<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Environment Launch</title>
    <style>
        body { background-color: #f8f9fa; margin: 0; font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .launch-card { background: #fff; padding: 50px; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.05); max-width: 600px; text-align: center; }
        
        h2 { color: #212529; font-size: 28px; margin-bottom: 15px; }
        p { color: #555; font-size: 16px; margin-bottom: 30px; line-height: 1.6; }
        
        .launch-btn { display: inline-block; background-color: #dc3545; color: white; border: none; cursor: pointer; padding: 18px 40px; font-size: 20px; font-weight: bold; border-radius: 8px; transition: 0.2s; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); margin-bottom: 20px; }
        .launch-btn:hover { background-color: #bb2d3b; transform: translateY(-2px); }
        .launch-btn:disabled { background-color: #6c757d; cursor: not-allowed; transform: none; box-shadow: none; }
        
        .status-text { font-size: 18px; color: #0d6efd; font-weight: bold; display: none; margin-top: 20px; border-top: 2px solid #f0f0f0; padding-top: 25px;}
    </style>
</head>
<body>

<div class="launch-card">
    <h2>Secure Test Environment Ready</h2>
    <p>
        <strong>Participant: {{ $session->participant->name ?? 'Unknown' }}</strong><br>
        The cognitive task will open in a dedicated, isolated browser window to ensure accurate data recording. Please maximize the new window once it opens.
    </p>

    <button id="launchBtn" class="launch-btn" onclick="openTestWindow()">
        Launch {{ $session->test->test_type ?? 'Task' }}
    </button>

    <div id="statusMsg" class="status-text">
        Test in progress in separate window... <br>
        <span style="font-size: 14px; color: #666; font-weight: normal; margin-top: 10px; display: block;">
            Do not close this tab. You will be automatically redirected to the Post-Test when the task is complete.
        </span>
    </div>
</div>

<script>
    function openTestWindow() {
        // 1. Open the dedicated test window
        let testWindow = window.open('{{ $testUrl }}', 'TestEnvironment', 'width=1200,height=800,menubar=no,toolbar=no,location=no,status=no,resizable=yes,scrollbars=yes');

        // 2. Disable the launch button so they can't click it twice
        document.getElementById('launchBtn').disabled = true;
        document.getElementById('launchBtn').innerText = "Test Running...";
        
        // 3. Show the "Test in progress" message
        document.getElementById('statusMsg').style.display = "block";

        // 4. THE MAGIC: Check every 1 second if the popup window has closed
        let timer = setInterval(function() {
            if (testWindow && testWindow.closed) {
                clearInterval(timer); // Stop checking
                
                // Change the text to green to show success
                document.getElementById('statusMsg').innerHTML = "<span style='color: #198754;'>Test Complete! Loading Post-Test...</span>";
                
                // 5. Automatically redirect the main Launch Pad directly to the Post-Test!
                window.location.href = "/test-sessions/{{ $session->id }}/post-test";
            }
        }, 1000); 
    }
</script>

</body>
</html>