<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        // Fetch all holidays ordered by the most recent date first
        $holidays = Holiday::orderBy('date', 'desc')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:regular,special,suspension',
            'time_suspended' => 'nullable|date_format:H:i'
        ]);

        Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            // Only save the time if it's an actual suspension
            'time_suspended' => $request->type === 'suspension' ? $request->time_suspended : null,
        ]);

        return redirect()->back()->with('success', 'Holiday/Suspension successfully added!');
    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->back()->with('success', 'Record successfully removed.');
    }
}
