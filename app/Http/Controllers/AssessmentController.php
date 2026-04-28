<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSession;
use App\Models\Assessment;

class AssessmentController extends Controller
{
    // Shows the Pre-Test Form
    public function createPreTest($id)
    {
        // Find the specific session we clicked on
        $testSession = TestSession::with('participant')->findOrFail($id);

        return view('assessments.pre_test', compact('testSession'));
    }

    // Saves the Pre-Test Answers & Redirects to the Launch Pad!
    public function storePreTest(Request $request, $id)
    {
        $request->validate([
            'distress_item_01' => 'required|integer|min:1|max:5',
            'distress_item_02' => 'required|integer|min:1|max:5',
            'distress_item_03' => 'required|integer|min:1|max:5',
            'distress_item_04' => 'required|integer|min:1|max:5',
            'distress_item_05' => 'required|integer|min:1|max:5',
            'eustress_item_01' => 'required|integer|min:1|max:5',
            'eustress_item_02' => 'required|integer|min:1|max:5',
            'eustress_item_03' => 'required|integer|min:1|max:5',
            'eustress_item_04' => 'required|integer|min:1|max:5',
        ]);

        Assessment::create([
            'test_session_id' => $id,
            'type' => 'pre',
            'distress_item_01' => $request->distress_item_01,
            'distress_item_02' => $request->distress_item_02,
            'distress_item_03' => $request->distress_item_03,
            'distress_item_04' => $request->distress_item_04,
            'distress_item_05' => $request->distress_item_05,
            'eustress_item_01' => $request->eustress_item_01,
            'eustress_item_02' => $request->eustress_item_02,
            'eustress_item_03' => $request->eustress_item_03,
            'eustress_item_04' => $request->eustress_item_04,
        ]);

        // FIXED: Send them straight to the Launch Pad so they can open the dedicated window!
        return redirect("/test-sessions/{$id}/launch");
    }

    // Shows the Informed Consent page
    public function showConsent($id)
    {
        $session = \App\Models\TestSession::with(['participant', 'test'])->findOrFail($id);
        
        return view('assessments.consent', compact('session'));
    }

    // Shows the Post-Test Form
    public function createPostTest($id)
    {
        $session = TestSession::with('participant')->findOrFail($id);

        return view('assessments.post-test', compact('session'));
    }

    // Saves the Post-Test Answers
    public function storePostTest(Request $request, $id)
    {
        // 1. Validate the 9 questions
        $request->validate([
            'distress_item_01' => 'required|integer|min:1|max:5',
            'distress_item_02' => 'required|integer|min:1|max:5',
            'distress_item_03' => 'required|integer|min:1|max:5',
            'distress_item_04' => 'required|integer|min:1|max:5',
            'distress_item_05' => 'required|integer|min:1|max:5',
            'eustress_item_01' => 'required|integer|min:1|max:5',
            'eustress_item_02' => 'required|integer|min:1|max:5',
            'eustress_item_03' => 'required|integer|min:1|max:5',
            'eustress_item_04' => 'required|integer|min:1|max:5',
        ]);

        // 2. Save it to the database, ensuring we label it as type 'post'!
        Assessment::create([
            'test_session_id' => $id,
            'type' => 'post', 
            'distress_item_01' => $request->distress_item_01,
            'distress_item_02' => $request->distress_item_02,
            'distress_item_03' => $request->distress_item_03,
            'distress_item_04' => $request->distress_item_04,
            'distress_item_05' => $request->distress_item_05,
            'eustress_item_01' => $request->eustress_item_01,
            'eustress_item_02' => $request->eustress_item_02,
            'eustress_item_03' => $request->eustress_item_03,
            'eustress_item_04' => $request->eustress_item_04,
        ]);

        // 3. Mark the session as Completed!
        $session = TestSession::findOrFail($id);
        $session->update(['status' => 'Completed']);

        // 4. Send them to the Final Result Summary screen!
        return redirect("/test-sessions/{$id}/result-summary");
    }

    // Shows the Launch Pad for the dedicated test window
    public function launchTest($id)
    {
        $session = TestSession::with(['participant', 'test'])->findOrFail($id);
        
        // Figure out which test URL to give the button
        $testUrl = "/test-sessions/{$id}/tsst"; // Default
        if ($session->test->test_type == 'Stroop Test') {
            $testUrl = "/test-sessions/{$id}/stroop";
        } elseif ($session->test->test_type == 'MIST') {
            $testUrl = "/test-sessions/{$id}/mist";
        }

        return view('assessments.launch', compact('session', 'testUrl'));
    }

    // Shows the Final Results to the Participant
    public function showResultSummary($id)
    {
        // Load the session, participant, the math results, and the assessments
        $session = TestSession::with(['participant', 'result', 'assessments'])->findOrFail($id);

        // Separate the pre-test and post-test data
        $preTest = $session->assessments->where('type', 'pre')->first();
        $postTest = $session->assessments->where('type', 'post')->first();

        return view('assessments.result-summary', compact('session', 'preTest', 'postTest'));
    }
}