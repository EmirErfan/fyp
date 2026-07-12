@extends('layouts.guest-test')

@section('title', 'Camera Face Check')

@section('styles')
<style>
    .camera-check-container { max-width: 600px; margin: 40px auto; text-align: center; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    .camera-check-container h2 { font-size: 26px; color: #212529; margin-bottom: 10px; }
    .camera-check-container p { color: #555; font-size: 16px; margin-bottom: 25px; line-height: 1.5; }
    #video-container { position: relative; display: inline-block; width: 100%; max-width: 400px; border-radius: 12px; overflow: hidden; background: #000; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 25px; }
    video { width: 100%; display: block; transform: scaleX(-1); }
    canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: scaleX(-1); }
    .status { padding: 15px; border-radius: 8px; font-weight: bold; font-size: 18px; color: #fff; }
    .status-detecting { background-color: #ffc107; color: #000; }
    .status-success { background-color: #198754; color: #fff; }
    .status-error { background-color: #dc3545; color: #fff; }
</style>
<!-- Load tracking.js and face-min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/tracking-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/data/face-min.js"></script>
@endsection

@section('content')
<div class="camera-check-container">
    <h2>Camera Face Detection</h2>
    <p>We need to ensure your face is clearly visible for the cognitive tasks. Please allow camera access and look into the camera.</p>
    
    <div id="video-container">
        <video id="video" preload autoplay loop muted playsinline></video>
        <canvas id="canvas"></canvas>
    </div>

    <div id="status-box" class="status status-detecting">
        Waiting for camera & detecting face...
    </div>
</div>

<script>
    window.onload = function() {
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');
        var statusBox = document.getElementById('status-box');
        var faceDetectedFrames = 0;
        var requiredFrames = 10; 

        var tracker = new tracking.ObjectTracker('face');
        tracker.setInitialScale(4);
        tracker.setStepSize(2);
        tracker.setEdgesDensity(0.1);

        // Manually request camera access for modern browser compatibility
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                video.srcObject = stream;
                
                video.onloadedmetadata = function() {
                    video.play();
                    // Start tracking once video is playing
                    tracking.track('#video', tracker);
                };
            }).catch(function(err) {
                console.error("Camera error:", err);
                statusBox.className = 'status status-error';
                statusBox.innerHTML = 'Camera access denied or error. Please allow camera and refresh.';
            });
        } else {
            statusBox.className = 'status status-error';
            statusBox.innerHTML = 'Camera not supported in this browser.';
        }

        tracker.on('track', function(event) {
            context.clearRect(0, 0, canvas.width, canvas.height);
            
            // Adjust canvas to match video dimensions once video starts playing
            if(canvas.width !== video.videoWidth && video.videoWidth > 0) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
            }

            if (event.data.length === 0) {
                // No face detected
            } else {
                event.data.forEach(function(rect) {
                    context.strokeStyle = '#a64ceb';
                    context.lineWidth = 3;
                    context.strokeRect(rect.x, rect.y, rect.width, rect.height);
                });

                faceDetectedFrames++;
                if (faceDetectedFrames >= requiredFrames) {
                    // Success!
                    statusBox.className = 'status status-success';
                    statusBox.innerHTML = 'Face Detected! Proceeding...';
                    tracker.removeAllListeners(); // stop tracking
                    
                    // Turn off the camera before leaving
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(function(track) {
                            track.stop();
                        });
                    }
                    
                    setTimeout(function() {
                        window.location.href = "/test-sessions/{{ $session->id }}/pre-test";
                    }, 1000);
                }
            }
        });
    };
</script>
@endsection
