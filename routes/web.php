<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentHeadController;
use App\Http\Controllers\DtrController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeFormController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\IclockController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PassSlipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\UserController;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\TravelOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 🌐 ROOT & PUBLIC ROUTES
// =========================================================================

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// Hardware Routes (ZKTeco device push data)
Route::match(['get', 'post'], '/iclock/cdata', [IclockController::class, 'cdata']);
Route::match(['get', 'post'], '/iclock/getrequest', [IclockController::class, 'getRequest']);

// =========================================================================
// 🔒 SECURE AUTHENTICATED ROUTES
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // --- 👤 BREEZE PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- 👷 TIER 0: EMPLOYEE DESK (Accessible by all logged-in users) ---
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/forms', [EmployeeFormController::class, 'index'])->name('forms.index');

        // Form Stores (Anyone can file)
        Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
        Route::post('/travel', [TravelOrderController::class, 'store'])->name('travel.store');
        Route::post('/pass-slip', [PassSlipController::class, 'store'])->name('pass_slip.store');
    });

    // 🌟 ADMIN, HR, & EMPLOYEE: Form Edits 🌟
    Route::middleware(['role:admin,hr,employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/leave/{id}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
        Route::put('/leave/{id}', [LeaveController::class, 'update'])->name('leave.update');

        Route::get('/travel/{id}/edit', [TravelOrderController::class, 'edit'])->name('travel.edit');
        Route::put('/travel/{id}', [TravelOrderController::class, 'update'])->name('travel.update');

        Route::get('/pass-slip/{id}/edit', [PassSlipController::class, 'edit'])->name('pass_slip.edit');
        Route::put('/pass-slip/{id}', [PassSlipController::class, 'update'])->name('pass_slip.update');
    });

    // --- 👑 TIER 1: DEPARTMENT HEAD ---
    Route::prefix('department-head')->group(function () {
        Route::get('/dashboard', [DepartmentHeadController::class, 'index'])->name('dept_head.dashboard');
        Route::get('/staff', [DepartmentHeadController::class, 'staff'])->name('dept_head.staff');

        Route::post('/leave/{id}/approve', [DepartmentHeadController::class, 'approveLeave'])->name('dept_head.approve_leave');
        Route::post('/leave/{id}/decline', [DepartmentHeadController::class, 'declineLeave'])->name('dept_head.decline_leave');

        Route::post('/travel/{id}/approve', [DepartmentHeadController::class, 'approveTravelOrder'])->name('dept_head.approve_travel');
        Route::post('/travel/{id}/decline', [DepartmentHeadController::class, 'declineTravelOrder'])->name('dept_head.decline_travel');

        Route::post('/pass-slip/{id}/approve', [DepartmentHeadController::class, 'approvePassSlip'])->name('dept_head.approve_pass_slip');
        Route::post('/pass-slip/{id}/decline', [DepartmentHeadController::class, 'declinePassSlip'])->name('dept_head.decline_pass_slip');
    });

    // --- 👑 TIER 2: HRMO ---
    Route::middleware(['role:hrmo'])->group(function () {
        Route::prefix('hrmo')->group(function () {
            Route::get('/dashboard', function () {
                $pendingLeaves = Leave::with('employee')->where('status', 'pending_hrmo')->latest()->get();
                $pendingTravels = TravelOrder::with('employee')->where('status', 'pending_hrmo')->latest()->get();
                $employees = Employee::where('is_hidden', false)->orderBy('name')->get();

                return view('hrmo.dashboard', compact('employees', 'pendingLeaves', 'pendingTravels'));
            })->name('hrmo.dashboard');
        });

        Route::patch('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::patch('/leaves/{id}/decline', [LeaveController::class, 'decline'])->name('leaves.decline');
        Route::patch('/travel-orders/{id}/approve', [TravelOrderController::class, 'approve'])->name('travel_orders.approve');
        Route::patch('/travel-orders/{id}/decline', [TravelOrderController::class, 'decline'])->name('travel_orders.decline');
    });

    // --- 🏛️ TIER 3: MAYOR (FINAL APPROVAL) ---
    Route::middleware(['role:mayor'])->group(function () {
        Route::prefix('mayor')->group(function () {
            Route::get('/dashboard', function () {
                $pendingLeaves = Leave::with('employee')->where('status', 'pending_mayor')->latest()->get();
                $pendingTravels = TravelOrder::with('employee')->where('status', 'pending_mayor')->latest()->get();

                return view('mayor.dashboard', compact('pendingLeaves', 'pendingTravels'));
            })->name('mayor.dashboard');

            Route::patch('/mayor/leaves/{id}/approve', [LeaveController::class, 'approveMayor'])->name('mayor.leaves.approve');
            Route::patch('/mayor/leaves/{id}/decline', [LeaveController::class, 'declineMayor'])->name('mayor.leaves.decline');
            Route::patch('/mayor/travel-orders/{id}/approve', [TravelOrderController::class, 'approveMayor'])->name('mayor.travel_orders.approve');
            Route::patch('/mayor/travel-orders/{id}/decline', [TravelOrderController::class, 'declineMayor'])->name('mayor.travel_orders.decline');
        });
    });

    // --- 🧑‍💼 TIER 4: HR STAFF (RECORD KEEPING) ---
    Route::middleware(['role:hr'])->group(function () {
        Route::prefix('hr')->group(function () {
            Route::get('/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');
        });
    });

    // --- 🛠️ TIER 5: SYSTEM ADMIN ---
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // User Management Routes
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Admin Employee Management Modal Routes
        Route::resource('employees', EmployeeController::class)->except(['create', 'edit']);

        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);

        // Bulk Hide/Unhide Routes
        Route::post('/employees/bulk-hide', [DtrController::class, 'bulkHide'])->name('employees.bulk_hide');
        Route::post('/employees/bulk-unhide', [DtrController::class, 'bulkUnhide'])->name('employees.bulk_unhide');
        Route::get('/dtr/{id}/unhide', [DtrController::class, 'unhide'])->name('dtr.unhide')->whereNumber('id');
    });

    // --- 👨‍💼 ADMIN, HR, HRMO, & MAYOR SHARED TOOLS ---
    Route::middleware(['role:admin,hr,hrmo,mayor'])->group(function () {
        Route::post('/employees/{id}/toggle-head', [DtrController::class, 'toggleDepartmentHead'])->name('employees.toggle_dept_head');
        Route::post('/employees/bulk-department', [DtrController::class, 'bulkAssignDepartment'])->name('employees.bulk_department');

        Route::prefix('dtr')->name('dtr.')->group(function () {
            Route::get('/', [DtrController::class, 'index'])->name('index');
            Route::post('/import', [DtrController::class, 'importUserDat'])->name('import');
            Route::post('/import-logs', [DtrController::class, 'importAttlogDat'])->name('import_logs');

            Route::get('/{id}/hide', function ($id) {
                $employee = Employee::findOrFail($id);
                $employee->is_hidden = true;
                $employee->save();

                return redirect()->back()->with('success', 'Employee hidden successfully.');
            })->name('hide')->whereNumber('id');
        });



        Route::post('/analyze-sync', [DtrController::class, 'analyzeSync'])->name('dtr.analyze_sync');

        Route::get('/device', function () {
            $sns = Cache::get('zkteco_devices_list', []);
            $devices = [];
            foreach ($sns as $sn) {
                $data = Cache::get("zkteco_device_{$sn}");
                if ($data) {
                    $lastSeen = Carbon::parse($data['last_seen']);
                    $devices[] = [
                        'sn' => $data['sn'],
                        'ip' => $data['ip'],
                        'last_seen' => $data['last_seen'],
                        'is_online' => $lastSeen->greaterThan(now()->subMinutes(3)),
                    ];
                }
            }

            return view('device.status', compact('devices'));
        })->name('device.status');

       Route::get('/dtr/print-bulk', [DtrController::class, 'printBulk'])->name('dtr.print_bulk');
        Route::get('/dtr/send-emails', [DtrController::class, 'sendBulkEmails'])->name('dtr.send_emails');

        // Holiday & Suspension Management
Route::get('/admin/holidays', [\App\Http\Controllers\HolidayController::class, 'index'])->name('holidays.index');
Route::post('/admin/holidays', [\App\Http\Controllers\HolidayController::class, 'store'])->name('holidays.store');
Route::delete('/admin/holidays/{id}', [\App\Http\Controllers\HolidayController::class, 'destroy'])->name('holidays.destroy');
    });

    // --- 🟢 PUBLIC SHARED DTR & PRINTING ---
    Route::prefix('dtr')->name('dtr.')->group(function () {
        Route::get('/live', [DtrController::class, 'liveFeed'])->name('live');
        Route::get('/api/latest-logs', [DtrController::class, 'latestLogs'])->name('api.latest_logs');
        Route::get('/{id}', [DtrController::class, 'show'])->name('show')->whereNumber('id');
        Route::get('/{employee_id}/print', [DtrController::class, 'print'])->name('print')->whereNumber('employee_id');

        Route::get('/upload', [DtrController::class, 'uploadForm'])->name('upload_form');
        Route::post('/sync-all', [DtrController::class, 'syncAllData'])->name('sync_all');
    });

    Route::get('/leaves/{id}/print', [LeaveController::class, 'print'])->name('leaves.print')->whereNumber('id');
    Route::get('/travel-orders/{id}/print', [TravelOrderController::class, 'print'])->name('travel_orders.print')->whereNumber('id');
    Route::post('/generate-company-ids', [DtrController::class, 'generateCompanyIds'])->name('employees.generate_ids');
    Route::get('/pass-slips/{id}/print', [PassSlipController::class, 'print'])->name('pass_slips.print')->whereNumber('id');

    // --- 🚦 SMART DASHBOARD ROUTER ---
    Route::get('/dashboard', function () {
        $role = strtolower(trim(auth()->user()->role ?? ''));

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'mayor') {
            return redirect()->route('mayor.dashboard');
        } elseif ($role === 'hrmo') {
            return redirect()->route('hrmo.dashboard');
        } elseif ($role === 'hr') {
            return redirect()->route('hr.dashboard');
        } elseif ($role === 'department_head') {
            return redirect()->route('dept_head.dashboard');
        } else {
            return redirect()->route('employee.forms.index');
        }
    })->name('dashboard');
});

require __DIR__.'/auth.php';
