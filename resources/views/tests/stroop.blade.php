<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stroop Test</title>
    <!-- Load tracking.js for continuous face monitoring -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/tracking-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/data/face-min.js"></script>
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
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }
        .blink-text { animation: blink 0.2s infinite; }
    </style>
</head>
<body>

    <!-- Hidden Live Face Feed Widget (required for tracking) -->
    <div id="face-preview-container" style="position: fixed; top: -10000px; left: -10000px; opacity: 0; pointer-events: none;">
        <video id="faceVid" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); display: block;"></video>
        <div style="position: absolute; bottom: 6px; left: 6px; background: rgba(0,0,0,0.75); color: #22c55e; font-size: 11px; font-weight: bold; padding: 2px 7px; border-radius: 5px; display: flex; align-items: center; gap: 5px;">
            <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> LIVE FACE
        </div>
    </div>

    <!-- Missing Face Warning Overlay -->
    <div id="face-warning-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.85); z-index: 10000; flex-direction: column; align-items: center; justify-content: center; color: #fff; text-align: center;">
        <div style="background: #1e293b; border: 2px solid #ef4444; padding: 30px 40px; border-radius: 16px; max-width: 480px; box-shadow: 0 10px 30px rgba(239,68,68,0.4);">
            <div style="font-size: 48px; color: #ef4444; margin-bottom: 15px;">⚠️</div>
            <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #f87171;">Please keep you face on screen</h2>
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 20px;">Your face is no longer detected. Please position your face clearly in front of the camera.</p>
            <div style="font-size: 24px; font-weight: 800; color: #ef4444; background: rgba(239,68,68,0.1); padding: 10px 20px; border-radius: 8px;" id="face-warning-timer">Test ending in 5s...</div>
        </div>
    </div>

    <div class="test-container" id="game-screen">
        <div class="header">
            <div class="title">Stroop Task</div>
            <div class="timer" id="timer-display">02:00</div> 
        </div>

        <div id="start-screen">
            <p style="font-size: 18px; color: #555; margin-bottom: 30px;">Select the <strong>INK COLOR</strong> of the word, not the word itself. <br>Respond as quickly and accurately as possible.</p>
            <button id="start-btn" onclick="startTest()">Start Experiment</button>
        </div>

        <div id="test-area" class="hidden">
            <div class="feedback" id="feedback-message"></div>
            <div id="question-timer-display" style="font-size: 20px; color: #dc3545; font-weight: bold; margin-bottom: 15px;"></div>
            <div class="instruction">What is the INK COLOR?</div>
            
            <div id="word-display">WORD</div>

            <div class="color-buttons">
                <!-- Dynamically generated choices -->
            </div>
        </div>
    </div>

    <div class="test-container" id="results-screen">
        Saving Data & Processing Video Evidence... <br>
        <span style="font-size: 16px; color: #666; font-weight: normal; margin-top: 15px; display: block;">Please wait. You will be redirected automatically.</span>
    </div>

    <button id="mute-btn" onclick="toggleMute()" class="hidden" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 1000;">🔇 Mute Alarm</button>

    <script>
        const colors = [
            { name: 'RED', value: 'red', hex: '#dc3545' },
            { name: 'BLUE', value: 'blue', hex: '#0d6efd' },
            { name: 'GREEN', value: 'green', hex: '#198754' },
            { name: 'YELLOW', value: 'yellow', hex: '#ffc107' },
            { name: 'PURPLE', value: 'purple', hex: '#6f42c1' },
            { name: 'ORANGE', value: 'orange', hex: '#fd7e14' },
            { name: 'BLACK', value: 'black', hex: '#212529' },
            { name: 'GRAY', value: 'gray', hex: '#6c757d' },
            { name: 'BROWN', value: 'brown', hex: '#8B4513' }
        ];

        let globalTimeRemaining = 120; 
        let isPlaying = false;
        let currentInkColor = '';
        let isMuted = false;
        let alarmInterval = null;

        function toggleMute() {
            isMuted = !isMuted;
            const btn = document.getElementById('mute-btn');
            btn.innerText = isMuted ? "🔊 Unmute Alarm" : "🔇 Mute Alarm";
            btn.style.backgroundColor = isMuted ? "#6c757d" : "#dc3545";
        }
        let wordStartTime = 0;
        let questionTimerInterval;
        let questionTimeRemaining = 0;
        
        let totalAttempts = 0, correctAnswers = 0, totalErrors = 0, reactionTimes = [];

        let combinedRecorder;
        let combinedChunks = [];
        let animationId;
        let faceStreamMain, screenStreamMain;
        const sessionId = {{ $testSession->id }};

        // --- AUDIO FEEDBACK SETUP ---
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

        function playAlarmTick() {
            if (isMuted) return;
            if (audioCtx.state === 'suspended') audioCtx.resume();
            
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.type = 'square';
            oscillator.frequency.setValueAtTime(900, audioCtx.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(400, audioCtx.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.02);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            oscillator.start(audioCtx.currentTime);
            oscillator.stop(audioCtx.currentTime + 0.15);
        }

        function playSound(type) {
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            if (type === 'correct') {
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(800, audioCtx.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 0.1);
                
                gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                
                oscillator.start(audioCtx.currentTime);
                oscillator.stop(audioCtx.currentTime + 0.3);
            } else {
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, audioCtx.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(100, audioCtx.currentTime + 0.3);
                
                gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                
                oscillator.start(audioCtx.currentTime);
                oscillator.stop(audioCtx.currentTime + 0.3);
            }
        }

        // --- THE CSS TRICK ---
        const stealthMode = "position:fixed; top:-10000px; left:-10000px; width:1920px; height:1080px;";

        const faceVid = document.getElementById('faceVid');
        
        const screenVid = document.createElement('video');
        screenVid.muted = true; screenVid.autoplay = true; screenVid.playsInline = true;
        screenVid.style.cssText = stealthMode;
        document.body.appendChild(screenVid);

        const canvas = document.createElement('canvas');
        canvas.width = 1920;  
        canvas.height = 1080; 
        canvas.style.cssText = stealthMode;
        document.body.appendChild(canvas);
        const ctx = canvas.getContext('2d');

        // --- CONTINUOUS FACE MONITORING LOGIC ---
        let faceTracker = null;
        let lastFaceDetectedTime = Date.now();
        let faceWarningActive = false;
        let faceWarningStartTime = 0;
        let faceMonitorInterval = null;
        let isFaceMonitoringStarted = false;

        function startContinuousFaceMonitoring() {
            if (isFaceMonitoringStarted) return;
            isFaceMonitoringStarted = true;
            lastFaceDetectedTime = Date.now();

            if (typeof tracking !== 'undefined') {
                try {
                    faceTracker = new tracking.ObjectTracker('face');
                    faceTracker.setInitialScale(4);
                    faceTracker.setStepSize(2);
                    faceTracker.setEdgesDensity(0.1);

                    tracking.track('#faceVid', faceTracker);

                    faceTracker.on('track', function(event) {
                        if (event.data && event.data.length > 0) {
                            lastFaceDetectedTime = Date.now();
                            if (faceWarningActive) {
                                dismissFaceWarning();
                            }
                        }
                    });
                } catch (e) {
                    console.error("Tracking JS Error:", e);
                }
            }

            if (!faceMonitorInterval) {
                faceMonitorInterval = setInterval(checkFacePresence, 250);
            }
        }

        function checkFacePresence() {
            if (!isPlaying) return;

            const now = Date.now();
            const elapsedSinceLastFace = (now - lastFaceDetectedTime) / 1000;

            if (elapsedSinceLastFace >= 3) {
                if (!faceWarningActive) {
                    showFaceWarning();
                } else {
                    const warningElapsed = (now - faceWarningStartTime) / 1000;
                    const remainingSecs = Math.max(0, Math.ceil(5 - warningElapsed));
                    const timerEl = document.getElementById('face-warning-timer');
                    if (timerEl) {
                        timerEl.innerText = `Test ending in ${remainingSecs}s...`;
                    }

                    if (warningElapsed >= 5) {
                        clearInterval(faceMonitorInterval);
                        dismissFaceWarning();
                        endTest(true);
                    }
                }
            }
        }

        function showFaceWarning() {
            faceWarningActive = true;
            faceWarningStartTime = Date.now();
            const timerEl = document.getElementById('face-warning-timer');
            if (timerEl) timerEl.innerText = "Test ending in 5s...";
            const overlay = document.getElementById('face-warning-overlay');
            if (overlay) overlay.style.display = 'flex';
        }

        function dismissFaceWarning() {
            faceWarningActive = false;
            const overlay = document.getElementById('face-warning-overlay');
            if (overlay) overlay.style.display = 'none';
        }

        async function startTest() {
            document.documentElement.requestFullscreen().catch(err => console.log("Fullscreen Error:", err));
            try {
                faceStreamMain = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                screenStreamMain = await navigator.mediaDevices.getDisplayMedia({ video: { displaySurface: "window" }, audio: false });

                faceVid.srcObject = faceStreamMain;
                screenVid.srcObject = screenStreamMain;

                await faceVid.play().catch(e => console.log(e));
                await screenVid.play().catch(e => console.log(e));

                startContinuousFaceMonitoring();

                // Wait half a second before capturing to ensure cameras are fully on
                setTimeout(() => {
                    drawDualFrame();
                    
                    const combinedStream = canvas.captureStream(30);
                    const audioTrack = faceStreamMain.getAudioTracks()[0];
                    if (audioTrack) combinedStream.addTrack(audioTrack);

                    let options = { mimeType: 'video/webm; codecs=vp8,opus' };
                    if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                        options = { mimeType: 'video/webm' }; 
                    }

                    combinedRecorder = new MediaRecorder(combinedStream, options);
                    combinedRecorder.ondataavailable = e => {
                        if (e.data && e.data.size > 0) combinedChunks.push(e.data);
                    };
                    combinedRecorder.start(500); 
                }, 500);

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

                if (globalTimeRemaining <= 30) {
                    document.getElementById('timer-display').style.backgroundColor = '#dc3545';
                }

                if (globalTimeRemaining <= 60 && !alarmInterval) {
                    document.getElementById('mute-btn').classList.remove('hidden');
                    alarmInterval = setInterval(() => {
                        if (isPlaying && globalTimeRemaining > 0) {
                            playAlarmTick();
                        } else {
                            clearInterval(alarmInterval);
                        }
                    }, 400); // Fast beeping!
                }

                if (globalTimeRemaining <= 0) {
                    clearInterval(globalTimer);
                    endTest(false);
                }
            }, 1000);
        }

        function drawDualFrame() {
            if (!isPlaying) return;

            ctx.fillStyle = "black";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            if (faceVid.readyState >= 2) {
                ctx.save();
                ctx.translate(960, 0); 
                ctx.scale(-1, 1); 
                ctx.drawImage(faceVid, 0, 270, 960, 540);
                ctx.restore();
            }

            if (screenVid.readyState >= 2) {
                ctx.drawImage(screenVid, 960, 270, 960, 540);
            }

            ctx.fillStyle = "white";
            ctx.font = "bold 30px Arial";
            ctx.fillText("Participant Reaction", 50, 50);
            ctx.fillText("Task Interface", 1010, 50);

            animationId = requestAnimationFrame(drawDualFrame);
        }

        function nextWord() {
            let randomWordObj = colors[Math.floor(Math.random() * colors.length)];
            let randomWord = randomWordObj.name;
            
            let availableColors = colors.filter(c => c.name !== randomWord);
            let colorObj = availableColors[Math.floor(Math.random() * availableColors.length)];
            currentInkColor = colorObj.value;
            
            let display = document.getElementById('word-display');
            display.innerText = randomWord;
            display.style.color = colorObj.hex;
            wordStartTime = Date.now();

            let choice1 = colorObj; // ink color (correct)
            let choice2 = randomWordObj; // word meaning (incorrect)

            let choices = Math.random() > 0.5 ? [choice1, choice2] : [choice2, choice1];

            let buttonsHtml = '';
            choices.forEach(choice => {
                let textColor = (choice.name === 'YELLOW') ? '#000' : '#fff';
                buttonsHtml += `<button class="color-btn" style="background-color: ${choice.hex}; color: ${textColor};" onclick="checkAnswer('${choice.value}')">${choice.name}</button>`;
            });
            document.querySelector('.color-buttons').innerHTML = buttonsHtml;

            startQuestionTimer();
        }

        function startQuestionTimer() {
            if (questionTimerInterval) clearInterval(questionTimerInterval);
            
            questionTimeRemaining = globalTimeRemaining > 60 ? 7 : 3;
            document.getElementById('question-timer-display').innerText = `Time left: ${questionTimeRemaining}s`;

            questionTimerInterval = setInterval(() => {
                questionTimeRemaining--;
                document.getElementById('question-timer-display').innerText = `Time left: ${questionTimeRemaining}s`;
                
                if (questionTimeRemaining <= 0) {
                    clearInterval(questionTimerInterval);
                    if (!isPlaying) return;
                    totalAttempts++;
                    totalErrors++;
                    let feedback = document.getElementById('feedback-message');
                    feedback.innerText = "TIMEOUT";
                    feedback.style.color = "#dc3545";
                    feedback.classList.add('blink-text');
                    document.getElementById('test-area').animate([
                        { transform: 'translateX(-10px)' }, { transform: 'translateX(10px)' }, { transform: 'translateX(0px)' }
                    ], { duration: 300 });
                    playSound('incorrect');
                    setTimeout(() => { if(feedback.innerText !== "") { feedback.innerText = ""; feedback.classList.remove('blink-text'); } }, 500);
                    nextWord();
                }
            }, 1000);
        }

        function checkAnswer(selectedColor) {
            if(!isPlaying) return;
            if(questionTimerInterval) clearInterval(questionTimerInterval);
            totalAttempts++;
            reactionTimes.push(Date.now() - wordStartTime);

            let feedback = document.getElementById('feedback-message');
            if (selectedColor === currentInkColor) {
                correctAnswers++;
                feedback.innerText = "Correct";
                feedback.style.color = "#198754";
                feedback.classList.remove('blink-text');
            } else {
                totalErrors++;
                feedback.innerText = "WRONG";
                feedback.style.color = "#dc3545";
                feedback.classList.add('blink-text');
                document.getElementById('test-area').animate([
                    { transform: 'translateX(-10px)' }, { transform: 'translateX(10px)' }, { transform: 'translateX(0px)' }
                ], { duration: 300 });
                playSound('incorrect');
            }

            setTimeout(() => { if(feedback.innerText !== "") { feedback.innerText = ""; feedback.classList.remove('blink-text'); } }, 500);
            nextWord();
        }

        function endTest(isInterrupted = false) {
            isPlaying = false;
            if (typeof faceMonitorInterval !== 'undefined' && faceMonitorInterval) {
                clearInterval(faceMonitorInterval);
            }
            if (typeof dismissFaceWarning === 'function') {
                dismissFaceWarning();
            }
            if (questionTimerInterval) clearInterval(questionTimerInterval);
            cancelAnimationFrame(animationId);
            
            document.getElementById('game-screen').classList.add('hidden');
            document.getElementById('results-screen').style.display = 'block';

            // When the recorder stops, IT triggers the upload. (No more random 3 second guessing!)
            if (combinedRecorder && combinedRecorder.state !== 'inactive') {
                combinedRecorder.onstop = () => {
                    uploadVideo(isInterrupted);
                };
                combinedRecorder.stop();
            } else {
                uploadVideo(isInterrupted);
            }

            // Shut down cameras
            if(faceStreamMain) faceStreamMain.getTracks().forEach(t => t.stop());
            if(screenStreamMain) screenStreamMain.getTracks().forEach(t => t.stop());
        }

        function uploadVideo(isInterrupted = false) {
            let formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            if(combinedChunks.length > 0) {
                let finalVideo = new Blob(combinedChunks, { type: 'video/webm' });
                
                // Debug tool to see if the browser is still breaking the canvas
                if(finalVideo.size === 0) {
                    alert("FATAL ERROR: The video recorded 0 bytes. Please ensure the window remains maximized.");
                }

                // Append the SAME video twice to satisfy Laravel's strict check
                formData.append('face_video', finalVideo, 'combined_face.webm');
                formData.append('screen_video', finalVideo, 'combined_screen.webm');
            }

            let accuracy = totalAttempts > 0 ? ((correctAnswers / totalAttempts) * 100).toFixed(2) : 0;
            let avgReaction = reactionTimes.length > 0 ? (reactionTimes.reduce((a,b) => a+b, 0) / reactionTimes.length).toFixed(0) : 0;
            
            formData.append('accuracy_rate', totalAttempts);
            formData.append('total_error', correctAnswers);
            formData.append('average_reaction_time', totalErrors);

            fetch(`/test-sessions/${sessionId}/recordings`, { 
                method: 'POST', 
                body: formData 
            })
            .then(async (res) => {
                if (!res.ok) {
                    let err = await res.text();
                    alert("UPLOAD ERROR " + res.status + ":\n\n" + err.substring(0, 400));
                } else {
                    if (!isInterrupted) {
                        localStorage.setItem('test_completed_' + sessionId, 'true');
                    }
                    window.close();
                    document.getElementById('results-screen').innerHTML = "<h2 style='color:#198754'>Upload Complete!</h2><p>You may safely close this window.</p>";
                }
            })
            .catch(err => {
                alert("NETWORK ERROR: " + err);
            });
        }
    </script>

</body>
</html>