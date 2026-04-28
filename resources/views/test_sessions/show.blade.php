<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Final Report</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; background-color: #f4f7f6; }
        .card { border: 1px solid #ddd; padding: 25px; border-radius: 12px; margin-bottom: 25px; background-color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card-title { font-size: 20px; font-weight: bold; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .data-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #eee; padding-bottom: 8px; font-size: 16px;}
        .highlight { font-weight: bold; color: #d9534f; }
        .btn { padding: 12px 20px; background-color: #212529; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; transition: 0.2s;}
        .btn:hover { background-color: #000; }
    </style>
</head>
<body>

    <h1 style="text-align: center; color: #333; margin-bottom: 10px;">Experiment Final Report</h1>
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="/test-sessions" class="btn">Back to Dashboard</a>
    </div>

    <div class="card">
        <div class="card-title">1. Participant & Test Info</div>
        <div class="data-row"><span>Name:</span> <strong>{{ $session->participant->name }}</strong></div>
        <div class="data-row"><span>Age/Gender:</span> <strong>{{ $session->participant->age }} / {{ $session->participant->gender }}</strong></div>
        <div class="data-row"><span>Test Conducted:</span> <strong>{{ $session->test->test_type }} ({{ $session->test->test_level }})</strong></div>
        <div class="data-row"><span>Date & Time:</span> <strong>{{ $session->testSchedule->date }} at {{ $session->testSchedule->time }}</strong></div>
    </div>

    <div class="card">
        <div class="card-title">2. Stress Test Results</div>
        
        @if($session->result)
            <div class="data-row"><span>Accuracy Rate:</span> <strong>{{ $session->result->accuracy_rate }}%</strong></div>
            <div class="data-row"><span>Average Reaction Time:</span> <strong>{{ $session->result->average_reaction_time }} ms</strong></div>
            <div class="data-row"><span>Total Errors:</span> <strong class="highlight">{{ $session->result->total_error }}</strong></div>
            
            <h4 style="margin-top: 30px; margin-bottom: 15px; border-top: 1px dashed #eee; padding-top: 20px; color: #333;">Dual-Recording Evidence:</h4>
            
            @if($session->result->face_video_path)
                <div style="background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 100%;">
                    <video width="100%" controls preload="metadata" style="display: block;">
                        <source src="{{ asset('storage/' . $session->result->face_video_path) }}" type="video/webm">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <p style="text-align: center; color: #666; font-size: 13px; margin-top: 10px;">
                    Synchronized Participant Reaction and Task Interface
                </p>
            @else
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; color: #6c757d; border: 1px dashed #ccc; text-align: center;">
                    <i>No video recording found for this session. The test may have been skipped or aborted early.</i>
                </div>
            @endif
            @else
            <p style="color: #dc3545; font-weight: bold; text-align: center; padding: 20px; background: #f8d7da; border-radius: 8px;">No results entered yet.</p>
        @endif
        </div>

    <div class="card">
        <div class="card-title">3. Psychological Assessment Comparison</div>
        
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Metric (1-5 Scale)</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Pre-Test</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Post-Test</th>
            </tr>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">Anxiety Level</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $preTest ? $preTest->distress_item_01 : 'N/A' }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold;">{{ $postTest ? $postTest->distress_item_01 : 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">Feeling Overwhelmed</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $preTest ? $preTest->distress_item_03 : 'N/A' }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold;">{{ $postTest ? $postTest->distress_item_03 : 'N/A' }}</td>
            </tr>
             <tr>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">Mental Fatigue</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $preTest ? $preTest->distress_item_05 : 'N/A' }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold;">{{ $postTest ? $postTest->distress_item_05 : 'N/A' }}</td>
            </tr>
        </table>
        <p style="font-size: 12px; color: #888; margin-top: 15px; text-align: center;">* Showing selected key metrics for quick review.</p>
    </div>

</body>
</html>