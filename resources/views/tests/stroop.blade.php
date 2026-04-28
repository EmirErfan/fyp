<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stroop Test</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #f4f7f6; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .test-container { background: white; padding: 50px; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.1); width: 100%; max-width: 800px; text-align: center; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px;}
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .timer { background-color: #212529; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 20px;}

        /* Stroop specific styles */
        #word-display { font-size: 80px; font-weight: 900; margin: 40px 0; text-transform: uppercase; letter-spacing: 2px;}
        .instruction { font-size: 20px; color: #666; font-weight: bold; margin-bottom: 30px;}
        
        .color-buttons { display: flex; gap: 15px; justify-content: center; }
        .color-btn { flex: 1; padding: 20px; font-size: 20px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; color: white; text-transform: uppercase; transition: transform 0.1s;}
        .color-btn:active { transform: scale(0.95); }
        .btn-red { background-color: #dc3545; }
        .btn-blue { background-color: #0d6efd; }
        .btn-green { background-color: #198754; }
        .btn-yellow { background-color: #ffc107; color: #000; }

        .feedback { font-size: 24px; font-weight: bold; min-height: 35px; margin-bottom: 10px; text-transform: uppercase;}
        
        #start-btn { padding: 15px 30px; background: #212529; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold;}
        .hidden { display: none !important; }
        #results-screen { display: none; padding: 40px; color: #4b6bfb; font-size: 22px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="test-container" id="game-screen">
        <div class="header">
            <div class="title">Stroop Task</div>
            <div class="timer" id="timer-display">01:00</div> 
        </div>

        <div id="start-screen">
            <p style="font-size: 18px; color: #555; margin-bottom: 30px;">Select the <strong>INK COLOR</strong> of the word, not the word itself. <br>Respond as quickly and accurately as possible.</p>
            <button id="start-btn" onclick="startTest()">Start Experiment</button>
        </div>

        <div id="test-area" class="hidden">
            <div class="feedback" id="feedback-message"></div>
            <div class="instruction">What is the INK COLOR?</div>
            
            <div id="word-display">WORD</div>

            <div class="color-buttons">
                <button class="color-btn btn-red" onclick="checkAnswer('red')">Red</button>
                <button class="color-btn btn-blue" onclick="checkAnswer('blue')">Blue</button>
                <button class="color-btn btn-green" onclick="checkAnswer('green')">Green</button>
                <button class="color-btn btn-yellow" onclick="checkAnswer('yellow')">Yellow</button>
            </div>
        </div>
    </div>

    <div class="test-container" id="results-screen">
        Saving Data & Processing Video Evidence... <br>
        <span style="font-size: 16px; color: #666; font-weight: normal; margin-top: 15px; display: block;">Please wait. You will be redirected automatically.</span>
    </div>

    <script>
        const colors = [
            { name: 'RED', value: 'red', hex: '#dc3545' },
            { name: 'BLUE', value: 'blue', hex: '#0d6efd' },
            { name: 'GREEN', value: 'green', hex: '#198754' },
            { name: 'YELLOW', value: 'yellow', hex: '#ffc107' }
        ];

        let globalTimeRemaining = 60; // Set to 240 for 4 minutes
        let isPlaying = false;
        let currentInkColor = '';
        let wordStartTime = 0;
        
        // Scoring
        let totalAttempts = 0;
        let correctAnswers = 0;
        let totalErrors = 0;
        let reactionTimes = [];

        // WebRTC
        let faceRecorder, screenRecorder;
        let faceChunks = [], screenChunks = [];
        const sessionId = {{ $testSession->id }};

        async function startTest() {
            try {
                const faceStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                faceRecorder = new MediaRecorder(faceStream);
                faceRecorder.ondataavailable = e => faceChunks.push(e.data);
                faceRecorder.start(1000);

                const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });
                screenRecorder = new MediaRecorder(screenStream);
                screenRecorder.ondataavailable = e => screenChunks.push(e.data);
                screenRecorder.start(1000);
            } catch (err) {
                alert("Camera/Screen Error. Permissions required."); return;
            }

            document.getElementById('start-screen').classList.add('hidden');
            document.getElementById('test-area').classList.remove('hidden');
            isPlaying = true;
            nextWord();

            const globalTimer = setInterval(() => {
                globalTimeRemaining--;
                let minutes = Math.floor(globalTimeRemaining / 60);
                let seconds = globalTimeRemaining % 60;
                document.getElementById('timer-display').innerText = `0${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (globalTimeRemaining <= 0) {
                    clearInterval(globalTimer);
                    endTest();
                }
            }, 1000);
        }

        function nextWord() {
            // 50% chance of being congruent (matching) or incongruent (stressful)
            let textObj = colors[Math.floor(Math.random() * colors.length)];
            let colorObj = Math.random() > 0.5 ? textObj : colors[Math.floor(Math.random() * colors.length)];
            
            currentInkColor = colorObj.value;
            let display = document.getElementById('word-display');
            display.innerText = textObj.name;
            display.style.color = colorObj.hex;
            
            wordStartTime = Date.now();
        }

        function checkAnswer(selectedColor) {
            if(!isPlaying) return;
            
            totalAttempts++;
            let reactionTime = Date.now() - wordStartTime;
            reactionTimes.push(reactionTime);

            let feedback = document.getElementById('feedback-message');
            
            if (selectedColor === currentInkColor) {
                correctAnswers++;
                feedback.innerText = "Correct";
                feedback.style.color = "#198754";
            } else {
                totalErrors++;
                feedback.innerText = "WRONG";
                feedback.style.color = "#dc3545";
                
                // Stressful screen shake on error
                document.getElementById('test-area').animate([
                    { transform: 'translateX(-10px)' }, { transform: 'translateX(10px)' }, { transform: 'translateX(0px)' }
                ], { duration: 300 });
            }

            setTimeout(() => { if(feedback.innerText !== "") feedback.innerText = ""; }, 500);
            nextWord();
        }

        function endTest() {
            isPlaying = false;
            
            // 1. Safely stop the cameras
            if (faceRecorder && faceRecorder.state !== 'inactive') faceRecorder.stop();
            if (screenRecorder && screenRecorder.state !== 'inactive') screenRecorder.stop();

            document.getElementById('game-screen').classList.add('hidden');
            document.getElementById('results-screen').style.display = 'block';

            setTimeout(() => {
                let formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // 2. Safely package the videos
                if(faceChunks.length > 0) formData.append('face_video', new Blob(faceChunks, { type: 'video/webm' }), 'face.webm');
                if(screenChunks.length > 0) formData.append('screen_video', new Blob(screenChunks, { type: 'video/webm' }), 'screen.webm');

                // 3. Package the math/color scores
                let accuracy = totalAttempts > 0 ? ((correctAnswers / totalAttempts) * 100).toFixed(2) : 0;
                let avgReaction = reactionTimes.length > 0 ? (reactionTimes.reduce((a,b) => a+b, 0) / reactionTimes.length).toFixed(0) : 0;
                
                formData.append('accuracy_rate', accuracy);
                formData.append('total_error', totalErrors);
                formData.append('average_reaction_time', avgReaction);

                // 4. Send to Laravel WITH our Error Trap!
                fetch(`/test-sessions/${sessionId}/recordings`, { 
                    method: 'POST', 
                    body: formData 
                })
                .then(async (res) => {
                    if (!res.ok) {
                        let err = await res.text();
                        alert("UPLOAD ERROR " + res.status + ":\n\n" + err.substring(0, 400));
                    } else {
                        // SUCCESS: Close this dedicated window automatically!
                        window.close();
                        
                        // Fallback message just in case their browser has strict popup blockers
                        document.getElementById('results-screen').innerHTML = "<h2 style='color:#198754'>Upload Complete!</h2><p>You may safely close this window and return to the main screen.</p>";
                    }
                })
                .catch(err => {
                    alert("NETWORK ERROR: " + err);
                    window.location.href = `/test-sessions/${sessionId}/post-test`;
                });
            }, 3000);
        }
    </script>
</body>
</html>