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

        if (auth()->user()->role === 'researcher') {
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
        // 1. Validate the form
        $request->validate([
            'test_schedule_id' => 'required|exists:test_schedules,id',
            'participant_id' => 'required|exists:participants,id',
            'test_id' => 'required|exists:tests,id',
        ]);

        $schedule = \App\Models\TestSchedule::findOrFail($request->test_schedule_id);
        $participant = \App\Models\Participant::findOrFail($request->participant_id);

        if (auth()->user()->role === 'researcher') {
            if ($schedule->user_id !== auth()->id() || $participant->user_id !== auth()->id()) {
                abort(403);
            }
        }

        // 3. Create the massive link in the pivot table!
        TestSession::create([
            'user_id' => auth()->id(),
            'test_schedule_id' => $request->test_schedule_id,
            'participant_id' => $request->participant_id,
            'test_id' => $request->test_id,
        ]);

        // 4. Send them back to the SPECIFIC schedule page instead of the main list!
        return redirect('/schedules/' . $request->test_schedule_id)->with('success', 'Participant assigned to session successfully!');
    }

    public function storeRecordings(Request $request, $id)
    {
        try {
            $session = TestSession::findOrFail($id);
            if (auth()->user()->role === 'researcher' && $session->user_id !== auth()->id()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }

            // 1. Check for the video
            if (!$request->hasFile('face_video')) {
                return response()->json(['status' => 'error', 'message' => 'Video missing.'], 400);
            }

            $destinationPath = storage_path('app/public/recordings');
            
            // 2. Process and save the single file
            $filename = "session_{$id}_combined.webm";
            $request->file('face_video')->move($destinationPath, $filename);
            $videoPath = 'recordings/' . $filename; 

            // 3. AUTO-SAVE THE RESULTS TO THE DATABASE!
            \App\Models\Result::updateOrCreate(
                ['test_session_id' => $id],
                [
                    'accuracy_rate' => $request->input('accuracy_rate') ?? 0,
                    'total_error' => $request->input('total_error') ?? $request->input('total_errors') ?? 0,
                    'total_attempts' => $request->input('total_attempts'),
                    'correct_answers' => $request->input('correct_answers'),
                    'average_reaction_time' => $request->input('average_reaction_time') ?? 0,
                    
                    // Changed to match your database schema!
                    'face_video_path' => $videoPath, 
                    'screen_video_path' => $videoPath,
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Recordings and Results Auto-Saved!']);

        } catch (\Exception $e) {
            // THIS IS THE MAGIC TRICK:
            // If the database crashes, this catches it and sends the EXACT error message to your screen!
            return response()->json([
                'status' => 'error', 
                'message' => 'DATABASE CRASH: ' . $e->getMessage()
            ], 500);
        }
    }
}