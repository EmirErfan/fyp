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

        let globalTimeRemaining = 60; 
        let isPlaying = false;
        let currentInkColor = '';
        let wordStartTime = 0;
        
        let totalAttempts = 0, correctAnswers = 0, totalErrors = 0, reactionTimes = [];

        let combinedRecorder;
        let combinedChunks = [];
        let animationId;
        let faceStreamMain, screenStreamMain;
        const sessionId = {{ $testSession->id }};

        // --- THE CSS TRICK ---
        // Move them 10,000 pixels off the screen so the browser still fully renders them, but the user can't see them!
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
                screenStreamMain = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });

                faceVid.srcObject = faceStreamMain;
                screenVid.srcObject = screenStreamMain;

                await faceVid.play().catch(e => console.log(e));
                await screenVid.play().catch(e => console.log(e));

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

                if (globalTimeRemaining <= 0) {
                    clearInterval(globalTimer);
                    endTest();
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
            reactionTimes.push(Date.now() - wordStartTime);

            let feedback = document.getElementById('feedback-message');
            if (selectedColor === currentInkColor) {
                correctAnswers++;
                feedback.innerText = "Correct";
                feedback.style.color = "#198754";
            } else {
                totalErrors++;
                feedback.innerText = "WRONG";
                feedback.style.color = "#dc3545";
                document.getElementById('test-area').animate([
                    { transform: 'translateX(-10px)' }, { transform: 'translateX(10px)' }, { transform: 'translateX(0px)' }
                ], { duration: 300 });
            }

            setTimeout(() => { if(feedback.innerText !== "") feedback.innerText = ""; }, 500);
            nextWord();
        }

        function endTest() {
            isPlaying = false;
            cancelAnimationFrame(animationId);
            
            document.getElementById('game-screen').classList.add('hidden');
            document.getElementById('results-screen').style.display = 'block';

            // When the recorder stops, IT triggers the upload. (No more random 3 second guessing!)
            if (combinedRecorder && combinedRecorder.state !== 'inactive') {
                combinedRecorder.onstop = () => {
                    uploadVideo();
                };
                combinedRecorder.stop();
            } else {
                uploadVideo();
            }

            // Shut down cameras
            if(faceStreamMain) faceStreamMain.getTracks().forEach(t => t.stop());
            if(screenStreamMain) screenStreamMain.getTracks().forEach(t => t.stop());
        }

        function uploadVideo() {
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
            
            formData.append('accuracy_rate', accuracy);
            formData.append('total_error', totalErrors);
            formData.append('average_reaction_time', avgReaction);

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