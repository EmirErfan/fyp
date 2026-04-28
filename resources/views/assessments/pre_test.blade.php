<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Test Assessment</title>
    <style>
        body { background-color: #f8f9fa; margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; }
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
    </style>
</head>
<body>

<div class="assessment-container">
    
    <div class="page-header">
        <h1>Pre-Test Questionnaire</h1>
        <h4>Participant: {{ $testSession->participant->name ?? 'Unknown Participant' }}</h4>
    </div>

    <div class="instruction-text">
        Right now, I am feeling...<br>
        <span style="font-size: 14px; font-weight: normal;">(Please rate on a scale of 1: totally disagree to 5: totally agree)</span>
    </div>

    @if ($errors->any())
        <div class="error-alert">
            Please answer all 9 questions before submitting.
        </div>
    @endif

    <form action="/test-sessions/{{ $testSession->id }}/pre-test" method="POST">
        @csrf

        <div class="section-title">Distress Items</div>

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
            Submit Pre-Test & Proceed to Task
        </button>
    </form>
</div>

</body>
</html>