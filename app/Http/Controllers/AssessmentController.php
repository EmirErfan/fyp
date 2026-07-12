<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSession;
use App\Models\Assessment;

class AssessmentController extends Controller
{
    /**
     * SECURITY HELPER
     * Allows access if the user is a logged-in researcher OR a guest who entered the correct PIN.
     */
    private function authorizeSessionAccess($id)
    {
        if (!auth()->check() && session('active_guest_session') != $id) {
            abort(403, 'Unauthorized access. Please join using a valid test PIN on the homepage.');
        }
    }

    // Shows the Informed Consent page
    public function showConsent($id)
    {
        $this->authorizeSessionAccess($id);

        $session = \App\Models\TestSession::with(['participant', 'test'])->findOrFail($id);
        
        return view('assessments.consent', compact('session'));
    }

    // Shows the Step-by-Step Tutorial
    public function showTutorial($id)
    {
        $this->authorizeSessionAccess($id);

        $session = \App\Models\TestSession::with('test')->findOrFail($id);
        
        return view('assessments.tutorial', compact('session'));
    }

    // Shows the Camera Face Detection Check
    public function showCameraCheck($id)
    {
        $this->authorizeSessionAccess($id);

        $session = \App\Models\TestSession::with('participant')->findOrFail($id);
        
        return view('assessments.camera_check', compact('session'));
    }

    // Shows the Pre-Test Form
    public function createPreTest($id)
    {
        $this->authorizeSessionAccess($id);

        $testSession = TestSession::with('participant')->findOrFail($id);

        return view('assessments.pre_test', compact('testSession'));
    }

    // Saves the Pre-Test Answers & Redirects to the Launch Pad!
    public function storePreTest(Request $request, $id)
    {
        $this->authorizeSessionAccess($id);

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

        return redirect("/test-sessions/{$id}/launch");
    }

    // Shows the Launch Pad for the dedicated test window
    public function launchTest($id)
    {
        $this->authorizeSessionAccess($id);

        $session = TestSession::with(['participant', 'test'])->findOrFail($id);
        
        $testUrl = "/test-sessions/{$id}/tsst"; 
        if ($session->test->test_type == 'Stroop Test') {
            $testUrl = "/test-sessions/{$id}/stroop";
        } elseif ($session->test->test_type == 'MIST') {
            $testUrl = "/test-sessions/{$id}/mist";
        }

        return view('assessments.launch', compact('session', 'testUrl'));
    }

    // Shows the Post-Test Form
    public function createPostTest($id)
    {
        $this->authorizeSessionAccess($id);

        $session = TestSession::with('participant')->findOrFail($id);

        return view('assessments.post-test', compact('session'));
    }

    // Saves the Post-Test Answers
    public function storePostTest(Request $request, $id)
    {
        $this->authorizeSessionAccess($id);

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

        // $session = TestSession::findOrFail($id);
        // $session->update(['status' => 'Completed']);

        return redirect("/test-sessions/{$id}/result-summary");
    }

    // Shows the Final Results to the Participant
    public function showResultSummary($id)
    {
        $this->authorizeSessionAccess($id);

        $session = TestSession::with(['participant', 'result', 'assessments'])->findOrFail($id);

        $preTest = $session->assessments->where('type', 'pre')->first();
        $postTest = $session->assessments->where('type', 'post')->first();

        return view('assessments.result-summary', compact('session', 'preTest', 'postTest'));
    }
}