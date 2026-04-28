@extends('layouts.app')

@section('title', 'Informed Consent - Stress System')

@section('styles')
<style>
    .consent-wrapper { display: flex; justify-content: center; padding: 20px 0; }
    .consent-card { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #eaeaea; padding: 40px; max-width: 800px; width: 100%; }
    
    .header-text { text-align: center; margin-bottom: 30px; }
    .header-text h2 { color: #333; font-size: 26px; margin-bottom: 10px; }
    .header-text p { color: #666; font-size: 15px; }

    /* The Scrollable Document Area */
    .document-box { background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; padding: 25px; height: 350px; overflow-y: scroll; margin-bottom: 30px; font-size: 14px; color: #444; line-height: 1.6; }
    .document-box h4 { margin-top: 20px; margin-bottom: 10px; color: #222; }
    .document-box h4:first-child { margin-top: 0; }

    /* Checkbox & Action Area */
    .action-area { background: #eef2ff; padding: 20px; border-radius: 8px; border-left: 4px solid #4b6bfb; display: flex; flex-direction: column; gap: 15px; }
    
    .checkbox-label { display: flex; align-items: flex-start; gap: 10px; font-size: 15px; font-weight: 600; color: #333; cursor: pointer; }
    .checkbox-label input[type="checkbox"] { width: 20px; height: 20px; margin-top: 2px; cursor: pointer;}

    /* Button Styling (Disabled by default) */
    .btn-proceed { background-color: #4b6bfb; color: white; border: none; padding: 15px 20px; border-radius: 6px; font-size: 16px; font-weight: bold; text-align: center; text-decoration: none; transition: 0.3s; opacity: 0.5; pointer-events: none; /* Locks the button */ }
    .btn-proceed.active { opacity: 1; pointer-events: auto; /* Unlocks the button */ }
    .btn-proceed.active:hover { background-color: #3a56d4; }
</style>
@endsection

@section('content')
<div class="consent-wrapper">
    <div class="consent-card">
        <div class="header-text">
            <h2>Participant Informed Consent</h2>
            <p>Protocol: <strong>{{ $session->test->test_type }}</strong> | Participant ID: <strong>#P-{{ str_pad($session->participant->id, 3, '0', STR_PAD_LEFT) }}</strong></p>
        </div>

        <div class="document-box">
            <h4>1. Purpose of the Study</h4>
            <p>You are invited to participate in a research study investigating physiological and psychological responses using the {{ $session->test->test_type }} protocol. The purpose of this study is to observe cognitive performance and reaction times under specific testing conditions.</p>

            <h4>2. Procedures</h4>
            <p>If you agree to participate, you will be asked to complete a brief pre-test questionnaire, followed by the computerized {{ $session->test->test_type }} task. Finally, you will complete a post-test questionnaire. Your facial expressions and screen activity may be recorded for analytical purposes.</p>

            <h4>3. Risks and Discomforts</h4>
            <p>The tasks are designed to be challenging and may induce mild, temporary frustration or cognitive fatigue. This is a normal part of the experimental protocol. There are no known physical risks associated with this study.</p>

            <h4>4. Confidentiality</h4>
            <p>All data collected, including video recordings and performance metrics, will be kept strictly confidential. Your data will be anonymized and assigned a Participant ID. Only authorized researchers will have access to the raw files.</p>

            <h4>5. Voluntary Participation & Withdrawal</h4>
            <p>Your participation is entirely voluntary. You may choose to withdraw from the study at any time without penalty or loss of benefits. If you wish to stop, simply inform the researcher or close the browser.</p>
        </div>

        <div class="action-area">
            <label class="checkbox-label">
                <input type="checkbox" id="consent-checkbox" onchange="toggleProceedButton()">
                I acknowledge that I have read and understand the information provided above. I voluntarily agree to participate in this study and consent to the data collection protocols.
            </label>

            <a href="/test-sessions/{{ $session->id }}/pre-test" id="proceed-button" class="btn-proceed">
                <i class="fas fa-check-circle"></i> Proceed to Pre-Test
            </a>
        </div>
    </div>
</div>

<script>
    function toggleProceedButton() {
        const checkbox = document.getElementById('consent-checkbox');
        const button = document.getElementById('proceed-button');

        if (checkbox.checked) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    }
</script>
@endsection