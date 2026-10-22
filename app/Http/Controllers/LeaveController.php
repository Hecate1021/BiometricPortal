<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveController extends Controller
{
    
    public function edit($id)
    {
        $leave = \App\Models\Leave::findOrFail($id);
        return view('leaves.edit', compact('leave'));
    }
    
    
  public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'                => 'required|exists:employees,id',
            'date_of_filing'             => 'required|date',
            'start_date'                 => 'required|date',
            'end_date'                   => 'required|date|after_or_equal:start_date',
            'office_department'          => 'nullable|string',
            
            // 🌟 1. CHANGE VALIDATION TO ACCEPT ARRAYS 🌟
            'leave_type'                 => 'required|array', 
            'leave_type.*'               => 'string',
            'leave_type_others'          => 'nullable|string',
            
            'leave_details'              => 'nullable|array', 
            'leave_details.*'            => 'string',
            
            'details_specify_abroad'     => 'nullable|string',
            'details_specify_hospital'   => 'nullable|string',
            'details_specify_outpatient' => 'nullable|string',
            'details_specify_women'      => 'nullable|string',
            'working_days'               => 'required|numeric|min:0',
            'commutation'                => 'nullable|string',
        ]);

        // 🌟 2. CONVERT ARRAYS INTO COMMA-SEPARATED STRINGS FOR THE DATABASE 🌟
        $validated['leave_type'] = implode(', ', $validated['leave_type']);
        
        if (isset($validated['leave_details'])) {
            $validated['leave_details'] = implode(', ', $validated['leave_details']);
        }

        // 🌟 3. FIX: Simply append the custom text if the user typed something! 🌟
        $validated['type'] = $validated['leave_type']; 
        if (!empty($validated['leave_type_others'])) {
            // This will make it look like: "Vacation Leave - Trip to Manila"
            $validated['type'] .= ' - ' . $validated['leave_type_others'];
        }

        // ==========================================
        // 🌟 HR OVERRIDE: AUTO-APPROVE INSTANTLY 🌟
        // ==========================================
        $userRole = strtolower(trim(auth()->user()->role ?? ''));
        $isHrProxy = in_array($userRole, ['hr', 'hrmo', 'admin']);

        if ($isHrProxy) {
            $validated['status'] = 'approved';
            $validated['dept_head_approved_at'] = now();
            $validated['hrmo_approved_at'] = now();
            $validated['mayor_approved_at'] = now();
        } else {
            // Default status for regular employees
            $validated['status'] = 'pending'; 
        }

        Leave::create($validated);

        return redirect()->back()->with('success', 'Leave Application saved successfully!');
    }

    public function print($id)
    {
        $leave = Leave::with('employee')->findOrFail($id);
        return view('leaves.print', compact('leave'));
    }

    // ==========================================
    // HRMO APPROVAL TO MAYOR
    // ==========================================
    public function approve(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'pending_mayor', // Push to the final tier
            'hrmo_id' => auth()->id(),
            'hrmo_approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request verified and forwarded to the Mayor.');
    }

    // ==========================================
    // DECLINE LEAVE (With Remarks)
    // ==========================================
    public function decline(Request $request, $id)
    {
        // Validate that HR actually typed a reason
        $request->validate([
            'remarks' => 'required|string|max:500'
        ]);

        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'declined',
            'hrmo_id' => auth()->id(),
            'hrmo_approved_at' => now(),
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Leave request has been declined.');
    }

    // ==========================================
    // MAYOR'S FINAL APPROVAL TIER
    // ==========================================
    public function approveMayor(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'approved', // 🌟 Final Status!
            'mayor_id' => auth()->id(),
            'mayor_approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request fully approved!');
    }

    public function declineMayor(Request $request, $id)
    {
        $request->validate(['remarks' => 'required|string|max:500']);

        $leave = Leave::findOrFail($id);
        
        $leave->update([
            'status' => 'declined',
            'mayor_id' => auth()->id(),
            'mayor_approved_at' => now(),
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Leave request declined at the final tier.');
    }
    
public function update(Request $request, $id)
    {
        $leave = \App\Models\Leave::findOrFail($id);
        
        $validated = $request->validate([
            'date_of_filing'             => 'required|date',
            'start_date'                 => 'required|date',
            'end_date'                   => 'required|date|after_or_equal:start_date',
            'office_department'          => 'nullable|string',
            'leave_type'                 => 'required|array', 
            'leave_type.*'               => 'string',
            'leave_type_others'          => 'nullable|string',
            'leave_details'              => 'nullable|array', 
            'leave_details.*'            => 'string',
            'details_specify_abroad'     => 'nullable|string',
            'details_specify_hospital'   => 'nullable|string',
            'details_specify_outpatient' => 'nullable|string',
            'details_specify_women'      => 'nullable|string',
            'working_days'               => 'required|numeric|min:0',
            'commutation'                => 'nullable|string',
        ]);

        $validated['leave_type'] = implode(', ', $validated['leave_type']);
        if (isset($validated['leave_details'])) {
            $validated['leave_details'] = implode(', ', $validated['leave_details']);
        } else {
            $validated['leave_details'] = null;
        }

        $validated['type'] = $validated['leave_type']; 
        if (!empty($validated['leave_type_others'])) {
            $validated['type'] .= ' - ' . $validated['leave_type_others'];
        }

        $leave->update($validated);

        return redirect()->route('dashboard')->with('success', 'Leave Application updated successfully!');
    }
}