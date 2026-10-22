<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PassSlip;
use App\Models\Employee;

class PassSlipController extends Controller
{
    
    public function edit($id)
    {
        $passSlip = \App\Models\PassSlip::findOrFail($id);
        return view('pass_slips.edit', compact('passSlip'));
    }
    
   public function store(Request $request)
    {
        // 1. Validate the incoming data (Notice we added employee_id here)
        $validated = $request->validate([
            'employee_id'          => 'nullable|exists:employees,id', // 🌟 NEW: Accept HR's dropdown choice
            'transaction_details'  => 'required|string',
            'transaction_date'     => 'required|date',
            'time_from'            => 'required',
            'time_to'              => 'required',
            'unlogged_count'       => 'nullable|integer|min:0|max:4',
        ]);

        // 2. Determine WHO is filing this form
        if (!empty($validated['employee_id'])) {
            // SCENARIO A: HR Staff selected an employee from the dropdown
            $employeeId = $validated['employee_id'];
        } else {
            // SCENARIO B: A normal Employee is filing for themselves
            $employee = \App\Models\Employee::where('company_id', auth()->user()->company_id)->firstOrFail();
            $employeeId = $employee->id;
        }

        // 3. Attach the correct Employee ID and checkboxes to the data array
        $validated['employee_id']          = $employeeId;
        $validated['unlogged_time_in_am']  = $request->has('unlogged_time_in_am');
        $validated['unlogged_time_out_am'] = $request->has('unlogged_time_out_am');
        $validated['unlogged_time_in_pm']  = $request->has('unlogged_time_in_pm');
        $validated['unlogged_time_out_pm'] = $request->has('unlogged_time_out_pm');

        // Save to Database
        \App\Models\PassSlip::create($validated);

        return redirect()->back()->with('success', 'Pass Slip filed successfully!');
    }
    
    public function print($id)
    {
        $passSlip = \App\Models\PassSlip::with('employee')->findOrFail($id);
        
        // Check if the employee belongs to HRMO
        $department = strtolower($passSlip->employee->department ?? '');
        $isHrmo = str_contains($department, 'hrmo') || str_contains($department, 'human resource');

        $deptHeadName = '';

        // Fetch the Department Head's name for the signature line
        if (!empty($passSlip->dept_head_id)) {
            $employee = \App\Models\Employee::find($passSlip->dept_head_id);
            if ($employee) {
                $deptHeadName = $employee->name;
            }
        }
        
        return view('pass_slips.print', compact('passSlip', 'deptHeadName', 'isHrmo'));
    }
    
    public function update(Request $request, $id)
    {
        $pass = \App\Models\PassSlip::findOrFail($id);
        
        $validated = $request->validate([
            'transaction_details'  => 'required|string|max:500',
            'transaction_date'     => 'required|date',
            'time_from'            => 'required|date_format:H:i',
            'time_to'              => 'required|date_format:H:i|after:time_from',
            'unlogged_time_in_am'  => 'nullable|boolean',
            'unlogged_time_out_am' => 'nullable|boolean',
            'unlogged_time_in_pm'  => 'nullable|boolean',
            'unlogged_time_out_pm' => 'nullable|boolean',
            'unlogged_count'       => 'nullable|integer|min:0|max:4',
        ]);

        // Fix checkboxes (if empty, set to 0)
        $validated['unlogged_time_in_am'] = $request->has('unlogged_time_in_am');
        $validated['unlogged_time_out_am'] = $request->has('unlogged_time_out_am');
        $validated['unlogged_time_in_pm'] = $request->has('unlogged_time_in_pm');
        $validated['unlogged_time_out_pm'] = $request->has('unlogged_time_out_pm');

        $pass->update($validated);

        return redirect()->route('dashboard')->with('success', 'Pass Slip updated successfully!');
    }
}