<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post-Test Questionnaire</title>
    <style>
        body { background-color: #f8f9fa; font-family: system-ui, -apple-system, sans-serif; margin: 0; }
        .assessment-container { max-width: 800px; margin: 40px auto; padding: 20px; }
        
        .page-header { text-align: center; margin-bottom: 30px; }
        .page-header h1 { font-size: 32px; font-weight: bold; color: #000; margin-bottom: 15px; }
        .page-header h4 { font-size: 18px; font-weight: bold; color: #000; margin-bottom: 10px; }
        
        .instruction-text { text-align: center; font-style: italic; color: #555; margin-bottom: 30px; font-size: 16px; font-weight: 500;}
        
        .section-title { font-size: 24px; font-weight: bold; color: #000; border-bottom: 2px solid #17a2b8; padding-bottom: 10px; margin-bottom: 20px; margin-top: 40px; }
        
        .question-box { background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 15px; }
        .question-text { font-weight: bold; font-size: 16px; margin-bottom: 15px; color: #000; }
        
        .rating-options { display: flex; gap: 10px; width: 100%; }
        .rating-options input[type="radio"] { display: none; }
        
        .rating-options label { 
            flex: 1; text-align: center; background: #e9ecef; border: 1px solid #dee2e6; 
            padding: 12px 0; border-radius: 4px; cursor: pointer; font-weight: bold; 
            color: #000; font-size: 18px; transition: all 0.2s; margin: 0;
        }
        
        .rating-options label:hover { background: #d3d9df; }
        .rating-options input[type="radio"]:checked + label { 
            background: #0d6efd; color: white; border-color: #0d6efd; box-shadow: 0 0 8px rgba(13, 110, 253, 0.4);
        }
        
        .submit-btn { width: 100%; background: #0d6efd; color: white; border: none; padding: 15px; font-size: 18px; font-weight: bold; border-radius: 6px; cursor: pointer; margin-top: 40px; transition: 0.2s; }
        .submit-btn:hover { background: #0b5ed7; }
        .error-alert { background: #f8d7da; color: #842029; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: bold; }

        .feeling-prompt { font-size: 22px; font-weight: bold; font-style: italic; color: #212529; margin-top: 10px; margin-bottom: 20px; padding-left: 5px; }

        /* Popup Modal Styles */
        .popup-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        }
        .popup-overlay.active {
            opacity: 1; pointer-events: auto;
        }
        .popup-box {
            background: #fff; padding: 35px 40px; border-radius: 12px;
            text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 400px; transform: translateY(-20px); transition: transform 0.3s ease;
        }
        .popup-overlay.active .popup-box {
            transform: translateY(0);
        }
        .popup-box h2 { color: #212529; margin-bottom: 10px; font-size: 26px; font-weight: bold;}
        .popup-box p { color: #555; margin-bottom: 25px; font-size: 17px; line-height: 1.5; }
        .popup-btn {
            background: #212529; color: white; border: none; padding: 14px 25px;
            font-size: 16px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; transition: 0.2s;
        }
        .popup-btn:hover { background: #000; }
    </style>
</head>
<body>

    <!-- POST-TEST POPUP HTML -->
    <div class="popup-overlay" id="instructionPopup">
        <div class="popup-box">
            <h2>Task Complete!</h2>
            <p>Great job. Now, please complete this <strong>Post-Test</strong> questionnaire to finalize your session.</p>
            <button class="popup-btn" onclick="closePopup()">Continue to Post-Test</button>
        </div>
    </div>

    <div class="assessment-container">
        
        <div class="page-header">
            <h1>Post-Test Questionnaire</h1>
            <h4>Participant: {{ $session->participant->name ?? 'Unknown Participant' }}</h4>
        </div>

        <div style="text-align: center; margin-bottom: 40px;">
            <p style="color: #666; font-size: 16px; font-style: italic;">
                (Please rate on a scale of 1: totally disagree to 5: totally agree)
            </p>
        </div>

        @if ($errors->any())
            <div class="error-alert">
                Please answer all 9 questions before submitting.
            </div>
        @endif

        <form action="/test-sessions/{{ $session->id }}/post-test" method="POST">
            @csrf

            <div class="section-title">Distress Items</div>

            <div class="feeling-prompt">Right now, I am feeling...</div>

            <div class="question-box">
                <div class="question-text">1. ... stressed.</div>
                <div class="rating-options">
                    <input type="radio" name="distress_item_01" id="d1_1" value="1" required><label for="d1_1">1</label>
                    <input type="radio" name="distress_item_01" id="d1_2" value="2"><label for="d1_2">2</label>
                    <input type="radio" name="distress_item_01" id="d1_3" value="3"><label for="d1_3">3</label>
                    <input type="radio" name="distress_item_01" id="d1_4" value="4"><label for="d1_4">4</label>
                    <input type="radio" name="distress_item_01" id="d1_5" value="5"><label for="d1_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">2. ... overwhelmed.</div>
                <div class="rating-options">
                    <input type="radio" name="distress_item_02" id="d2_1" value="1" required><label for="d2_1">1</label>
                    <input type="radio" name="distress_item_02" id="d2_2" value="2"><label for="d2_2">2</label>
                    <input type="radio" name="distress_item_02" id="d2_3" value="3"><label for="d2_3">3</label>
                    <input type="radio" name="distress_item_02" id="d2_4" value="4"><label for="d2_4">4</label>
                    <input type="radio" name="distress_item_02" id="d2_5" value="5"><label for="d2_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">3. ... exhausted.</div>
                <div class="rating-options">
                    <input type="radio" name="distress_item_03" id="d3_1" value="1" required><label for="d3_1">1</label>
                    <input type="radio" name="distress_item_03" id="d3_2" value="2"><label for="d3_2">2</label>
                    <input type="radio" name="distress_item_03" id="d3_3" value="3"><label for="d3_3">3</label>
                    <input type="radio" name="distress_item_03" id="d3_4" value="4"><label for="d3_4">4</label>
                    <input type="radio" name="distress_item_03" id="d3_5" value="5"><label for="d3_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">4. ... strained.</div>
                <div class="rating-options">
                    <input type="radio" name="distress_item_04" id="d4_1" value="1" required><label for="d4_1">1</label>
                    <input type="radio" name="distress_item_04" id="d4_2" value="2"><label for="d4_2">2</label>
                    <input type="radio" name="distress_item_04" id="d4_3" value="3"><label for="d4_3">3</label>
                    <input type="radio" name="distress_item_04" id="d4_4" value="4"><label for="d4_4">4</label>
                    <input type="radio" name="distress_item_04" id="d4_5" value="5"><label for="d4_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">5. ... mentally restless.</div>
                <div class="rating-options">
                    <input type="radio" name="distress_item_05" id="d5_1" value="1" required><label for="d5_1">1</label>
                    <input type="radio" name="distress_item_05" id="d5_2" value="2"><label for="d5_2">2</label>
                    <input type="radio" name="distress_item_05" id="d5_3" value="3"><label for="d5_3">3</label>
                    <input type="radio" name="distress_item_05" id="d5_4" value="4"><label for="d5_4">4</label>
                    <input type="radio" name="distress_item_05" id="d5_5" value="5"><label for="d5_5">5</label>
                </div>
            </div>

            <div class="section-title">Eustress Items</div>

            <div class="feeling-prompt">Right now, I am feeling...</div>

            <div class="question-box">
                <div class="question-text">6. ... full of joy.</div>
                <div class="rating-options">
                    <input type="radio" name="eustress_item_01" id="e1_1" value="1" required><label for="e1_1">1</label>
                    <input type="radio" name="eustress_item_01" id="e1_2" value="2"><label for="e1_2">2</label>
                    <input type="radio" name="eustress_item_01" id="e1_3" value="3"><label for="e1_3">3</label>
                    <input type="radio" name="eustress_item_01" id="e1_4" value="4"><label for="e1_4">4</label>
                    <input type="radio" name="eustress_item_01" id="e1_5" value="5"><label for="e1_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">7. ... challenged in a positive way.</div>
                <div class="rating-options">
                    <input type="radio" name="eustress_item_02" id="e2_1" value="1" required><label for="e2_1">1</label>
                    <input type="radio" name="eustress_item_02" id="e2_2" value="2"><label for="e2_2">2</label>
                    <input type="radio" name="eustress_item_02" id="e2_3" value="3"><label for="e2_3">3</label>
                    <input type="radio" name="eustress_item_02" id="e2_4" value="4"><label for="e2_4">4</label>
                    <input type="radio" name="eustress_item_02" id="e2_5" value="5"><label for="e2_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">8. ... bursting with energy.</div>
                <div class="rating-options">
                    <input type="radio" name="eustress_item_03" id="e3_1" value="1" required><label for="e3_1">1</label>
                    <input type="radio" name="eustress_item_03" id="e3_2" value="2"><label for="e3_2">2</label>
                    <input type="radio" name="eustress_item_03" id="e3_3" value="3"><label for="e3_3">3</label>
                    <input type="radio" name="eustress_item_03" id="e3_4" value="4"><label for="e3_4">4</label>
                    <input type="radio" name="eustress_item_03" id="e3_5" value="5"><label for="e3_5">5</label>
                </div>
            </div>

            <div class="question-box">
                <div class="question-text">9. ... enthusiastic.</div>
                <div class="rating-options">
                    <input type="radio" name="eustress_item_04" id="e4_1" value="1" required><label for="e4_1">1</label>
                    <input type="radio" name="eustress_item_04" id="e4_2" value="2"><label for="e4_2">2</label>
                    <input type="radio" name="eustress_item_04" id="e4_3" value="3"><label for="e4_3">3</label>
                    <input type="radio" name="eustress_item_04" id="e4_4" value="4"><label for="e4_4">4</label>
                    <input type="radio" name="eustress_item_04" id="e4_5" value="5"><label for="e4_5">5</label>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                Submit Answers & Complete Experiment
            </button>
        </form>
    </div>

    <!-- POPUP JAVASCRIPT -->
    <script>
        // Fade the popup in 300ms after the page loads
        window.onload = function() {
            setTimeout(function() {
                document.getElementById('instructionPopup').classList.add('active');
            }, 300); 
        };

        // Close the popup when the button is clicked
        function closePopup() {
            document.getElementById('instructionPopup').classList.remove('active');
        }
    </script>

</body>
</html>