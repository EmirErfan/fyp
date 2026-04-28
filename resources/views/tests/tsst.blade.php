<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TSST - Stress Test</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        
        .test-container { background: white; padding: 50px; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.1); width: 100%; max-width: 700px; text-align: center; position: relative;}
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px;}
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .timer { background-color: #212529; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 20px; letter-spacing: 1px;}

        /* Stressful Feedback Element */
        .feedback { font-size: 20px; font-weight: 900; color: #dc3545; min-height: 55px; margin-bottom: 10px; text-transform: uppercase; display: flex; align-items: center; justify-content: center; transition: opacity 0.2s;}
        
        /* The Numbers */
        .instruction-text { font-size: 18px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;}
        .current-number { font-size: 70px; font-weight: 900; margin: 10px 0; color: #222; letter-spacing: 2px;}
        .task-rule { font-size: 22px; font-weight: bold; color: #dc3545; margin-bottom: 30px; background: #f8d7da; display: inline-block; padding: 10px 20px; border-radius: 8px;}

        /* Input Area */
        .input-area { margin-bottom: 30px; }
        .answer-input { font-size: 30px; padding: 15px; width: 250px; text-align: center; border: 3px solid #ccc; border-radius: 8px; font-weight: bold; outline: none; transition: 0.2s;}
        .answer-input:focus { border-color: #4b6bfb; box-shadow: 0 0 10px rgba(75, 107, 251, 0.3);}
        .instruction { color: #888; font-size: 14px; margin-top: 10px; font-style: italic;}

        #start-btn { padding: 15px 30px; background: #212529; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold;}
        
        /* Results Screen */
        #results-screen { display: none; }
        .uploading-message { padding: 40px; color: #4b6bfb; font-size: 22px; font-weight: bold; }
        .uploading-message i { margin-right: 10px; }
        
        .hidden { display: none !important; }
    </style>
</head>
<body>

    <div class="test-container" id="game-screen">
        <div class="header">
            <div class="title">TSST Arithmetic Task</div>
            <div class="timer" id="timer-display">01:00</div> 
        </div>

        <div id="start-screen">
            <p style="font-size: 18px; color: #555; margin-bottom: 30px;">You are required to serially subtract the number 13 from <strong id="instruction-start-number" style="font-size: 22px; color: #222;">...</strong> as fast and as accurately as possible. <strong>If you make a mistake, you will be forced to start over from the beginning.</strong></p>
            <button id="start-btn" onclick="startTest()">Start Experiment</button>
        </div>

        <div id="test-area" class="hidden">
            <div class="feedback" id="feedback-message"></div>
            
            <div class="instruction-text">Current Number</div>
            <div class="current-number" id="number-display">...</div>
            <div class="task-rule">Subtract 13 Repeatedly</div>

            <div class="input-area">
                <input type="number" id="answer-input" class="answer-input" disabled autocomplete="off">
                <div class="instruction">Type your answer and press Enter</div>
            </div>
        </div>
    </div>

    <div class="test-container" id="results-screen">
        <div id="uploading-message" class="uploading-message">
            Saving Data & Processing Video Evidence... <br>
            <span style="font-size: 16px; color: #666; font-weight: normal; display: block; margin-top: 15px;">Please wait. You will be redirected automatically.</span>
        </div>
    </div>

    <script>
        // --- HARSH PSYCHOLOGICAL FEEDBACK ARRAYS ---
        const slowMessages = [
            "You’re falling behind—pick up the pace.", "That’s too slow. You need to respond faster.",
            "Keep going. Pauses are not acceptable.", "You’re wasting time. Continue immediately.",
            "This pace is below expectations. Speed up.", "Don’t hesitate—answer now.",
            "You should be responding faster than this.", "That delay is too long. Continue.",
            "Stay focused. No unnecessary pauses.", "This is not fast enough. Try again.",
            "You’re underperforming—improve your speed.", "Keep talking. Silence is not acceptable.",
            "You’re losing time. Continue immediately.", "That response was too slow. Do better.",
            "Maintain a faster pace. No delays.", "You need to respond more efficiently.",
            "This is below the required pace. Speed up.", "Don’t stop—keep answering.",
            "You’re expected to perform better than this.", "That hesitation is costing you time.",
            "Focus and respond faster.", "You’re not keeping up. Increase your pace.",
            "This level of delay is not acceptable.", "Keep going. No more pauses.",
            "You are the slowest in this session—improve your pace immediately."
        ];

        const wrongMessages = [
            "That is incorrect. Try again immediately.", "Wrong answer. Focus and respond again.",
            "That response is not acceptable. Answer again.", "Incorrect. You need to do better than that.",
            "That’s wrong. Provide a correct answer now.", "Not correct. Stay focused and try again.",
            "That answer does not meet expectations.", "Incorrect response. Think and answer again quickly.",
            "That is not the right answer. Continue.", "Wrong. You need to improve your accuracy.",
            "That response is below the expected standard.", "Incorrect. Provide a better answer immediately.",
            "That is not acceptable. Try again.", "Wrong answer. Focus and correct it.",
            "That does not meet the requirement. Answer again.", "Incorrect. You need to respond more carefully.",
            "That answer is inaccurate. Try again quickly.", "Wrong. Stay focused and fix your answer.",
            "That is not correct. Do better.", "Incorrect response. Improve your accuracy.",
            "That answer is not sufficient. Try again.", "Wrong. You need to correct that now.",
            "That does not meet expectations. Continue.", "Incorrect. Respond again with a better answer.",
            "That answer is wrong. Focus and improve immediately."
        ];

        let isPlaying = false;
        // NOTE: Change this back to 240 (4 minutes) for the real experiment!
        let globalTimeRemaining = 60; 
        let idleTimer; 
        
        // --- WEBRTC RECORDING VARIABLES ---
        let faceRecorder, screenRecorder;
        let faceChunks = [], screenChunks = [];
        const sessionId = {{ $testSession->id }};
        
        // --- MATH LOGIC SETTINGS ---
        const STARTING_NUMBER = Math.floor(Math.random() * 9000) + 1000;
        const SUBTRACTION_STEP = 13;
        
        let currentDisplayNumber = STARTING_NUMBER;
        let expectedAnswer = STARTING_NUMBER - SUBTRACTION_STEP;

        // Inject the random number into the HTML immediately on page load
        document.getElementById('instruction-start-number').innerText = STARTING_NUMBER;
        document.getElementById('number-display').innerText = STARTING_NUMBER;

        // Scoring
        let totalAttempts = 0;
        let correctAnswers = 0;
        let totalErrors = 0;
        let lowestNumberReached = STARTING_NUMBER; 

        const answerInput = document.getElementById('answer-input');
        const feedbackMsg = document.getElementById('feedback-message');
        const numberDisplay = document.getElementById('number-display');

        // Listen for the ENTER key
        answerInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && isPlaying) {
                event.preventDefault();
                checkAnswer();
            }
        });

        // The Harsh Idle Timer Function
        function resetIdleTimer() {
            clearInterval(idleTimer);
            if (isPlaying) {
                idleTimer = setInterval(() => {
                    let randomSlowMsg = slowMessages[Math.floor(Math.random() * slowMessages.length)];
                    showFeedback(randomSlowMsg, "#dc3545"); 
                }, 6000); 
            }
        }

        async function startTest() {
            try {
                // Check if browser is secure
                if (!navigator.mediaDevices) {
                    throw new Error("Your browser is blocking camera access. Ensure you are using http://127.0.0.1:8000");
                }

                // 1. Request Webcam Permission
                const faceStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                faceRecorder = new MediaRecorder(faceStream);
                faceRecorder.ondataavailable = e => faceChunks.push(e.data);
                faceRecorder.start(1000);

                // 2. Request Screen Share Permission
                const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });
                screenRecorder = new MediaRecorder(screenStream);
                screenRecorder.ondataavailable = e => screenChunks.push(e.data);
                screenRecorder.start(1000);
                
            } catch (err) {
                console.error(err);
                alert("Camera/Screen Error: " + err.message + "\n\nYou MUST allow permissions to begin the test.");
                return; // Stops the test if they deny permissions!
            }

            // If permissions are granted, start the game UI!
            document.getElementById('start-screen').classList.add('hidden');
            document.getElementById('test-area').classList.remove('hidden');
            answerInput.disabled = false;
            answerInput.focus();
            isPlaying = true;
            
            resetIdleTimer();

            const globalTimer = setInterval(() => {
                globalTimeRemaining--;
                let minutes = Math.floor(globalTimeRemaining / 60);
                let seconds = globalTimeRemaining % 60;
                document.getElementById('timer-display').innerText = `0${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                // Force red timer text in the last 30 seconds for extra stress
                if (globalTimeRemaining <= 30) {
                    document.getElementById('timer-display').style.backgroundColor = '#dc3545';
                }

                if (globalTimeRemaining <= 0) {
                    clearInterval(globalTimer);
                    endTest();
                }
            }, 1000);
        }

        function checkAnswer() {
            if(answerInput.value === '') return;

            resetIdleTimer(); // They typed something, reset the hesitation timer

            totalAttempts++;
            let userAnswer = parseInt(answerInput.value);

            if (userAnswer === expectedAnswer) {
                // Correct!
                correctAnswers++;
                currentDisplayNumber = expectedAnswer;
                expectedAnswer -= SUBTRACTION_STEP;
                
                if (currentDisplayNumber < lowestNumberReached) {
                    lowestNumberReached = currentDisplayNumber;
                }

                showFeedback("Correct", "#198754"); 
                
            } else {
                // INCORRECT! Pull a random mean message!
                totalErrors++;
                
                // Reset the math back to the original random starting number
                currentDisplayNumber = STARTING_NUMBER;
                expectedAnswer = STARTING_NUMBER - SUBTRACTION_STEP;
                
                let randomWrongMsg = wrongMessages[Math.floor(Math.random() * wrongMessages.length)];
                showFeedback(randomWrongMsg, "#dc3545"); 
                
                // Shake the screen for psychological impact
                document.getElementById('test-area').animate([
                    { transform: 'translateX(0px)' },
                    { transform: 'translateX(-10px)' },
                    { transform: 'translateX(10px)' },
                    { transform: 'translateX(-10px)' },
                    { transform: 'translateX(10px)' },
                    { transform: 'translateX(0px)' }
                ], { duration: 400 });
            }

            numberDisplay.innerText = currentDisplayNumber;
            answerInput.value = '';
            answerInput.focus();
        }

        function showFeedback(text, color) {
            feedbackMsg.innerText = text;
            feedbackMsg.style.color = color;
            
            // Clear message after 2 seconds
            setTimeout(() => {
                if(feedbackMsg.innerText === text) {
                    feedbackMsg.innerText = "";
                }
            }, 2000);
        }

        function endTest() {
            isPlaying = false;
            clearInterval(idleTimer); 
            
            // --- STOP RECORDING ---
            if (faceRecorder && faceRecorder.state !== 'inactive') faceRecorder.stop();
            if (screenRecorder && screenRecorder.state !== 'inactive') screenRecorder.stop();

            // Transition UI to the Loading State
            document.getElementById('game-screen').classList.add('hidden');
            document.getElementById('results-screen').style.display = 'block';

            // --- THE 3-SECOND BUFFER & AUTO-SAVE UPLOAD ---
            setTimeout(() => {
                let formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // 1. Package the Video Blobs
                if(faceChunks.length > 0) {
                    formData.append('face_video', new Blob(faceChunks, { type: 'video/webm' }), 'face.webm');
                }
                if(screenChunks.length > 0) {
                    formData.append('screen_video', new Blob(screenChunks, { type: 'video/webm' }), 'screen.webm');
                }

                // 2. Package the Math Scores!
                let accuracy = totalAttempts > 0 ? ((correctAnswers / totalAttempts) * 100).toFixed(2) : 0;
                formData.append('accuracy_rate', accuracy);
                formData.append('total_errors', totalErrors);

                // 3. Send to Laravel securely
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
                    alert("NETWORK ERROR: Server disconnected or offline.\n" + err);
                    // Fallback redirect just in case
                    window.location.href = `/test-sessions/${sessionId}/post-test`;
                });

            }, 3000); // Wait exactly 3 seconds for browsers to finish compiling video data
        }
    </script>
</body>
</html>