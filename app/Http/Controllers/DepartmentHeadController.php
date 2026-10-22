<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\TravelOrder;
use Illuminate\Http\Request;

class DepartmentHeadController extends Controller
{
    /**
     * Get the logged-in Department Head's employee record.
     */
    private function getMyRecord()
    {
        $user = auth()->user();
        if (!in_array(auth()->user()->role, ['department_head', 'hrmo'])) {
            abort(403, 'Unauthorized access.');
        }
        return Employee::where('company_id', $user->company_id)->firstOrFail();
    }

    public function index()
    {
        $me = $this->getMyRecord();
        
        $myStaffIds = Employee::where('department', $me->department)->pluck('id');
        $employees = Employee::where('department', $me->department)->orderBy('name')->get();

        // 🌟 FIX: Use whereIn to catch both 'pending' and 'pending_dept_head' statuses
        $pendingLeaves = Leave::whereIn('employee_id', $myStaffIds)
            ->whereIn('status', ['pending', 'pending_dept_head'])
            ->with('employee')
            ->get();

        // 🌟 FIX: Applied to Travel Orders
        $pendingTravels = TravelOrder::whereIn('employee_id', $myStaffIds)
            ->whereIn('status', ['pending', 'pending_dept_head'])
            ->with('employee')
            ->get();

        // 🌟 FIX: Applied to Pass Slips
        $pendingPassSlips = \App\Models\PassSlip::whereIn('employee_id', $myStaffIds)
            ->whereIn('status', ['pending', 'pending_dept_head'])
            ->with('employee')
            ->get();

        return view('department_head.dashboard', compact('me', 'employees', 'pendingLeaves', 'pendingTravels', 'pendingPassSlips'));
    }
    
    public function approvePassSlip($id)
    {
        $passSlip = \App\Models\PassSlip::findOrFail($id);
        
        // 🌟 Since Dept Head is the final step, set status strictly to 'approved'
        $passSlip->update([
            'status' => 'approved', 
            'dept_head_approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Pass Slip fully approved!');
    }

    public function declinePassSlip(Request $request, $id)
    {
        $me = $this->getMyRecord();
        $passSlip = \App\Models\PassSlip::findOrFail($id);

        if ($passSlip->employee->department !== $me->department) {
            abort(403, 'You cannot decline pass slips outside your department.');
        }

        $passSlip->update([
            'status' => 'declined',
            'dept_head_id' => $me->id,
            'dept_head_approved_at' => now(), 
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Pass Slip request has been declined.');
    }

    public function approveLeave($id)
    {
        $me = $this->getMyRecord();
        $leave = Leave::findOrFail($id);

        // Security: Ensure the leave belongs to their department
        $leaveOwner = Employee::findOrFail($leave->employee_id);
        if ($leaveOwner->department !== $me->department) {
            abort(403, 'You cannot approve leaves outside your department.');
        }

        // Push to the next tier (HRMO)
        $leave->update([
            'status' => 'pending_hrmo',
            'dept_head_id' => $me->id,
            'dept_head_approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Leave approved and forwarded to HRMO.');
    }

    public function approveTravelOrder($id)
    {
        $me = $this->getMyRecord();
        $travel = TravelOrder::findOrFail($id);

        $travelOwner = Employee::findOrFail($travel->employee_id);
        if ($travelOwner->department !== $me->department) {
            abort(403, 'You cannot approve travel orders outside your department.');
        }

        $travel->update([
            'status' => 'pending_hrmo',
            'dept_head_id' => $me->id,
            'dept_head_approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Travel Order approved and forwarded to HRMO.');
    }
    
    public function staff()
    {
        $me = $this->getMyRecord();
        $employees = Employee::where('department', $me->department)->orderBy('name')->get();

        return view('department_head.staff', compact('me', 'employees'));
    }
    
    public function declineLeave(Request $request, $id)
    {
        $me = $this->getMyRecord();
        $leave = Leave::findOrFail($id);

        if ($leave->employee->department !== $me->department) {
            abort(403, 'You cannot decline leaves outside your department.');
        }

        $leave->update([
            'status' => 'declined',
            'dept_head_id' => $me->id,
            'dept_head_approved_at' => now(), // Records when the action was taken
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Leave request has been declined.');
    }

    public function declineTravelOrder(Request $request, $id)
    {
        $me = $this->getMyRecord();
        $travel = TravelOrder::findOrFail($id);

        if ($travel->employee->department !== $me->department) {
            abort(403, 'You cannot decline travel orders outside your department.');
        }

        $travel->update([
            'status' => 'declined',
            'dept_head_id' => $me->id,
            'dept_head_approved_at' => now(), 
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Travel Order has been declined.');
    }
}