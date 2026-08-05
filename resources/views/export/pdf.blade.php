<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Research Data Export</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1a73e8;
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        .header p {
            color: #666;
            margin: 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            color: #1a73e8;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fbfbfc;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .section-title {
            background: #e8f0fe;
            color: #1a73e8;
            padding: 4px;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <div class="footer">
        Generated on {{ date('M d, Y h:i A') }} • StressLab Research System
    </div>

    <div class="header">
        <div style="margin-bottom: 15px;">
            <span style="font-size: 32px; font-weight: 800; color: #111; letter-spacing: -1px;">Stress<em style="color: #1a73e8; font-style: normal;">Lab</em></span>
        </div>
        <h1>Research Data Export</h1>
        <p>Summary report of selected test sessions</p>
    </div>

    @if($sessions->isEmpty())
        <p style="text-align: center; color: #666; font-style: italic; margin-top: 50px;">No records found for the selected criteria.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Participant</th>
                    <th style="width: 15%;">Test Details</th>
                    <th style="width: 35%;">Performance Metrics</th>
                    <th style="width: 35%;">DESS Assessment (Pre / Post)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                    @php
                        $participant = $session->participant;
                        $test = $session->test;
                        $result = $session->result;
                        
                        $preTest = $session->assessments->firstWhere('type', 'pre');
                        $postTest = $session->assessments->firstWhere('type', 'post');

                        // Calculate totals if assessments exist
                        $preTotal = $preTest ? array_sum([
                            $preTest->distress_item_01, $preTest->distress_item_02, $preTest->distress_item_03, $preTest->distress_item_04, $preTest->distress_item_05,
                            $preTest->eustress_item_01, $preTest->eustress_item_02, $preTest->eustress_item_03, $preTest->eustress_item_04
                        ]) : 'N/A';

                        $postTotal = $postTest ? array_sum([
                            $postTest->distress_item_01, $postTest->distress_item_02, $postTest->distress_item_03, $postTest->distress_item_04, $postTest->distress_item_05,
                            $postTest->eustress_item_01, $postTest->eustress_item_02, $postTest->eustress_item_03, $postTest->eustress_item_04
                        ]) : 'N/A';
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $participant ? $participant->name : 'N/A' }}</strong><br>
                            <span style="color: #666;">ID: #P-{{ $participant ? str_pad($participant->id, 3, '0', STR_PAD_LEFT) : '??' }}</span><br>
                            Age: {{ $participant ? $participant->age : 'N/A' }}<br>
                            Gender: {{ $participant ? ucfirst($participant->gender) : 'N/A' }}
                        </td>
                        <td>
                            <strong>{{ $test ? $test->test_type : 'N/A' }}</strong><br>
                            {{ $session->created_at ? $session->created_at->format('M d, Y') : 'N/A' }}<br>
                            {{ $session->created_at ? $session->created_at->format('h:i A') : '' }}
                        </td>
                        <td>
                            @if($result)
                                Accuracy: <strong>{{ round($result->accuracy_rate, 2) }}%</strong><br>
                                Correct / Total: {{ $result->correct_answers }} / {{ $result->total_attempts }}<br>
                                Avg Reaction: {{ round($result->average_reaction_time, 2) }}ms
                            @else
                                <span style="color: #999;">No results logged</span>
                            @endif
                        </td>
                        <td>
                            <table style="margin-bottom:0;">
                                <tr>
                                    <td style="border:none; padding: 0 5px 0 0; width:50%;">
                                        <div class="section-title">Pre-Test</div>
                                        Total Score: <strong>{{ $preTotal }}</strong>
                                    </td>
                                    <td style="border:none; border-left:1px solid #ddd; padding: 0 0 0 5px; width:50%;">
                                        <div class="section-title">Post-Test</div>
                                        Total Score: <strong>{{ $postTotal }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <p style="text-align: right; color: #666; font-size: 10px;">Total Records: {{ $sessions->count() }}</p>
    @endif

</body>
</html>
