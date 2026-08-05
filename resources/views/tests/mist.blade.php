<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MIST Stress Task</title>
    <!-- Load tracking.js for continuous face monitoring -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/tracking-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/data/face-min.js"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #f4f7f6; color: #333; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .test-container { background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); width: 100%; max-width: 700px; text-align: center; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px;}
        .title { font-size: 24px; font-weight: bold; color: #111; }
        .timer { background-color: #dc3545; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 20px;}

        /* REACTION TIME PEER PRESSURE UI */
        .performance-panel { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0;}
        .bar-container { width: 100%; background-color: #e2e8f0; border-radius: 4px; margin: 10px 0; height: 20px; position: relative; overflow: hidden;}
        .bar-fill { height: 100%; border-radius: 4px; transition: width 0.3s ease-in-out;}
        .peer-bar { background-color: #198754; width: 15%; } /* Green and short (fast time) */
        .user-bar { background-color: #dc3545; width: 0%; }   /* Red and long (slow time) */
        .bar-label { font-size: 15px; font-weight: bold; text-align: left; display: flex; justify-content: space-between; margin-bottom: 5px; color: #333;}
        .warning-text { font-size: 13px; color: #b45309; margin-top: 15px; font-style: italic; font-weight: bold; }

        /* The Math */
        #equation-display { font-size: 60px; font-weight: 900; margin: 30px 0; letter-spacing: 2px; color: #111;}
        .answer-input { font-size: 35px; padding: 15px; width: 200px; text-align: center; border: 3px solid #ccc; border-radius: 8px; font-weight: bold; background: #fff; color: #111; outline: none; transition: border-color 0.2s, box-shadow 0.2s;}
        .answer-input:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,0.15);}
        
        .feedback { font-size: 24px; font-weight: bold; min-height: 35px; margin-bottom: 10px; color: #dc3545;}
        
        #start-btn { padding: 15px 30px; background: #0d6efd; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold; transition: transform 0.1s;}
        #start-btn:active { transform: scale(0.98); }
        .hidden { display: none !important; }
        #results-screen { display: none; padding: 40px; color: #333; font-size: 22px; font-weight: bold; }
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
            <div class="title">MIST Protocol</div>
            <div class="timer" id="timer-display">03:00</div> 
        </div>

        <div id="start-screen">
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Solve the equations as quickly as possible. Your response time is being evaluated against the average speed of your peers.</p>
            <button id="start-btn" onclick="initRecordingAndRest()">Start Evaluation</button>
        </div>

        <div id="rest-screen" class="hidden" style="text-align: center; padding: 2rem;">
            <h2 style="color: #059669; font-size: 2rem;">Baseline Rest Period</h2>
            <p style="font-size: 1.1rem; color: #4b5563;">Please sit quietly, breathe normally, and look at the screen.</p>
            <div style="font-size: 3rem; font-weight: bold; margin-top: 2rem; color: #111;">
                00:<span id="rest-timer">30</span>
            </div>
            <p style="font-size: 0.9rem; color: #9ca3af; margin-top: 20px;">The evaluation will begin automatically.</p>
        </div>

        <div id="test-area" class="hidden">
            <div class="performance-panel">
                <div class="bar-label"><span style="color: #198754;">Target Peer Average Time</span> <span id="peer-time-text">1.8s</span></div>
                <div class="bar-container"><div class="bar-fill peer-bar" id="peer-bar-fill"></div></div>
                
                <div class="bar-label" style="margin-top: 20px;"><span style="color: #dc3545;">Your Average Time</span> <span id="user-time-text">0.0s</span></div>
                <div class="bar-container"><div class="bar-fill user-bar" id="user-bar-fill"></div></div>
                
                <div class="warning-text" id="stress-warning">Analyzing response baseline...</div>
            </div>

            <div class="feedback" id="feedback-message"></div>
            
            <div id="equation-display">...</div>

            <input type="number" id="answer-input" class="answer-input" autocomplete="off">
            <p style="color: #888; font-size: 14px; margin-top: 15px;">Press Enter to submit</p>
        </div>
    </div>

    <div class="test-container" id="results-screen">
        Finalizing Evaluation & Processing Video... <br>
        <span style="font-size: 16px; color: #666; font-weight: normal; margin-top: 15px; display: block;">Please wait. You will be redirected automatically.</span>
    </div>

    <button id="mute-btn" onclick="toggleMute()" class="hidden" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 1000;">🔇 Mute Alarm</button>

    <script>
        let globalTimeRemaining = 180; // 3 Minutes
        let isPlaying = false;
        let expectedAnswer = 0;
        let isMuted = false;
        let alarmInterval = null;

        function toggleMute() {
            isMuted = !isMuted;
            const btn = document.getElementById('mute-btn');
            btn.innerText = isMuted ? "🔊 Unmute Alarm" : "🔇 Mute Alarm";
            btn.style.backgroundColor = isMuted ? "#6c757d" : "#dc3545";
        }
        let questionStartTime = 0;
        
        let totalAttempts = 0;
        let correctAnswers = 0;
        let totalErrors = 0;
        let reactionTimes = [];
        let consecutiveCorrect = 0;
        let consecutiveErrors = 0;

        // --- REST PHASE VARIABLES ---
        let restTimeLeft = 30;
        let restInterval;

        // --- DUAL-RECORDING ENGINE VARIABLES ---
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

        const answerInput = document.getElementById('answer-input');
        answerInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && isPlaying) {
                event.preventDefault();
                checkAnswer();
            }
        });

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
                        endTest();
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

        // --- NEW INITIALIZATION FUNCTION ---
        async function initRecordingAndRest() {
            try {
                // Request permissions immediately so recording captures the baseline!
                faceStreamMain = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                screenStreamMain = await navigator.mediaDevices.getDisplayMedia({ video: { displaySurface: "window" }, audio: false });

                faceVid.srcObject = faceStreamMain;
                screenVid.srcObject = screenStreamMain;

                await faceVid.play().catch(e => console.log(e));
                await screenVid.play().catch(e => console.log(e));

                startContinuousFaceMonitoring();

                // Start the recording processes
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
                alert("Camera/Screen Error. Permissions required to proceed."); return;
            }

            // Hide start screen, show rest screen
            document.getElementById('start-screen').classList.add('hidden');
            document.getElementById('rest-screen').classList.remove('hidden');

            // Begin the 30-second countdown
            restInterval = setInterval(() => {
                restTimeLeft--;
                
                // Format nicely with a leading zero
                let displayTime = restTimeLeft < 10 ? "0" + restTimeLeft : restTimeLeft;
                document.getElementById('rest-timer').innerText = displayTime;

                if (restTimeLeft <= 0) {
                    clearInterval(restInterval);
                    startMathTest();
                }
            }, 1000);
        }

        // --- PREVIOUSLY startTest() ---
        function startMathTest() {
            document.documentElement.requestFullscreen().catch(err => console.log("Fullscreen Error:", err));
            // Swap out the UI screens
            document.getElementById('rest-screen').classList.add('hidden');
            document.getElementById('test-area').classList.remove('hidden');
            
            isPlaying = true;
            answerInput.focus();
            generateEquation();

            const globalTimer = setInterval(() => {
                globalTimeRemaining--;
                let minutes = Math.floor(globalTimeRemaining / 60);
                let seconds = globalTimeRemaining % 60;
                document.getElementById('timer-display').innerText = `0${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (globalTimeRemaining <= 30) {
                    document.getElementById('timer-display').style.backgroundColor = '#ff4d4d'; // Brighter red
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
                    endTest();
                }
            }, 1000);
        }

        // --- STITCHING LOGIC ---
        function drawDualFrame() {
            // Keep drawing even if not "playing" math yet, so the rest phase is recorded!
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

        // --- PROGRESSIVE DIFFICULTY ENGINE ---
        function generateEquation() {
            let equationStr = "";
            
            if (globalTimeRemaining > 120) {
                let a = Math.floor(Math.random() * 20) + 5;
                let b = Math.floor(Math.random() * 15) + 1;
                
                if (Math.random() > 0.5) {
                    expectedAnswer = a + b;
                    equationStr = `${a} + ${b}`;
                } else {
                    if (a < b) { let temp = a; a = b; b = temp; }
                    expectedAnswer = a - b;
                    equationStr = `${a} - ${b}`;
                }

            } else if (globalTimeRemaining > 60) {
                let a = Math.floor(Math.random() * 9) + 2; 
                let b = Math.floor(Math.random() * 9) + 2; 
                let c = Math.floor(Math.random() * 20) + 1; 
                
                if (Math.random() > 0.5) {
                    expectedAnswer = a * b + c;
                    equationStr = `${a} x ${b} + ${c}`;
                } else {
                    expectedAnswer = a * b - c;
                    equationStr = `${a} x ${b} - ${c}`;
                }

            } else {
                let a = Math.floor(Math.random() * 80) + 15; 
                let b = Math.floor(Math.random() * 60) + 12; 
                let c = Math.floor(Math.random() * 500) + 100; 
                
                if (Math.random() > 0.5) {
                    expectedAnswer = a * b + c;
                    equationStr = `${a} x ${b} + ${c}`;
                } else {
                    expectedAnswer = a * b - c;
                    equationStr = `${a} x ${b} - ${c}`;
                }
            }
            
            document.getElementById('equation-display').innerText = equationStr;
            questionStartTime = Date.now();
        }

        function checkAnswer() {
            if(answerInput.value === '') return;
            
            totalAttempts++;
            let userAnswer = parseInt(answerInput.value);
            let reactionTime = (Date.now() - questionStartTime) / 1000; 
            reactionTimes.push(reactionTime);

            let feedback = document.getElementById('feedback-message');
            
            if (userAnswer === expectedAnswer) {
                correctAnswers++;
                consecutiveCorrect++;
                consecutiveErrors = 0;
                playSound('correct');

                if (consecutiveCorrect >= 3) {
                    feedback.innerText = "Outstanding precision! Keep the momentum.";
                    feedback.style.color = "#20c997";
                } else {
                    feedback.innerText = "Correct";
                    feedback.style.color = "#198754";
                }
                feedback.classList.remove('blink-text');
            } else {
                totalErrors++;
                consecutiveErrors++;
                consecutiveCorrect = 0;
                playSound('incorrect');

                if (consecutiveErrors >= 3) {
                    feedback.innerText = "Warning: Accuracy dropping significantly. Focus!";
                    feedback.style.color = "#ff4d4d";
                } else {
                    feedback.innerText = "Incorrect";
                    feedback.style.color = "#dc3545";
                }
                feedback.classList.add('blink-text');
                
                document.getElementById('test-area').animate([
                    { transform: 'translateX(-10px)' }, { transform: 'translateX(10px)' }, { transform: 'translateX(0px)' }
                ], { duration: 300 });
            }

            updateFakePeerPressure();

            answerInput.value = '';
            generateEquation();
        }

        function updateFakePeerPressure() {
            let userAvgRaw = reactionTimes.reduce((a,b) => a+b, 0) / reactionTimes.length;
            let userAvg = Math.max(1.5, userAvgRaw); 

            let fakePeerAvg = userAvg * 0.60; 
            
            if(globalTimeRemaining <= 60 && fakePeerAvg > 2.5) {
                fakePeerAvg = 2.1 + (Math.random() * 0.4); 
            }

            document.getElementById('user-time-text').innerText = userAvg.toFixed(1) + "s";
            document.getElementById('peer-time-text').innerText = fakePeerAvg.toFixed(1) + "s";

            let userBarWidth = Math.min(100, (userAvg / 10) * 100);
            let peerBarWidth = Math.min(100, (fakePeerAvg / 10) * 100);

            document.getElementById('user-bar-fill').style.width = userBarWidth + "%";
            document.getElementById('peer-bar-fill').style.width = peerBarWidth + "%";

            let warningText = document.getElementById('stress-warning');
            let difference = (userAvg - fakePeerAvg).toFixed(1);
            warningText.innerText = `Performance Warning: You are ${difference}s slower than the required peer baseline.`;
        }

        function endTest() {
            isPlaying = false;
            if (typeof faceMonitorInterval !== 'undefined' && faceMonitorInterval) {
                clearInterval(faceMonitorInterval);
            }
            if (typeof dismissFaceWarning === 'function') {
                dismissFaceWarning();
            }
            cancelAnimationFrame(animationId);
            
            document.getElementById('game-screen').classList.add('hidden');
            document.getElementById('results-screen').style.display = 'block';

            if (combinedRecorder && combinedRecorder.state !== 'inactive') {
                combinedRecorder.onstop = () => {
                    uploadVideo();
                };
                combinedRecorder.stop();
            } else {
                uploadVideo();
            }

            if(faceStreamMain) faceStreamMain.getTracks().forEach(t => t.stop());
            if(screenStreamMain) screenStreamMain.getTracks().forEach(t => t.stop());
        }

        function uploadVideo() {
            let formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            if(combinedChunks.length > 0) {
                let finalVideo = new Blob(combinedChunks, { type: 'video/webm' });
                
                if(finalVideo.size === 0) {
                    alert("FATAL ERROR: The video recorded 0 bytes. Please ensure the window remains maximized.");
                }

                formData.append('face_video', finalVideo, 'combined_face.webm');
                formData.append('screen_video', finalVideo, 'combined_screen.webm');
            }

            let accuracy = totalAttempts > 0 ? ((correctAnswers / totalAttempts) * 100).toFixed(2) : 0;
            // Calculate the average reaction time in seconds
            let avgReactionSeconds = reactionTimes.length > 0 ? (reactionTimes.reduce((a,b) => a+b, 0) / reactionTimes.length).toFixed(2) : 0;
            
            formData.append('accuracy_rate', accuracy);
            formData.append('total_error', totalErrors);
            formData.append('total_attempts', totalAttempts);
            formData.append('correct_answers', correctAnswers);
            formData.append('average_reaction_time', avgReactionSeconds);

            fetch(`/test-sessions/${sessionId}/recordings`, { 
                method: 'POST', 
                body: formData 
            })
            .then(async (res) => {
                if (!res.ok) {
                    let err = await res.text();
                    alert("UPLOAD ERROR " + res.status + ":\n\n" + err.substring(0, 400));
                } else {
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