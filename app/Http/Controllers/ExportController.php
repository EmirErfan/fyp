<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\TestSession;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function index()
    {
        return view('export.index');
    }

    public function downloadCsv()
    {
        $sessions = TestSession::with(['participant', 'test', 'result', 'assessments'])->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=research_data_export_" . date('Y_m_d_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Session_ID', 'Participant_Name', 'Participant_DOB', 'Participant_Age', 'Participant_Gender', 'Participant_Date_Joined',
            'Test_Name', 'Session_Date',
            'Accuracy_Rate', 'Total_Attempts', 'Correct_Answers', 'Total_Errors', 'Average_Reaction_Time',
            
            // Pre-Test
            'Pre_Distress_01', 'Pre_Distress_02', 'Pre_Distress_03', 'Pre_Distress_04', 'Pre_Distress_05',
            'Pre_Eustress_01', 'Pre_Eustress_02', 'Pre_Eustress_03', 'Pre_Eustress_04',
            
            // Post-Test
            'Post_Distress_01', 'Post_Distress_02', 'Post_Distress_03', 'Post_Distress_04', 'Post_Distress_05',
            'Post_Eustress_01', 'Post_Eustress_02', 'Post_Eustress_03', 'Post_Eustress_04'
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

                $row = [
                    $session->id,
                    $participant ? $participant->name : 'N/A',
                    $participant ? $participant->dob : 'N/A',
                    $participant ? $participant->age : 'N/A',
                    $participant ? $participant->gender : 'N/A',
                    $participant ? $participant->date_joined : 'N/A',
                    
                    $test ? $test->name : 'N/A',
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
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
