<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSession;
use App\Models\TestSchedule;
use App\Models\Participant;
use App\Models\Test;
use App\Models\User; // We need this for the researcher!
use Illuminate\Support\Facades\Mail;
use App\Mail\TestAccessCodeMail;

class TestSessionController extends Controller
{

    // Shows the list of all assigned sessions (The Dashboard)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        // UPGRADED SMART ID SEARCH:
        // Now cleanly matches "#P-005", "P-05", "p001", "P001", or just "005"
        // and extracts only the pure number!
        $searchId = $search;
        if (preg_match('/^#?P?-?0*(\d+)$/i', $search, $matches)) {
            $searchId = $matches[1]; 
        }

        $testSessions = TestSession::whereHas('testSchedule', function ($query) {
            $query->whereDate('date', '>=', now()->toDateString());
        })
        ->when(auth()->user()->role === 'admin', function ($query) {
            $query->whereHas('participant', function ($q) {
                $q->where('team_id', auth()->user()->team_id);
            });
        })
        ->when(auth()->user()->role === 'researcher', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->when($search, function ($query) use ($search, $searchId) {
            $query->whereHas('participant', function ($q) use ($search, $searchId) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$searchId}%");
            })
            ->orWhereHas('test', function ($q) use ($search) {
                $q->where('test_type', 'like', "%{$search}%");
            });
        })
        ->when($status, function ($query, $status) {
            if ($status === 'complete') {
                $query->has('result');
            } elseif ($status === 'in-progress') {
                $query->doesntHave('result');
            }
        })
        ->get()
        ->sortBy(function ($session) {
            return $session->testSchedule->date;
        });

        return view('test_sessions.index', compact('testSessions'));
    }

    // Shows the final combined report
    public function show($id)
    {
        // Notice how many tables we are pulling from at once!
        $session = TestSession::with(['participant', 'testSchedule', 'test', 'result', 'assessments'])->findOrFail($id);

        if (auth()->user()->role === 'admin' && $session->participant->team_id !== auth()->user()->team_id) {
            abort(403);
        }
        if (auth()->user()->role === 'researcher' && $session->user_id !== auth()->id()) {
            abort(403);
        }

        // We split the assessments into Pre and Post so it's easy to display
        $preTest = $session->assessments->where('type', 'pre')->first();
        $postTest = $session->assessments->where('type', 'post')->first();

        return view('test_sessions.show', compact('session', 'preTest', 'postTest'));
    }

    public function create()
    {
        $schedulesQuery = \App\Models\TestSchedule::whereDate('date', '>=', now()->toDateString())
                                                  ->orderBy('date', 'asc');
        $participantsQuery = \App\Models\Participant::query();

        if (auth()->user()->role === 'admin') {
            $participantsQuery->where('team_id', auth()->user()->team_id);
        } elseif (auth()->user()->role === 'researcher') {
            $schedulesQuery->where('user_id', auth()->id());
            $participantsQuery->where('user_id', auth()->id());
        }

        $schedules = $schedulesQuery->get();
        $participants = $participantsQuery->get();
        
        // 3. Get all tests
        $tests = \App\Models\Test::all(); 

        // 4. Update the compact list to use 'schedules'
        return view('test_sessions.create', compact('schedules', 'participants', 'tests'));
    }

    // Saves the assignment
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required',
            'test_id' => 'required',
            'test_schedule_id' => 'required',
        ]);

        // 1. Generate the random 6-character code right before saving
        $accessCode = strtoupper(\Illuminate\Support\Str::random(6));

        // 2. Save it to the database, including the logged-in researcher's ID!
        TestSession::create([
            'participant_id' => $request->participant_id,
            'test_id' => $request->test_id,
            'test_schedule_id' => $request->test_schedule_id,
            'access_code' => $accessCode,
            'user_id' => auth()->id(), // <-- THIS IS THE MISSING PIECE
        ]);

        return redirect('/test-sessions')->with('success', 'Session created successfully!');
    }

    public function storeRecordings(Request $request, $id)
{
    // 1. SECURITY CHECK: Ensure it's the session owner (guest or researcher)
    if (!auth()->check() && session('active_guest_session') != $id) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    }

    // 2. ONLY check the role IF a user is actually logged in
    $userRole = auth()->check() ? auth()->user()->role : 'guest';

    // 3. Now proceed with your file saving logic
    try {
        $destinationPath = storage_path('app/public/recordings');
        $facePath = null;
        $screenPath = null;

        // Force move the videos & save their paths
        if ($request->hasFile('face_video')) {
            $filename = "session_{$id}_face.webm";
            $request->file('face_video')->move($destinationPath, $filename);
            $facePath = 'recordings/' . $filename;
        }
        
        if ($request->hasFile('screen_video')) {
            $filename = "session_{$id}_screen.webm";
            $request->file('screen_video')->move($destinationPath, $filename);
            $screenPath = 'recordings/' . $filename;
        }

        // AUTO-SAVE THE RESULTS TO THE DATABASE!
        \App\Models\Result::updateOrCreate(
            ['test_session_id' => $id],
            [
                'accuracy_rate' => $request->input('accuracy_rate', 0),
                'total_error' => $request->input('total_error') ?? $request->input('total_errors') ?? 0,
                'average_reaction_time' => $request->input('average_reaction_time', 0),
                'face_video_path' => $facePath,
                'screen_video_path' => $screenPath,
            ]
        );
        
        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

    public function sendAccessCodeEmail($id)
    {
        $session = TestSession::with(['participant', 'test', 'testSchedule'])->findOrFail($id);
        
        if (!$session->participant->email) {
            return back()->with('error', 'Participant does not have an email address on file.');
        }

        try {
            Mail::to($session->participant->email)->send(new TestAccessCodeMail($session, $session->participant));
            return back()->with('success', 'Access code email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}