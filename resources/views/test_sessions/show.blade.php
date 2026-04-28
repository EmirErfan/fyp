<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Final Report</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        .card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-bottom: 20px; background-color: #fdfdfd; }
        .card-title { font-size: 20px; font-weight: bold; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .data-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed #eee; padding-bottom: 5px;}
        .highlight { font-weight: bold; color: #d9534f; }
        .btn { padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>

    <h1 style="text-align: center;">Experiment Final Report</h1>
    <div style="text-align: center; margin-bottom: 20px;">
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
            
            <h4 style="margin-top: 15px; margin-bottom: 5px;">Recordings:</h4>
            @if($session->result->face_video_path)
                <a href="{{ $session->result->face_video_path }}" target="_blank" style="color: blue;">View Face Recording</a><br>
            @endif
            @if($session->result->screen_video_path)
                <a href="{{ $session->result->screen_video_path }}" target="_blank" style="color: blue;">View Screen Recording</a>
            @endif
        @else
            <p style="color: red;">No results entered yet.</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">3. Psychological Assessment Comparison</div>
        
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <tr style="background-color: #eee;">
                <th style="padding: 10px;">Metric (1-5 Scale)</th>
                <th style="padding: 10px;">Pre-Test</th>
                <th style="padding: 10px;">Post-Test</th>
            </tr>
            <tr>
                <td style="padding: 10px;">Anxiety Level</td>
                <td style="padding: 10px;">{{ $preTest ? $preTest->distress_item_01 : 'N/A' }}</td>
                <td style="padding: 10px; font-weight: bold;">{{ $postTest ? $postTest->distress_item_01 : 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px;">Feeling Overwhelmed</td>
                <td style="padding: 10px;">{{ $preTest ? $preTest->distress_item_03 : 'N/A' }}</td>
                <td style="padding: 10px; font-weight: bold;">{{ $postTest ? $postTest->distress_item_03 : 'N/A' }}</td>
            </tr>
             <tr>
                <td style="padding: 10px;">Mental Fatigue</td>
                <td style="padding: 10px;">{{ $preTest ? $preTest->distress_item_05 : 'N/A' }}</td>
                <td style="padding: 10px; font-weight: bold;">{{ $postTest ? $postTest->distress_item_05 : 'N/A' }}</td>
            </tr>
        </table>
        <p style="font-size: 12px; color: #777; margin-top: 10px;">* Showing selected key metrics for quick review.</p>
    </div>

</body>
</html>