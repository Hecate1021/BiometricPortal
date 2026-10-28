<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Models\Leave;
use App\Models\TravelOrder;
use App\Models\User;
use App\Models\PassSlip;
use App\Models\Department;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepartmentDtrReport;
use Spatie\Browsershot\Browsershot;

class DtrController extends Controller
{
    // ==============================================================
    // 1. MAIN VIEWS (Index, Show, Print, Email)
    // ==============================================================

    public function index()
    {
        $employees = Employee::where('is_hidden', false)
            ->withCount('attendanceLogs')
            ->orderBy('name')
            ->get();

        $departments = Department::orderBy('name')->get();

        return view('dtr.index', compact('employees', 'departments'));
    }

    public function bulkHide(Request $request)
    {
        if (empty($request->employee_ids)) return back();
        \App\Models\Employee::whereIn('id', $request->employee_ids)->update(['is_hidden' => true]);
        return back()->with('success', count($request->employee_ids) . ' employees successfully hidden.');
    }

    public function bulkUnhide(Request $request)
    {
        if (empty($request->employee_ids)) return back();
        \App\Models\Employee::whereIn('id', $request->employee_ids)->update(['is_hidden' => false]);
        return back()->with('success', count($request->employee_ids) . ' employees successfully unhidden.');
    }

    public function unhide($id)
    {
        $employee = \App\Models\Employee::findOrFail($id);
        $employee->update(['is_hidden' => false]);
        return back()->with('success', $employee->name . ' is now visible in the system.');
    }

    public function bulkAssignDepartment(Request $request)
    {
        $request->validate([
            'department' => 'required|string',
            'employee_ids' => 'required|array',
        ]);

        Employee::whereIn('id', $request->employee_ids)->update([
            'department' => $request->department
        ]);

        $count = count($request->employee_ids);
        return redirect()->back()->with('success', "Successfully assigned {$count} employees to {$request->department}!");
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $targetEmployee = Employee::findOrFail($id);
        $myEmployeeRecord = Employee::where('company_id', $user->company_id)->first();

        if ($user->role === 'employee') {
            if (!$myEmployeeRecord || $myEmployeeRecord->id != $id) {
                abort(403, 'UNAUTHORIZED: You are only allowed to view your own DTR.');
            }
        }

        if ($user->role === 'department_head') {
            if (!$myEmployeeRecord || ($myEmployeeRecord->id != $id && $myEmployeeRecord->department !== $targetEmployee->department)) {
                abort(403, 'UNAUTHORIZED: You can only view DTRs for employees in your department.');
            }
        }

        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $data = $this->generateDtrData($id, $year, $month, false);

        return view('dtr.show', $data);
    }

    public function print(Request $request, $employee_id)
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $data = $this->generateDtrData($employee_id, $year, $month, true);

        return view('dtr.print', $data);
    }

    public function printBulk(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $department = $request->input('department');

        $query = Employee::where('is_hidden', false);

        if (!empty($department) && $department !== 'All') {
            $query->where('department', $department);
        }

        $employees = $query->orderBy('name')->get();

        if ($employees->isEmpty()) {
            return redirect()->back()->with('error', 'No employees found for this department.');
        }

        $allDtrs = [];
        foreach ($employees as $emp) {
            $allDtrs[] = $this->generateDtrData($emp->id, $year, $month, true);
        }

        return view('dtr.print_bulk', compact('allDtrs', 'department', 'month', 'year'));
    }

    public function sendBulkEmails(Request $request)
    {
        set_time_limit(300);

        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $departmentParam = $request->input('department');

        if ($departmentParam === 'All') {
            $departments = \App\Models\Department::whereNotNull('email')->where('email', '!=', '')->get();
        } else {
            $departments = \App\Models\Department::where('name', $departmentParam)->whereNotNull('email')->get();
        }

        if ($departments->isEmpty()) {
            return back()->withErrors(['No departments found with a configured email address.']);
        }

        $emailsSent = 0;

        foreach ($departments as $dept) {
            $employees = \App\Models\Employee::where('department', $dept->name)
                            ->where('is_hidden', false)
                            ->orderBy('name', 'asc')
                            ->get();

            if ($employees->isEmpty()) continue;

            $allDtrs = [];
            foreach ($employees as $employee) {
                $allDtrs[] = $this->generateDtrData($employee->id, $year, $month, true);
            }

            $html = view('dtr.print_bulk', [
                'allDtrs' => $allDtrs,
                'department' => $dept->name,
                'month' => $month,
                'year' => $year
            ])->render();

            // 🌟 HOSTINGER FIX: Generate PDF natively via PHP
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('legal', 'portrait');
            $pdfContent = $pdf->output();

            \Illuminate\Support\Facades\Mail::to($dept->email)
                ->send(new \App\Mail\DepartmentDtrReport($pdfContent, $dept->name, $month, $year));

            $emailsSent++;
        }

        if ($emailsSent > 0) {
            return back()->with('success', "Successfully sent $emailsSent department email(s)!");
        } else {
            return back()->withErrors(['Could not find employees for the selected departments.']);
        }
    }

    // ==============================================================
    // 2. LIVE FEED & API
    // ==============================================================

    public function liveFeed()
    {
        $logs = $this->getLiveLogsQuery()->get();
        return view('dtr.live', compact('logs'));
    }

    public function latestLogs()
    {
        $logs = $this->getLiveLogsQuery()->get();
        return response()->json($logs);
    }

    private function getLiveLogsQuery()
    {
        $today = Carbon::today()->format('Y-m-d');

        $query = AttendanceLog::with('employee')
            ->whereDate('punch_time', $today)
            ->orderBy('punch_time', 'desc');

        if (auth()->check() && auth()->user()->role === 'employee') {
            $employee = Employee::where('company_id', auth()->user()->company_id)->first();

            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }

    // ==============================================================
    // 3. USB DATA SYNC (Stateless - No Storage Needed)
    // ==============================================================

    public function uploadForm()
    {
        return view('dtr.upload');
    }

    public function syncAllData(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);

        if (!$payload) {
            return redirect()->back()->with('error', 'No data received. Please load the files first.');
        }

        $users = $payload['users'] ?? [];
        $logs = $payload['logs'] ?? [];

        $userCount = 0;
        $logCount = 0;

        foreach ($users as $biometricId => $name) {
            $employee = Employee::where('biometric_id', $biometricId)
                                ->orWhere('secondary_biometric_id', $biometricId)
                                ->first();

            if (!$employee) {
                $employee = new Employee(['biometric_id' => $biometricId]);
            }

            $employee->name = $name;

            if (!$employee->exists || empty($employee->company_id)) {
                $employee->company_id = $biometricId;
            }

            $employee->save();
            $userCount++;
        }

        foreach ($logs as $log) {
            $employee = Employee::where('biometric_id', $log['id'])
                                ->orWhere('secondary_biometric_id', $log['id'])
                                ->first();

            if ($employee) {
                $timestamp = $log['date'] . ' ' . $log['time'];
                $exists = AttendanceLog::where('employee_id', $employee->id)
                    ->where('punch_time', $timestamp)
                    ->exists();

                if (!$exists) {
                    AttendanceLog::create([
                        'employee_id' => $employee->id,
                        'punch_time'  => $timestamp,
                        'punch_type'  => '0'
                    ]);
                    $logCount++;
                }
            }
        }

        return redirect()->back()->with('success', "Successfully synced {$userCount} employees and {$logCount} new punches!");
    }

    public function analyzeSync(Request $request)
    {
        $logs = $request->input('logs', []);

        if (empty($logs)) {
            return response()->json(['new' => 0, 'duplicates' => 0]);
        }

        $duplicateCount = 0;
        $newCount = 0;

        $zktecoIds = collect($logs)->pluck('id')->map(fn($id) => trim((string)$id))->unique()->toArray();

        $employees = Employee::whereIn('biometric_id', $zktecoIds)
                             ->orWhereIn('secondary_biometric_id', $zktecoIds)
                             ->get();

        $employeeMap = [];
        foreach ($employees as $emp) {
            if (!empty($emp->biometric_id)) $employeeMap[$emp->biometric_id] = $emp->id;
            if (!empty($emp->secondary_biometric_id)) $employeeMap[$emp->secondary_biometric_id] = $emp->id;
        }

        $internalIds = array_values($employeeMap);
        $searchIds = array_unique(array_merge($zktecoIds, $internalIds));

        $existingLogs = AttendanceLog::whereIn('employee_id', $searchIds)
            ->select('employee_id', 'punch_time')
            ->get()
            ->mapWithKeys(function ($log) {
                $rawTime = $log->getAttributes()['punch_time'] ?? $log->punch_time;
                $formattedTime = Carbon::parse($rawTime)->format('Y-m-d H:i:s');

                return [trim((string)$log->employee_id) . '|' . $formattedTime => true];
            })->toArray();

        foreach ($logs as $log) {
            $zkId = trim((string)$log['id']);
            $internalId = isset($employeeMap[$zkId]) ? (string)$employeeMap[$zkId] : null;

            $punchTime = Carbon::parse(trim($log['date']) . ' ' . trim($log['time']))->format('Y-m-d H:i:s');

            $existsAsZk = isset($existingLogs[$zkId . '|' . $punchTime]);
            $existsAsInternal = $internalId && isset($existingLogs[$internalId . '|' . $punchTime]);

            if ($existsAsZk || $existsAsInternal) {
                $duplicateCount++;
            } else {
                $newCount++;
            }
        }

        return response()->json([
            'new' => $newCount,
            'duplicates' => $duplicateCount
        ]);
    }

    // ==============================================================
    // 4. PRIVATE HELPERS (The Core Math Engine)
    // ==============================================================
    private function generateDtrData($employeeId, $year, $month, $isPrint = false)
    {
        $employee = \App\Models\Employee::findOrFail($employeeId);
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        $department = \App\Models\Department::where('name', $employee->department)->first();
        $requiredPunches = $department ? $department->required_punches : 4;

        $rawLogs = \App\Models\AttendanceLog::where('employee_id', $employee->id)
            ->whereBetween('punch_time', [$startDate->copy()->subDay(), $endDate->copy()->addDay()])
            ->orderBy('punch_time')
            ->get();

        $leaves = \App\Models\Leave::where('employee_id', $employee->id)->where('status', 'approved')
            ->where('start_date', '<=', $endDate->format('Y-m-d'))->where('end_date', '>=', $startDate->format('Y-m-d'))->get();

        $travels = \App\Models\TravelOrder::where('employee_id', $employee->id)->where('status', 'approved')
            ->where('start_date', '<=', $endDate->format('Y-m-d'))->where('end_date', '>=', $startDate->format('Y-m-d'))->get();

        $passSlips = \App\Models\PassSlip::where('employee_id', $employee->id)->whereNotNull('dept_head_approved_at')
            ->whereBetween('transaction_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();

        $holidays = \App\Models\Holiday::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();

        $pairedFlexibleShifts = [];
        if ($requiredPunches == 2) {
            $pendingIn = null;
            foreach ($rawLogs as $log) {
                $pt = Carbon::parse($log->punch_time);
                if (!$pendingIn) {
                    $pendingIn = $pt;
                } else {
                    if ($pendingIn->diffInHours($pt) > 16) {
                        $pairedFlexibleShifts[] = ['in' => $pendingIn, 'out' => null, 'date' => $pendingIn->format('Y-m-d')];
                        $pendingIn = $pt;
                    } else {
                        $pairedFlexibleShifts[] = ['in' => $pendingIn, 'out' => $pt, 'date' => $pendingIn->format('Y-m-d')];
                        $pendingIn = null;
                    }
                }
            }
            if ($pendingIn) $pairedFlexibleShifts[] = ['in' => $pendingIn, 'out' => null, 'date' => $pendingIn->format('Y-m-d')];
        }

        $groupedLogs = $rawLogs->groupBy(fn($log) => Carbon::parse($log->punch_time)->format('Y-m-d'));

        $dtr = [];
        $period = CarbonPeriod::create($startDate->startOfDay(), $endDate->startOfDay());

        $totLateMins = $totEarlyMins = $totWorkMins = 0;
        $reqWorkDays = $renDaysCount = $absentDaysCount = $leaveObCount = 0;

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');

            $travel = $travels->first(fn($t) => $dateStr >= $t->start_date && $dateStr <= $t->end_date);
            $leave = $leaves->first(fn($l) => $dateStr >= $l->start_date && $dateStr <= $l->end_date);
            $passSlip = $passSlips->first(fn($p) => $dateStr === $p->transaction_date);
            $holiday = $holidays->firstWhere('date', $dateStr);

            $remark = $remarkDetails = $remarkId = null;
            $passSlipData = null;

            if ($travel) {
                $expenseType = strtolower(trim($travel->expense_type ?? 'official'));
                $remark = $expenseType === 'personal' ? 'PERSONAL TRAVEL' : ($isPrint ? 'Executive' : 'OFFICIAL BUSINESS');
                $remarkId = 'travel_' . $travel->id;
                $remarkDetails = ['form_number' => $travel->form_number ?? 'N/A', 'purpose' => $travel->purpose, 'route' => $travel->departure_from . ' / ' . $travel->destination];
            } elseif ($leave) {
                $remark = $isPrint ? 'Leave' : strtoupper($leave->type);
                $remarkId = 'leave_' . $leave->id;
                $remarkDetails = $isPrint ? ['form_number' => $leave->type, 'purpose' => $leave->office_department, 'route' => ''] : null;
            } elseif ($holiday) {
                if ($holiday->type !== 'suspension') {
                    $remark = strtoupper($holiday->name);
                    $remarkId = 'holiday_' . $holiday->id;
                }
            }

            if ($passSlip) {
                $passSlipData = [
                    'time_from' => Carbon::parse($passSlip->time_from)->format('h:i A'),
                    'time_to'   => Carbon::parse($passSlip->time_to)->format('h:i A'),
                    'am_in'     => $passSlip->unlogged_time_in_am, 'am_out' => $passSlip->unlogged_time_out_am,
                    'pm_in'     => $passSlip->unlogged_time_in_pm, 'pm_out' => $passSlip->unlogged_time_out_pm,
                ];
            }

            $amIn = $amOut = $pmIn = $pmOut = null;
            $lateMinutes = $earlyMinutes = $totalMinutes = 0;
            $autoAmOut = $autoPmOut = false;

            if ($requiredPunches == 2) {
                $dayShifts = array_filter($pairedFlexibleShifts, fn($s) => $s['date'] == $dateStr);

                if (!empty($dayShifts)) {
                    $firstShift = reset($dayShifts);
                    $amIn = $firstShift['in'];
                    $pmOut = $firstShift['out'];

                    if ($amIn && $pmOut) {
                        $totalMinutes = $amIn->diffInMinutes($pmOut);
                    }
                }

                if ($passSlip) {
                    if ($passSlip->unlogged_time_in_am && !$amIn) $amIn = Carbon::parse($dateStr . ' ' . $passSlip->time_from);
                    if ($passSlip->unlogged_time_out_pm && !$pmOut) $pmOut = Carbon::parse($dateStr . ' ' . $passSlip->time_to);
                    if ($amIn && $pmOut && $totalMinutes == 0) $totalMinutes = $amIn->diffInMinutes($pmOut);
                }

                if ($totalMinutes > 720) {
                    $totalMinutes = 720;
                }

            } else {
                $dailyLogs = $groupedLogs->get($dateStr, collect());
                $times = $dailyLogs->pluck('punch_time')->map(fn($t) => Carbon::parse($t))->sort()->values();
                $count = $times->count();

                if ($count === 1) {
                    if ($times[0]->hour < 12) $amIn = $times[0]; elseif ($times[0]->hour <= 14) $pmIn = $times[0]; else $pmOut = $times[0];
                } elseif ($count === 2) {
                    if ($times[0]->hour >= 11) {
                        if ($times[0]->hour < 14) { $amOut = $times[0]; $times[1]->hour <= 14 ? $pmIn = $times[1] : $pmOut = $times[1]; }
                        else { $pmIn = $times[0]; $pmOut = $times[1]; }
                    } elseif ($times[0]->hour < 11 && $times[1]->hour < 14) { $amIn = $times[0]; $amOut = $times[1]; }
                    else { $amIn = $times[0]; $pmOut = $times[1]; }
                } elseif ($count === 3) {
                    if ($times[0]->hour >= 11) { $amOut = $times[0]; $pmIn = $times[1]; $pmOut = $times[2]; }
                    else {
                        $amIn = $times[0];
                        if ($times[1]->hour < 13) { $amOut = $times[1]; $times[2]->hour <= 14 ? $pmIn = $times[2] : $pmOut = $times[2]; }
                        else { $pmIn = $times[1]; $pmOut = $times[2]; }
                    }
                } elseif ($count >= 4) {
                    if ($times[0]->hour >= 11) { $amOut = $times[0]; $pmIn = $times[1]; $pmOut = $times[$count - 1]; }
                    else { $amIn = $times[0]; $amOut = $times[1]; $pmIn = $times[2]; $pmOut = $times[$count - 1]; }
                }

                $shiftAmStart = Carbon::parse($dateStr . ' 08:00:00'); $shiftAmEnd = Carbon::parse($dateStr . ' 12:00:00');
                $shiftPmStart = Carbon::parse($dateStr . ' 13:00:00'); $shiftPmEnd = Carbon::parse($dateStr . ' 17:00:00');

                if ($holiday && $holiday->type === 'suspension' && $holiday->time_suspended) {
                    $suspendTime = Carbon::parse($dateStr . ' ' . $holiday->time_suspended);

                    if ($suspendTime <= $shiftAmEnd) {
                        $shiftAmEnd = $suspendTime;
                        $shiftPmStart = $shiftPmEnd = $suspendTime;
                        if ($amIn && !$amOut) { $amOut = $suspendTime->copy(); $autoAmOut = true; }
                    } else {
                        $shiftPmEnd = $suspendTime;
                        if ($pmIn && !$pmOut) { $pmOut = $suspendTime->copy(); $autoPmOut = true; }
                    }
                }

                if ($passSlip) {
                    if ($passSlip->unlogged_time_in_am && !$amIn) $amIn = $shiftAmStart->copy();
                    if ($passSlip->unlogged_time_out_am && !$amOut) $amOut = $shiftAmEnd->copy();
                    if ($passSlip->unlogged_time_in_pm && !$pmIn) $pmIn = $shiftPmStart->copy();
                    if ($passSlip->unlogged_time_out_pm && !$pmOut) $pmOut = $shiftPmEnd->copy();
                }

                if ($amIn && $amOut) {
                    $actualAmIn = $amIn->copy()->max($shiftAmStart); $actualAmOut = $amOut->copy()->min($shiftAmEnd);
                    if ($actualAmIn > $shiftAmStart) $lateMinutes += $shiftAmStart->diffInMinutes($actualAmIn);
                    if ($actualAmOut < $shiftAmEnd) $earlyMinutes += $actualAmOut->diffInMinutes($shiftAmEnd);
                    if ($actualAmOut > $actualAmIn) $totalMinutes += $actualAmIn->diffInMinutes($actualAmOut);
                }

                if ($pmIn && $pmOut && $shiftPmStart != $shiftPmEnd) {
                    $actualPmIn = $pmIn->copy()->max($shiftPmStart); $actualPmOut = $pmOut->copy()->min($shiftPmEnd);
                    if ($actualPmIn > $shiftPmStart) $lateMinutes += $shiftPmStart->diffInMinutes($actualPmIn);
                    if ($actualPmOut < $shiftPmEnd) $earlyMinutes += $actualPmOut->diffInMinutes($shiftPmEnd);
                    if ($actualPmOut > $actualPmIn) $totalMinutes += $actualPmIn->diffInMinutes($actualPmOut);
                }
            }

            if ($remark) {
                if (!$holiday || in_array($holiday->type, ['regular', 'special'])) {
                    $totalMinutes = 480;
                    $lateMinutes = 0;
                    $earlyMinutes = 0;
                }
            }

            if ($holiday && $holiday->type === 'suspension' && $holiday->time_suspended) {
                if ($amIn || $pmIn) {
                    $totalMinutes = 480 - $lateMinutes - $earlyMinutes;
                }
            }

            $totLateMins += $lateMinutes;
            $totEarlyMins += $earlyMinutes;
            $totWorkMins += $totalMinutes;

            if (!$date->isWeekend() && (!$holiday || $holiday->type !== 'regular')) {
                $reqWorkDays++;
                if ($totalMinutes > 0 || $remark) {
                    $renDaysCount++;
                    if ($remark) $leaveObCount++;
                } else {
                    $absentDaysCount++;
                }
            } else {
                if ($totalMinutes > 0 || $remark) $renDaysCount++;
            }

            $amInStr = $amIn ? $amIn->format($isPrint ? 'H:i' : 'h:i A') : '';
            $amOutStr = $amOut ? ($autoAmOut ? 'EXEMPT' : $amOut->format($isPrint ? 'H:i' : 'h:i A')) : '';
            $pmInStr = $pmIn ? $pmIn->format($isPrint ? 'H:i' : 'h:i A') : '';
            $pmOutStr = $pmOut ? ($autoPmOut ? 'EXEMPT' : $pmOut->format($isPrint ? 'H:i' : 'h:i A')) : '';

            if ($holiday && $holiday->type === 'suspension' && $holiday->time_suspended) {
                $sTimeStr = Carbon::parse($holiday->time_suspended)->format('H:i');

                if ($sTimeStr <= '11:30') {
                    if (!$amOut || $autoAmOut) $amOutStr = 'EXEMPT';
                    if (!$pmIn) $pmInStr = 'EXEMPT';
                    if (!$pmOut || $autoPmOut) $pmOutStr = 'EXEMPT';
                } elseif ($sTimeStr <= '13:00') {
                    if (!$pmIn) $pmInStr = 'EXEMPT';
                    if (!$pmOut || $autoPmOut) $pmOutStr = 'EXEMPT';
                } else {
                    if (!$pmOut || $autoPmOut) $pmOutStr = 'EXEMPT';
                }
            }

            $hoursWorkedStr = $totalMinutes > 0 ? floor($totalMinutes / 60) . ($isPrint ? ':' : 'h ') . str_pad($totalMinutes % 60, 2, '0', STR_PAD_LEFT) . ($isPrint ? '' : 'm') : ($isPrint ? '' : '--');
            $lateStr = $lateMinutes > 0 ? floor($lateMinutes / 60) . ($isPrint ? ':' : 'h ') . str_pad($lateMinutes % 60, 2, '0', STR_PAD_LEFT) . ($isPrint ? '' : 'm') : ($isPrint ? '' : '--');
            $earlyStr = $earlyMinutes > 0 ? floor($earlyMinutes / 60) . ($isPrint ? ':' : 'h ') . str_pad($earlyMinutes % 60, 2, '0', STR_PAD_LEFT) . ($isPrint ? '' : 'm') : ($isPrint ? '' : '--');

            $dtr[] = [
                'date'           => $dateStr,
                'day_num'        => (int) $date->format('d'),
                'day_name'       => $date->format('D'),
                'is_weekend'     => $date->isWeekend(),
                'am_in'          => $amInStr,
                'am_out'         => $amOutStr,
                'pm_in'          => $pmInStr,
                'pm_out'         => $pmOutStr,
                'late'           => $lateStr,
                'early'          => $earlyStr,
                'total_hrs'      => $hoursWorkedStr,
                'remark'         => $remark,
                'remark_details' => $remarkDetails,
                'remark_id'      => $remarkId,
                'pass_slip'      => $passSlipData,
                'rowspan'        => 1,
                'hide_remark'    => false,
            ];
        }

        for ($i = 0; $i < count($dtr); $i++) {
            if ($dtr[$i]['remark_id']) {
                $span = 1;
                for ($j = $i + 1; $j < count($dtr); $j++) {
                    if ($dtr[$j]['remark_id'] === $dtr[$i]['remark_id']) {
                        $span++;
                        $dtr[$j]['hide_remark'] = true;
                    } else {
                        break;
                    }
                }
                $dtr[$i]['rowspan'] = $span;
                $i = $i + $span - 1;
            }
        }

        $total_late = $totLateMins > 0 ? floor($totLateMins / 60) . ':' . str_pad($totLateMins % 60, 2, '0', STR_PAD_LEFT) : '0:00';
        $total_undertime = $totEarlyMins > 0 ? floor($totEarlyMins / 60) . ':' . str_pad($totEarlyMins % 60, 2, '0', STR_PAD_LEFT) : '0:00';
        $total_workhour = $totWorkMins > 0 ? floor($totWorkMins / 60) . ':' . str_pad($totWorkMins % 60, 2, '0', STR_PAD_LEFT) : '0:00';

        $rendered_days = floor($totWorkMins / 480) . " D | " . floor(($totWorkMins % 480) / 60) . " H | " . ($totWorkMins % 60) . " m";

        $missingMins = max(0, ($reqWorkDays * 480) - $totWorkMins);
        $total_absences = floor($missingMins / 480) . " D | " . floor(($missingMins % 480) / 60) . " H | " . ($missingMins % 60) . " m";

        $leave_ob_text = "LEAVE {$leaveObCount} D | 0 H";

        return [
            'employee'          => $employee,
            'dtr'               => $dtr,
            'month'             => $month,
            'year'              => $year,
            'required_workdays' => $reqWorkDays,
            'rendered_days'     => $rendered_days,
            'total_absences'    => $total_absences,
            'total_late'        => $total_late,
            'total_undertime'   => $total_undertime,
            'total_workhour'    => $total_workhour,
            'leave_ob_text'     => $leave_ob_text
        ];
    }

    // ==============================================================
    // 5. USER / COMPANY ID MANAGEMENT
    // ==============================================================

    public function generateCompanyIds()
    {
        set_time_limit(0);

        $count = 0;

        Employee::chunk(100, function ($employees) use (&$count) {
            foreach ($employees as $employee) {
                $oldCompanyId = $employee->company_id;

                $cleanName = preg_replace('/[^A-Za-z]/', '', $employee->name);
                $prefix = strtoupper(substr($cleanName, 0, 2));

                if (strlen($prefix) < 2) { $prefix = 'EM'; }

                $newCompanyId = $prefix . str_pad($employee->id, 4, '0', STR_PAD_LEFT);

                if ($oldCompanyId === $newCompanyId) {
                    continue;
                }

                $user = null;
                if (!empty($oldCompanyId)) {
                    $user = User::where('company_id', $oldCompanyId)->first();
                }

                if ($user) {
                    $user->update([
                        'company_id' => $newCompanyId,
                        'email' => strtolower($newCompanyId) . '@hrportal.local'
                    ]);
                } else {
                    User::firstOrCreate(
                        ['company_id' => $newCompanyId],
                        [
                            'name' => $employee->name,
                            'email' => strtolower($newCompanyId) . '@hrportal.local',
                            'password' => Hash::make('password123'),
                            'role' => 'employee'
                        ]
                    );
                }

                $employee->update([
                    'company_id' => $newCompanyId
                ]);

                $count++;
            }
        });

        return redirect()->back()->with('success', "Success! Reformatted {$count} Company IDs and synced their login credentials.");
    }

    public function toggleDepartmentHead($id)
    {
        $employee = Employee::findOrFail($id);
        $user = User::where('company_id', $employee->company_id)->first();

        if (!$user) {
            return redirect()->back()->with('error', "No login account found for {$employee->name}. Please click 'Auto-Generate Company IDs' first to create their account.");
        }

        if (strtolower(trim($user->role)) === 'department_head') {
            $user->update(['role' => 'employee']);
            $msg = "{$employee->name} has been changed back to a regular Employee.";
        } else {
            $user->update(['role' => 'department_head']);
            $msg = "👑 {$employee->name} is now a Department Head!";
        }

        return redirect()->back()->with('success', $msg);
    }
}