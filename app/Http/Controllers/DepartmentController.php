<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255|unique:departments,name',
            'email'            => 'nullable|email|max:255',
            'required_punches' => 'required|integer|in:2,4',
        ]);

        Department::create([
            'name'             => strtoupper($request->name),
            'email'            => strtolower($request->email),
            'required_punches' => $request->required_punches
        ]);

        return redirect()->back()->with('success', 'Department added successfully!');
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255|unique:departments,name,' . $id,
            'email'            => 'nullable|email|max:255',
            'required_punches' => 'required|integer|in:2,4',
        ]);

        // If the name changed, we should also update the employees assigned to it
        if ($department->name !== strtoupper($request->name)) {
            Employee::where('department', $department->name)->update([
                'department' => strtoupper($request->name)
            ]);
        }

        $department->update([
            'name'             => strtoupper($request->name),
            'email'            => strtolower($request->email),
            'required_punches' => $request->required_punches
        ]);

        return redirect()->back()->with('success', 'Department updated successfully!');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        // Unassign employees before deleting the department
        Employee::where('department', $department->name)->update(['department' => null]);

        $department->delete();

        return redirect()->back()->with('success', 'Department removed. Assigned employees are now marked as Unassigned.');
    }
}
