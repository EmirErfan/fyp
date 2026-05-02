<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSchedule; 

class TestScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schedules = \App\Models\TestSchedule::when($search, function ($query, $search) {
            $query->where('date', 'like', "%{$search}%")
                ->orWhere('time', 'like', "%{$search}%");
        })
        ->orderBy('date', 'desc')
        ->get();

        return view('schedules.index', compact('schedules'));
    }

    // This simply shows the blank form page
    public function create()
    {
        return view('schedules.create');
    }

    // This handles the data when the form is submitted
    public function store(Request $request)
    {
        // 1. Validate the data (make sure the user didn't leave it blank)
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
        ]);

        // 2. Save it to the database using our Model!
        TestSchedule::create([
            'date' => $request->date,
            'time' => $request->time,
        ]);

        // 3. Redirect back to the list page with a success message
        return redirect('/schedules')->with('success', 'Schedule created successfully!');
    }

    // Shows a specific schedule and all participants assigned to it
    public function show($id)
    {
        // Notice we grab the nested relationships: testSessions -> participant, test, result
        $schedule = \App\Models\TestSchedule::with(['testSessions.participant', 'testSessions.test', 'testSessions.result'])->findOrFail($id);

        return view('schedules.show', compact('schedule'));
    }
}