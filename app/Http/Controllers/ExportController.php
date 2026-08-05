<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\TestSession;
use App\Models\Participant;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function index()
    {
        // Load participants for the dropdown filter
        $participants = Participant::orderBy('name')->get();
        return view('export.index', compact('participants'));
    }

    private function getFilteredSessions(Request $request)
    {
        $query = TestSession::with(['participant', 'test', 'result', 'assessments']);

        // --- SECURITY (RBAC) ---
        // If the user is a researcher, ONLY grab sessions they conducted
        if (strtolower(Auth::user()->role) === 'researcher') {
            $query->where('user_id', Auth::id());
        }

        // --- APPLY FILTERS ---
        if ($request->filled('participant_id') && $request->participant_id !== 'all') {
            $query->where('participant_id', $request->participant_id);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return $query->get();
    }

    public function downloadCsv(Request $request)
    {
        $sessions = $this->getFilteredSessions($request);

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=research_data_export_" . date('Y_m_d_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Redesigned headers for better readability
        $columns = [
            'Session ID', 'Participant Name', 'Date of Birth', 'Age (Years)', 'Gender', 'Date Joined',
            'Test Type', 'Session Date & Time',
            'Accuracy Rate (%)', 'Total Attempts', 'Correct Answers', 'Total Errors', 'Avg Reaction Time (ms)',
            
            // Pre-Test
            'Pre-Test Distress 1', 'Pre-Test Distress 2', 'Pre-Test Distress 3', 'Pre-Test Distress 4', 'Pre-Test Distress 5',
            'Pre-Test Eustress 1', 'Pre-Test Eustress 2', 'Pre-Test Eustress 3', 'Pre-Test Eustress 4',
            
            // Post-Test
            'Post-Test Distress 1', 'Post-Test Distress 2', 'Post-Test Distress 3', 'Post-Test Distress 4', 'Post-Test Distress 5',
            'Post-Test Eustress 1', 'Post-Test Eustress 2', 'Post-Test Eustress 3', 'Post-Test Eustress 4',
            
            // Video Link
            'Screen Recording Video Link'
        ];

        $callback = function() use($sessions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sessions as $session) {
                $participant = $session->participant;
                $test = $session->test;
                $result = $session->result; 
                
                $preTest = $session->assessments->firstWhere('type', 'pre');
                $postTest = $session->assessments->firstWhere('type', 'post');

                $screenVideo = ($result && $result->screen_video_path) ? url('storage/' . $result->screen_video_path) : 'N/A';

                $row = [
                    $session->id,
                    $participant ? $participant->name : 'N/A',
                    $participant ? Carbon::parse($participant->dob)->format('M d, Y') : 'N/A',
                    $participant ? $participant->age : 'N/A',
                    $participant ? ucfirst($participant->gender) : 'N/A',
                    $participant ? Carbon::parse($participant->date_joined)->format('M d, Y') : 'N/A',
                    
                    $test ? $test->test_type : 'N/A', 
                    $session->created_at ? $session->created_at->format('M d, Y h:i A') : 'N/A',
                    
                    $result && $result->accuracy_rate !== null ? round($result->accuracy_rate, 2) . '%' : 'N/A',
                    $result ? $result->total_attempts : 'N/A',
                    $result ? $result->correct_answers : 'N/A',
                    $result ? $result->total_error : 'N/A',
                    $result && $result->average_reaction_time !== null ? round($result->average_reaction_time, 2) : 'N/A',

                    // Pre-test
                    $preTest ? $preTest->distress_item_01 : 'N/A',
                    $preTest ? $preTest->distress_item_02 : 'N/A',
                    $preTest ? $preTest->distress_item_03 : 'N/A',
                    $preTest ? $preTest->distress_item_04 : 'N/A',
                    $preTest ? $preTest->distress_item_05 : 'N/A',
                    $preTest ? $preTest->eustress_item_01 : 'N/A',
                    $preTest ? $preTest->eustress_item_02 : 'N/A',
                    $preTest ? $preTest->eustress_item_03 : 'N/A',
                    $preTest ? $preTest->eustress_item_04 : 'N/A',

                    // Post-test
                    $postTest ? $postTest->distress_item_01 : 'N/A',
                    $postTest ? $postTest->distress_item_02 : 'N/A',
                    $postTest ? $postTest->distress_item_03 : 'N/A',
                    $postTest ? $postTest->distress_item_04 : 'N/A',
                    $postTest ? $postTest->distress_item_05 : 'N/A',
                    $postTest ? $postTest->eustress_item_01 : 'N/A',
                    $postTest ? $postTest->eustress_item_02 : 'N/A',
                    $postTest ? $postTest->eustress_item_03 : 'N/A',
                    $postTest ? $postTest->eustress_item_04 : 'N/A',
                    
                    $screenVideo
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadPdf(Request $request)
    {
        $sessions = $this->getFilteredSessions($request);
        
        $pdf = Pdf::loadView('export.pdf', compact('sessions'));
        
        return $pdf->download("research_data_export_" . date('Y_m_d_His') . ".pdf");
    }
}