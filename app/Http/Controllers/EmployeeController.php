<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display the Admin Employee Masterlist (with built-in Modals)
     */
    public function index()
    {
        $employees = Employee::where('is_hidden', false)->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        // 🌟 UPDATED TO MATCH YOUR NEW SECURE FOLDER PATH 🌟
        return view('admin.employees.index', compact('employees', 'departments'));
    }

    /**
     * Store a newly created employee (From the Add Modal)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'company_id'   => 'nullable|string|max:255',
            'biometric_id' => 'nullable|string|max:255',
            'department'   => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255', 
        ]);

        Employee::create($validated);

        return redirect()->back()->with('success', 'Employee added successfully!');
    }

    /**
     * Update the employee (From the Edit Modal)
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'company_id'   => 'nullable|string|max:255',
            'biometric_id' => 'nullable|string|max:255',
            'department'   => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255', 
        ]);

        $employee->update($validated);

        return redirect()->back()->with('success', $employee->name . "'s information has been updated!");
    }

    /**
     * Hide the employee (From the Delete Modal)
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        $employee->update(['is_hidden' => true]); 

        return redirect()->back()->with('success', $employee->name . ' has been removed from the active list.');
    }
}