<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\TestSession;
use App\Models\Participant;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function index()
    {
        // Load participants for the dropdown filter
        $participants = Participant::orderBy('name')->get();
        return view('export.index', compact('participants'));
    }

    public function downloadCsv(Request $request)
    {
        // Start the query
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

        // Execute the query
        $sessions = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=research_data_export_" . date('Y_m_d_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // ADDED 'Video_Link' AT THE END OF THE COLUMNS
        $columns = [
            'Session_ID', 'Participant_Name', 'Participant_DOB', 'Participant_Age', 'Participant_Gender', 'Participant_Date_Joined',
            'Test_Name', 'Session_Date',
            'Accuracy_Rate', 'Total_Attempts', 'Correct_Answers', 'Total_Errors', 'Average_Reaction_Time',
            
            // Pre-Test
            'Pre_Distress_01', 'Pre_Distress_02', 'Pre_Distress_03', 'Pre_Distress_04', 'Pre_Distress_05',
            'Pre_Eustress_01', 'Pre_Eustress_02', 'Pre_Eustress_03', 'Pre_Eustress_04',
            
            // Post-Test
            'Post_Distress_01', 'Post_Distress_02', 'Post_Distress_03', 'Post_Distress_04', 'Post_Distress_05',
            'Post_Eustress_01', 'Post_Eustress_02', 'Post_Eustress_03', 'Post_Eustress_04',
            
            // Video Link
            'Screen_Video_Link'
        ];

        $callback = function() use($sessions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sessions as $session) {
                $participant = $session->participant;
                $test = $session->test;
                $result = $session->result; // This is where the videos live!
                
                $preTest = $session->assessments->firstWhere('type', 'pre');
                $postTest = $session->assessments->firstWhere('type', 'post');

                // Get the videos from the RESULT table instead of the session table
                $faceVideo = ($result && $result->face_video_path) ? url('storage/' . $result->face_video_path) : 'N/A';
                $screenVideo = ($result && $result->screen_video_path) ? url('storage/' . $result->screen_video_path) : 'N/A';

                $row = [
                    $session->id,
                    $participant ? $participant->name : 'N/A',
                    $participant ? $participant->dob : 'N/A',
                    $participant ? $participant->age : 'N/A',
                    $participant ? $participant->gender : 'N/A',
                    $participant ? $participant->date_joined : 'N/A',
                    
                    $test ? $test->test_type : 'N/A', // Make sure this matches your DB column for test name
                    $session->created_at ? $session->created_at->format('Y-m-d H:i:s') : 'N/A',
                    
                    $result ? $result->accuracy_rate : 'N/A',
                    $result ? $result->total_attempts : 'N/A',
                    $result ? $result->correct_answers : 'N/A',
                    $result ? $result->total_error : 'N/A',
                    $result ? $result->average_reaction_time : 'N/A',

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
}