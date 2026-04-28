<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Test Results</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto;}
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { padding: 10px; width: 100%; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;}
        .info-box { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        button { padding: 12px 20px; background-color: #ffc107; color: black; border: none; cursor: pointer; font-size: 16px; font-weight: bold; border-radius: 5px; width: 100%;}
    </style>
</head>
<body>

    <h1 style="text-align: center;">Enter Experiment Results</h1>
    
    <div class="info-box">
        <p><strong>Participant:</strong> {{ $testSession->participant->name }}</p>
        <p><strong>Test Completed:</strong> {{ $testSession->test->test_type }} ({{ $testSession->test->test_level }})</p>
    </div>

    <form action="/test-sessions/{{ $testSession->id }}/results" method="POST">
        @csrf

        <h3>1. Performance Metrics</h3>
        
        <div class="form-group">
            <label for="accuracy_rate">Accuracy Rate (%):</label>
            <input type="number" step="0.01" id="accuracy_rate" name="accuracy_rate" placeholder="e.g. 95.50" required>
        </div>

        <div class="form-group">
            <label for="average_reaction_time">Average Reaction Time (Milliseconds):</label>
            <input type="number" step="0.01" id="average_reaction_time" name="average_reaction_time" placeholder="e.g. 1450" required>
        </div>

        <div class="form-group">
            <label for="total_error">Total Errors Made:</label>
            <input type="number" id="total_error" name="total_error" placeholder="e.g. 4" required>
        </div>

        <h3 style="margin-top: 30px;">2. Video Recordings (Cloud Links)</h3>
        <p style="font-size: 14px; color: #666;">Paste the Google Drive or Cloud URL for the recordings below.</p>

        <div class="form-group">
            <label for="face_video_path">Face Recording Link:</label>
            <input type="url" id="face_video_path" name="face_video_path" placeholder="https://...">
        </div>

        <div class="form-group">
            <label for="screen_video_path">Screen Recording Link:</label>
            <input type="url" id="screen_video_path" name="screen_video_path" placeholder="https://...">
        </div>

        <button type="submit">Save Results & Videos</button>
    </form>

    <br>
    <a href="/test-sessions" style="display: block; text-align: center;">Back to Dashboard</a>

</body>
</html>