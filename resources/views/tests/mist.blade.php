<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MIST Stress Task</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #222; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .test-container { background: #333; padding: 40px; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.5); width: 100%; max-width: 700px; text-align: center; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #555; padding-bottom: 15px;}
        .title { font-size: 24px; font-weight: bold; color: #fff; }
        .timer { background-color: #dc3545; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 20px;}

        /* REACTION TIME PEER PRESSURE UI */
        .performance-panel { background: #111; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #444;}
        .bar-container { width: 100%; background-color: #333; border-radius: 4px; margin: 10px 0; height: 20px; position: relative; overflow: hidden;}
        .bar-fill { height: 100%; border-radius: 4px; transition: width 0.3s ease-in-out;}
        .peer-bar { background-color: #198754; width: 15%; } /* Green and short (fast time) */
        .user-bar { background-color: #dc3545; width: 0%; }   /* Red and long (slow time) */
        .bar-label { font-size: 15px; font-weight: bold; text-align: left; display: flex; justify-content: space-between; margin-bottom: 5px;}
        .warning-text { font-size: 13px; color: #ffc107; margin-top: 15px; font-style: italic; font-weight: bold; }

        /* The Math */
        #equation-display { font-size: 60px; font-weight: 900; margin: 30px 0; letter-spacing: 2px;}
        .answer-input { font-size: 35px; padding: 15px; width: 200px; text-align: center; border: 3px solid #555; border-radius: 8px; font-weight: bold; background: #222; color: white; outline: none;}
        .answer-input:focus { border-color: #0d6efd; }
        
        .feedback { font-size: 24px; font-weight: bold; min-height: 35px; margin-bottom: 10px; color: #dc3545;}
        
        #start-btn { padding: 15px 30px; background: #0d6efd; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold;}
        .hidden { display: none !important; }
        #results-screen { display: none; padding: 40px; color: #fff; font-size: 22px; font-weight: bold; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }
        .blink-text { animation: blink 0.2s infinite; }
    </style>
</head>
<body>

    <div class="test-container" id="game-screen">
        <div class="header">
            <div class="title">MIST Protocol</div>
            <div class="timer" id="timer-display">03:00</div> 
        </div>

        <div id="start-screen">
            <p style="font-size: 18px; color: #ccc; margin-bottom: 30px;">Solve the equations as quickly as possible. Your response time is being evaluated against the average speed of your peers.</p>
            <button id="start-btn" onclick="startTest()">Start Evaluation</button>
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
        <span style="font-size: 16px; color: #aaa; font-weight: normal; margin-top: 15px; display: block;">Please wait. You will be redirected automatically.</span>
    </div>

    <script>
        let globalTimeRemaining = 180; // 3 Minutes
        let isPlaying = false;
        let expectedAnswer = 0;
        let questionStartTime = 0;
        
        let totalAttempts = 0;
        let correctAnswers = 0;
        let totalErrors = 0;
        let reactionTimes = [];
        let consecutiveCorrect = 0;
        let consecutiveErrors = 0;

        // --- DUAL-RECORDING ENGINE VARIABLES ---
        let combinedRecorder;
        let combinedChunks = [];
        let animationId;
        let faceStreamMain, screenStreamMain;
        const sessionId = {{ $testSession->id }};

        // --- AUDIO FEEDBACK SETUP ---
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

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

        const faceVid = document.createElement('video');
        faceVid.muted = true; faceVid.autoplay = true; faceVid.playsInline = true;
        faceVid.style.cssText = stealthMode;
        document.body.appendChild(faceVid);
        
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

        async function startTest() {
            try {
                faceStreamMain = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                screenStreamMain = await navigator.mediaDevices.getDisplayMedia({ video: { displaySurface: "window" }, audio: false });

                faceVid.srcObject = faceStreamMain;
                screenVid.srcObject = screenStreamMain;

                await faceVid.play().catch(e => console.log(e));
                await screenVid.play().catch(e => console.log(e));

                // Wait half a second for streams to stabilize
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
            answerInput.focus();
            generateEquation();

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

        // --- STITCHING LOGIC ---
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