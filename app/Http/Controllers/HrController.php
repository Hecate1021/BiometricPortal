<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\TravelOrder;
use App\Models\PassSlip;
use App\Models\Employee;

class HrController extends Controller
{
    public function dashboard()
    {
        // 1. Fetch ALL leaves (newest first)
        $allLeaves = Leave::with('employee')->latest()->get();
        
        // 2. Fetch ALL travel orders (newest first)
        $allTravels = TravelOrder::with('employee')->latest()->get();
        
        // 3. Fetch ALL pass slips (newest first)
        $allPassSlips = PassSlip::with('employee')->latest()->get();
        
        // 4. Fetch active employees for the proxy filing dropdown
        $activeEmployees = Employee::where(function($query) {
            $query->where('is_hidden', false)->orWhereNull('is_hidden');
        })->orderBy('name')->get();

        // Pass everything to the view
        return view('hr.dashboard', compact('allLeaves', 'allTravels', 'allPassSlips', 'activeEmployees'));
    }
}