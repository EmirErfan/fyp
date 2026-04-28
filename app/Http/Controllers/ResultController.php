<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSession;
use App\Models\Result;

class ResultController extends Controller
{
    // Shows the Results Form
    public function create($id)
    {
        $testSession = TestSession::with(['participant', 'test'])->findOrFail($id);

        return view('results.create', compact('testSession'));
    }

    // Saves the Results
    public function store(Request $request, $id)
    {
        // 1. Validate the data
        $request->validate([
            'accuracy_rate' => 'required|numeric|min:0|max:100', // e.g., 95.5
            'average_reaction_time' => 'required|numeric|min:0',  // e.g., 1450 ms
            'total_error' => 'required|integer|min:0',
            // Videos are nullable, so we don't require them to be filled immediately
            'face_video_path' => 'nullable|string',
            'screen_video_path' => 'nullable|string',
        ]);

        // 2. Save the result to the database!
        Result::create([
            'test_session_id' => $id,
            'accuracy_rate' => $request->accuracy_rate,
            'average_reaction_time' => $request->average_reaction_time,
            'total_error' => $request->total_error,
            'face_video_path' => $request->face_video_path,
            'screen_video_path' => $request->screen_video_path,
        ]);

        // 3. Send the researcher back to the dashboard
        return redirect('/test-sessions')->with('success', 'Test Results saved successfully! Proceed to Post-Test.');
    }
}