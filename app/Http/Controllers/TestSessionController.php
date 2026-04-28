<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSession;
use App\Models\TestSchedule;
use App\Models\Participant;
use App\Models\Test;
use App\Models\User; // We need this for the researcher!

class TestSessionController extends Controller
{

    // Shows the list of all assigned sessions (The Dashboard)
    public function index()
    {
        // We use whereHas to check the date inside the connected TestSchedule table!
        $testSessions = TestSession::whereHas('testSchedule', function ($query) {
            $query->whereDate('date', '>=', now()->toDateString());
        })
        ->get()
        ->sortBy(function ($session) {
            return $session->testSchedule->date; // Sorts them by the schedule date
        });

        return view('test_sessions.index', compact('testSessions'));
    }

    // Shows the final combined report
    public function show($id)
    {
        // Notice how many tables we are pulling from at once!
        $session = TestSession::with(['participant', 'testSchedule', 'test', 'result', 'assessments'])->findOrFail($id);

        // We split the assessments into Pre and Post so it's easy to display
        $preTest = $session->assessments->where('type', 'pre')->first();
        $postTest = $session->assessments->where('type', 'post')->first();

        return view('test_sessions.show', compact('session', 'preTest', 'postTest'));
    }

    public function create()
    {
        // 1. Renamed to $schedules to match what your HTML expects!
        $schedules = \App\Models\TestSchedule::whereDate('date', '>=', now()->toDateString())
                                             ->orderBy('date', 'asc')
                                             ->get();

        // 2. Get all participants
        $participants = \App\Models\Participant::all();
        
        // 3. Get all tests
        $tests = \App\Models\Test::all(); 

        // 4. Update the compact list to use 'schedules'
        return view('test_sessions.create', compact('schedules', 'participants', 'tests'));
    }

    // Saves the assignment
    public function store(Request $request)
    {
        // 1. Validate the form
        $request->validate([
            'test_schedule_id' => 'required|exists:test_schedules,id',
            'participant_id' => 'required|exists:participants,id',
            'test_id' => 'required|exists:tests,id',
        ]);

        // 2. CLEVER TRICK: Since we don't have a login system yet, we need a "Researcher".
        // This checks if a user exists. If not, it creates a dummy researcher for us!
        $researcher = User::first();
        if (!$researcher) {
            $researcher = User::create([
                'name' => 'Dr. Smith (Test Researcher)',
                'email' => 'researcher@test.com',
                'password' => bcrypt('password'),
                'role' => 'Researcher'
            ]);
        }

        // 3. Create the massive link in the pivot table!
        TestSession::create([
            'user_id' => $researcher->id,
            'test_schedule_id' => $request->test_schedule_id,
            'participant_id' => $request->participant_id,
            'test_id' => $request->test_id,
        ]);

        // 4. Send them back to the schedules page
        return redirect('/schedules')->with('success', 'Participant assigned to session successfully!');
    }

    public function storeRecordings(Request $request, $id)
    {
        // 1. Strict Check
        if (!$request->hasFile('face_video') && !$request->hasFile('screen_video')) {
            return response()->json(['status' => 'error', 'message' => 'Files missing.'], 400);
        }

        $destinationPath = storage_path('app/public/recordings');
        $facePath = null;
        $screenPath = null;

        // 2. Force move the videos & save their paths
        if ($request->hasFile('face_video')) {
            $filename = "session_{$id}_face.webm";
            $request->file('face_video')->move($destinationPath, $filename);
            $facePath = 'storage/recordings/' . $filename;
        }
        
        if ($request->hasFile('screen_video')) {
            $filename = "session_{$id}_screen.webm";
            $request->file('screen_video')->move($destinationPath, $filename);
            $screenPath = 'storage/recordings/' . $filename;
        }

        // 3. AUTO-SAVE THE RESULTS TO THE DATABASE!
        // This completely eliminates the need for the manual entry form.
        \App\Models\Result::updateOrCreate(
            ['test_session_id' => $id],
            [
                'accuracy_rate' => $request->input('accuracy_rate', 0),
                'total_error' => $request->input('total_errors', 0),
                'average_reaction_time' => $request->input('average_reaction_time', 0),
                'face_recording_link' => $facePath, // Saves the local file path!
                'screen_recording_link' => $screenPath,
            ]
        );

        return response()->json(['status' => 'success', 'message' => 'Recordings and Results Auto-Saved!']);
    }
}