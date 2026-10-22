<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder;

class TravelOrderController extends Controller
{
    
    public function edit($id)
    {
        $travel = \App\Models\TravelOrder::findOrFail($id);
        return view('travel_orders.edit', compact('travel'));
    }
    
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'departure_from' => 'required|string',
            'destination'    => 'required|string',
            'purpose'        => 'required|string',
            'expense_type'   => 'required|in:Official,Personal',
            
            // 1. Strict Date Check: Do not allow past months (UNLESS HR IS ENCODING IT)
            'start_date'     => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $travelMonth = \Carbon\Carbon::parse($value)->format('Y-m');
                    $currentMonth = now()->format('Y-m');
                    
                    $userRole = strtolower(trim(auth()->user()->role ?? ''));
                    $isHrProxy = in_array($userRole, ['hr', 'hrmo', 'admin']);
                    
                    if ($travelMonth < $currentMonth && !$isHrProxy) {
                        $fail('Error: You cannot encode a Travel Order for a previous month.');
                    }
                }
            ],
            'end_date'       => 'required|date|after_or_equal:start_date',
            
            // 2. 🌟 HR ONLY: OPENED TO NUMBER VALIDATION (+/- 1 MONTH) 🌟
            'form_number'    => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        
                        // Check if user is actually HR
                        $userRole = strtolower(trim(auth()->user()->role ?? ''));
                        $isHrProxy = in_array($userRole, ['hr', 'hrmo', 'admin']);
                        
                        // Block regular employees immediately
                        if (!$isHrProxy) {
                            $fail('Error: Only HR administrators can manually encode a Travel Order Number.');
                            return; 
                        }

                        // Validate Format allowing 1 month backward and 1 month forward
                        if ($request->start_date) {
                            $date = \Carbon\Carbon::parse($request->start_date);
                            
                            $target = $date->format('Y-m-'); // e.g., 2026-10-
                            $prev   = $date->copy()->subMonth()->format('Y-m-'); // e.g., 2026-09-
                            $next   = $date->copy()->addMonth()->format('Y-m-'); // e.g., 2026-11-
                            
                            // Array of all valid prefixes (with and without 'TO-')
                            $validPrefixes = [
                                $target, "TO-{$target}",
                                $prev,   "TO-{$prev}",
                                $next,   "TO-{$next}"
                            ];
                            
                            $isValid = false;
                            foreach ($validPrefixes as $prefix) {
                                if (str_starts_with($value, $prefix)) {
                                    $isValid = true;
                                    break;
                                }
                            }

                            if (!$isValid) {
                                $fail("Error: The TO Number prefix must be within 1 month of the travel date (e.g., {$prev}####, {$target}####, or {$next}####).");
                            }

                            // Check Uniqueness so HR doesn't accidentally double-encode a physical paper
                            if (\App\Models\TravelOrder::where('form_number', $value)->exists()) {
                                $fail("Error: The Form No. {$value} has already been encoded in the system.");
                            }
                        }
                    }
                }
            ],
        ]);

        // ==========================================
        // AUTO-GENERATE FORM NUMBER (IF LEFT BLANK)
        // ==========================================
        if (empty($validated['form_number'])) {
            $date = \Carbon\Carbon::parse($request->start_date);
            
            // Uses the YYYY-MM- format as requested
            $prefix = $date->format('Y-m') . '-';
            
            // Loop to ensure the generated 4-digit number is completely unique
            do {
                $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $formNumber = $prefix . $randomNumber;
            } while (\App\Models\TravelOrder::where('form_number', $formNumber)->exists());

            $validated['form_number'] = $formNumber;
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
            $validated['status'] = 'pending';
        }

        TravelOrder::create($validated);

        return redirect()->back()->with('success', 'Travel Order saved successfully! (Form No: ' . $validated['form_number'] . ')');
    }
    
    public function print($id)
    {
        $travelOrder = \App\Models\TravelOrder::with('employee')->findOrFail($id);
        
        // 1. Check if the employee belongs to HRMO
        $department = strtolower($travelOrder->employee->department ?? '');
        $isHrmo = str_contains($department, 'hrmo') || str_contains($department, 'human resource');

        $deptHeadName = '---';

        // 2. Fetch the Department Head's name (Only if NOT in HRMO)
        if (!$isHrmo) {
            
            if (!empty($travelOrder->dept_head_id)) {
                // SCENARIO A: Form is ALREADY APPROVED. 
                // We now know dept_head_id is an EMPLOYEE ID, so we search the Employee model!
                $employee = \App\Models\Employee::find($travelOrder->dept_head_id);
                if ($employee) {
                    $deptHeadName = $employee->name;
                }
            } else {
                // SCENARIO B: Form is PENDING. 
                // Leave it completely blank so they can physically sign it with a pen.
                $deptHeadName = ''; 
            }
        }
        
        return view('travel_orders.print', compact('travelOrder', 'deptHeadName', 'isHrmo'));
    }
    
    // ==========================================
    // HRMO APPROVAL TO MAYOR
    // ==========================================
    public function approve(Request $request, $id)
    {
        $travel = TravelOrder::findOrFail($id);
        
        $travel->update([
            'status' => 'pending_mayor', // Push to the final tier
            'hrmo_id' => auth()->id(),
            'hrmo_approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Travel Order verified and forwarded to the Mayor.');
    }

    // ==========================================
    // DECLINE TRAVEL ORDER (With Remarks)
    // ==========================================
    public function decline(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:500'
        ]);

        $travel = TravelOrder::findOrFail($id);
        
        $travel->update([
            'status' => 'declined',
            'hrmo_id' => auth()->id(),
            'hrmo_approved_at' => now(),
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Travel Order has been declined.');
    }

    // ==========================================
    // MAYOR'S FINAL APPROVAL TIER
    // ==========================================
    public function approveMayor(Request $request, $id)
    {
        $travel = TravelOrder::findOrFail($id);
        
        $travel->update([
            'status' => 'approved', // 🌟 Final Status!
            'mayor_id' => auth()->id(),
            'mayor_approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Travel Order fully approved!');
    }

    public function declineMayor(Request $request, $id)
    {
        $request->validate(['remarks' => 'required|string|max:500']);
        
        $travel = TravelOrder::findOrFail($id);
        $travel->update([
            'status' => 'declined',
            'mayor_id' => auth()->id(),
            'mayor_approved_at' => now(),
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Travel Order declined at the final tier.');
    }
    
    public function update(Request $request, $id)
    {
        $travel = \App\Models\TravelOrder::findOrFail($id);
        
        $validated = $request->validate([
            'form_number'    => 'nullable|string',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'departure_from' => 'required|string',
            'destination'    => 'required|string',
            'purpose'        => 'required|string',
            'expense_type'   => 'required|in:Official,Personal',
        ]);

        $travel->update($validated);

        return redirect()->route('dashboard')->with('success', 'Travel Order updated successfully!');
    }
}