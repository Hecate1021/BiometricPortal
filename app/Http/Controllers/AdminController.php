<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Models\Leave;
use App\Models\TravelOrder;
use App\Models\PassSlip;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');

        // 1. Total Active Employees
        $totalEmployees = Employee::where('is_hidden', false)->count();

        // 2. Punched in today (Unique employees who scanned at least once today)
        $punchedInToday = AttendanceLog::whereDate('punch_time', $today)
                            ->distinct('employee_id')
                            ->count('employee_id');

        // 3. On Leave / OB / Pass Slip today
        $leavesToday = Leave::where('status', 'approved')
                        ->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->count();
                        
        $travelsToday = TravelOrder::where('status', 'approved')
                        ->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->count();
                        
        $passSlipsToday = PassSlip::whereNotNull('dept_head_approved_at')
                        ->whereDate('transaction_date', $today)
                        ->count();
                        
        $onLeaveOrOb = $leavesToday + $travelsToday + $passSlipsToday;

        // 4. Absent (Estimate: Total minus people who showed up or are strictly on Leave/Travel)
        $absentToday = max(0, $totalEmployees - $punchedInToday - $leavesToday - $travelsToday);

        // 5. Generate 7-Day Chart Data
        $chartLabels = [];
        $chartData = [];
        
        // Loop backwards from 6 days ago up to today
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('D'); 
            
            $count = AttendanceLog::whereDate('punch_time', $date->format('Y-m-d'))
                        ->distinct('employee_id')
                        ->count('employee_id');
            
            $chartData[] = $count;
        }

        // 6. 🌟 MONTHLY FAIR DEPARTMENT CHART 🌟
        // Get selected month/year from URL, default to current month/year
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();

        // Step A: Count how many active employees are in each department
        $departmentEmployeeCounts = DB::table('employees')
            ->select('department', DB::raw('COUNT(id) as emp_count'))
            ->where('is_hidden', false)
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->where('department', '!=', 'Unassigned')
            ->groupBy('department')
            ->pluck('emp_count', 'department');

        // Step B: Get total raw punches for each department in the selected timeframe
        $departmentPunches = DB::table('attendance_logs')
            ->join('employees', 'attendance_logs.employee_id', '=', 'employees.id')
            ->whereBetween('attendance_logs.punch_time', [$startOfMonth, $endOfMonth])
            ->whereNotNull('employees.department')
            ->where('employees.department', '!=', '')
            ->where('employees.department', '!=', 'Unassigned')
            ->select('employees.department', DB::raw('COUNT(attendance_logs.id) as total_punches'))
            ->groupBy('employees.department')
            ->get();

        // Step C: Calculate the fair average (Total Punches ÷ Number of Employees)
        $fairAttendanceScores = collect();

        foreach ($departmentPunches as $data) {
            $dept = $data->department;
            $empCount = $departmentEmployeeCounts->get($dept, 0);

            if ($empCount > 0) {
                $average = round($data->total_punches / $empCount, 1);
                $fairAttendanceScores->push([
                    'department' => $dept,
                    'average' => $average
                ]);
            }
        }

        // Step D: Sort by highest average and get the Top 7
        $topDepartments = $fairAttendanceScores->sortByDesc('average')->take(7);

        $deptChartLabels = $topDepartments->pluck('department')->toArray();
        $deptChartData = $topDepartments->pluck('average')->toArray();

        if (empty($deptChartLabels)) {
            $deptChartLabels = ['No Data Yet'];
            $deptChartData = [0];
        }

        return view('admin.dashboard', compact(
            'totalEmployees', 'punchedInToday', 'onLeaveOrOb', 'absentToday', 
            'chartLabels', 'chartData', 
            'deptChartLabels', 'deptChartData',
            'selectedMonth', 'selectedYear'
        ));
    }
}