@extends('layouts.guest-test')

@section('title', 'Tutorial — StressLab')

@section('styles')
<style>
    .tutorial-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        padding: 40px;
    }
    .tutorial-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .tutorial-header h2 {
        font-size: 28px;
        color: #212529;
        margin-bottom: 10px;
    }
    .tutorial-header p {
        font-size: 16px;
        color: #6c757d;
    }
    
    .stepper {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border-left: 5px solid #9b1c4a;
    }
    
    .step-number {
        background: #9b1c4a;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .step-content h4 {
        margin: 0 0 8px 0;
        font-size: 18px;
        color: #212529;
    }
    
    .step-content p {
        margin: 0;
        font-size: 15px;
        color: #555;
        line-height: 1.6;
    }

    .test-specific {
        background: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
        border-left: 4px solid #0d6efd;
    }
    .test-specific strong { color: #0d6efd; }
    
    .action-row {
        margin-top: 40px;
        text-align: center;
    }
    .btn-proceed {
        display: inline-block;
        background: #9b1c4a;
        color: white;
        text-decoration: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 18px;
        transition: 0.2s;
        box-shadow: 0 4px 15px rgba(155, 28, 74, 0.3);
    }
    .btn-proceed:hover {
        background: #7a153e;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="tutorial-container">
    <div class="tutorial-header">
        <h2>What to Expect</h2>
        <p>Please review the steps below so you know exactly what will happen during this session.</p>
    </div>

    <div class="stepper">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <h4>Camera Check</h4>
                <p>You will be asked to allow webcam access. Our system will briefly scan to ensure your face is clearly visible. <strong>No video is recorded during this step.</strong></p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <h4>Pre-Test Questionnaire</h4>
                <p>You will answer a short questionnaire about how you are feeling right at this moment.</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <h4>The Cognitive Task</h4>
                <p>You will complete a computerized cognitive task.</p>
                <div class="test-specific">
                    @if(str_contains(strtolower($session->test->test_type ?? ''), 'stroop'))
                        <strong>Stroop Test:</strong> You will see color words (like "RED" or "BLUE") printed in different ink colors. Your task is to identify the <strong>INK COLOR</strong> of the text as fast as possible, while ignoring the written word itself.
                    @elseif(str_contains(strtolower($session->test->test_type ?? ''), 'mist'))
                        <strong>MIST (Math Task):</strong> You will be presented with a series of arithmetic problems. You must solve them quickly and accurately within a strict time limit.
                    @elseif(str_contains(strtolower($session->test->test_type ?? ''), 'tsst'))
                        <strong>TSST:</strong> You will be asked to prepare and deliver a short speech, followed by a challenging mental arithmetic task, all while being recorded.
                    @else
                        Follow the on-screen instructions closely during the test.
                    @endif
                </div>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">4</div>
            <div class="step-content">
                <h4>Post-Test Questionnaire</h4>
                <p>Finally, you will answer another brief questionnaire to evaluate how you feel after completing the task.</p>
            </div>
        </div>
    </div>

    <div class="action-row">
        <a href="/test-sessions/{{ $session->id }}/camera-check" class="btn-proceed">I'm Ready. Start Camera Check! &rarr;</a>
    </div>
</div>
@endsection
