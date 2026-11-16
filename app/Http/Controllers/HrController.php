<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\TravelOrder;
use App\Models\PassSlip;
use App\Models\Employee;

class HrController extends Controller
{
    // 🌟 Inject Request here to catch the search input
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        // 1. Fetch active employees for the proxy filing dropdown (No pagination needed here)
        $activeEmployees = Employee::where(function($query) {
            $query->where('is_hidden', false)->orWhereNull('is_hidden');
        })->orderBy('name')->get();

        // 2. TRAVEL ORDERS QUERY with Search & Pagination
        $travelQuery = TravelOrder::with('employee')->latest();
        if ($search) {
            $travelQuery->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('form_number', 'like', '%' . $search . '%')
              ->orWhere('destination', 'like', '%' . $search . '%'); // Optional: allows searching by destination
        }
        $allTravels = $travelQuery->paginate(10, ['*'], 'travel_page');

        // 3. PASS SLIPS QUERY with Search & Pagination
        $passQuery = PassSlip::with('employee')->latest();
        if ($search) {
            $passQuery->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('transaction_details', 'like', '%' . $search . '%'); // Optional: allows searching by details
        }
        $allPassSlips = $passQuery->paginate(10, ['*'], 'pass_page');

        // 4. LEAVES QUERY with Search & Pagination
        $leaveQuery = Leave::with('employee')->latest();
        if ($search) {
            $leaveQuery->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('type', 'like', '%' . $search . '%');
        }
        $allLeaves = $leaveQuery->paginate(10, ['*'], 'leave_page');

        // Pass everything to the view
        return view('hr.dashboard', compact('allLeaves', 'allTravels', 'allPassSlips', 'activeEmployees'));
    }
}
