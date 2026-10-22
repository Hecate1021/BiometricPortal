<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use App\Models\TravelOrder;
use App\Models\Employee;

class EmployeeFormController extends Controller
{
    // Helper method to securely get the logged-in employee
    private function getCurrentEmployee()
    {
        return Employee::where('company_id', Auth::user()->company_id)->firstOrFail();
    }

    // ==========================================
    // UNIFIED VIEW (Shows the page with modals)
    // ==========================================
   public function index()
    {
        // 1. Get ONLY the currently logged-in employee
        $employee = $this->getCurrentEmployee();
        
        // 2. Fetch ONLY this employee's recent Travel Orders and Leaves
        $recentTravels = TravelOrder::where('employee_id', $employee->id)
                                    ->latest()
                                    ->take(5)
                                    ->get();
                                    
        $recentLeaves = Leave::where('employee_id', $employee->id)
                             ->latest()
                             ->take(5)
                             ->get();

        // 3. Fetch ONLY this employee's recent Pass Slips
        $recentPassSlips = \App\Models\PassSlip::where('employee_id', $employee->id)
                                              ->latest()
                                              ->take(5)
                                              ->get();

        return view('employee.forms.index', compact('employee', 'recentTravels', 'recentLeaves', 'recentPassSlips'));
    }

    // ==========================================
    // LEAVE FORM SUBMISSION
    // ==========================================
    public function storeLeave(Request $request)
    {
        $employee = $this->getCurrentEmployee();

        $validated = $request->validate([
            'office_department'          => 'nullable|string',
            'leave_type'                 => 'required|string',
            'leave_type_others'          => 'nullable|string',
            'leave_details'              => 'nullable|string',
            'details_specify_abroad'     => 'nullable|string',
            'details_specify_hospital'   => 'nullable|string',
            'details_specify_outpatient' => 'nullable|string',
            'details_specify_women'      => 'nullable|string',
            'working_days'               => 'required|numeric|min:0.5',
            'start_date'                 => 'required|date',
            'end_date'                   => 'required|date|after_or_equal:start_date',
            'commutation'                => 'required|string',
            'reason'                     => 'nullable|string',
        ]);

        // Security: Force the employee_id on the backend
        $validated['employee_id'] = $employee->id;
        $validated['type'] = $validated['leave_type']; 
        
        // 🌟 Push straight to the Department Head tier
        $validated['status'] = 'pending_dept_head'; 

        // Save EVERYTHING validated in one go
        Leave::create($validated);

        return back()->with('success', 'Leave application submitted successfully! It is now pending your Department Head\'s approval.');
    }

    // ==========================================
    // TRAVEL ORDER SUBMISSION
    // ==========================================
    public function storeTravel(Request $request)
    {
        $employee = $this->getCurrentEmployee();

        $validated = $request->validate([
            'departure_from' => 'required|string',
            'destination'    => 'required|string',
            'purpose'        => 'required|string',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
        ]);

        // Security: Force backend-generated data
        $validated['employee_id'] = $employee->id;
        $validated['form_number'] = 'TO-' . now()->format('Y-m') . '-' . rand(1000, 9999);
        $validated['expense_type'] = $request->input('expense_type', 'Official');
        
        // 🌟 Push straight to the Department Head tier
        $validated['status'] = 'pending_dept_head';

        // Save EVERYTHING validated in one go
        TravelOrder::create($validated);

        return back()->with('success', 'Travel Order submitted successfully! It is now pending your Department Head\'s approval.');
    }
}