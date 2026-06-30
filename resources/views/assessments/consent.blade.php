@extends('layouts.guest-test')

@section('title', 'Study Information — StressLab')

@section('styles')
<style>
    .consent-container {
        max-width: 700px;
        margin: 4rem auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .consent-header {
        background: #9b1c4a;
        color: white;
        padding: 1.5rem 2rem;
        text-align: center;
    }
    .consent-header h2 { margin: 0; font-size: 1.4rem; font-weight: 700; }
    .consent-body {
        padding: 2.5rem;
        color: #374151;
        line-height: 1.7;
        font-size: 0.95rem;
    }
    .consent-body h3 {
        color: #111827;
        font-size: 1.1rem;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .consent-body h3:first-child { margin-top: 0; }
    .consent-body ul { padding-left: 1.5rem; margin-bottom: 1.5rem; }
    .action-row {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .btn-proceed {
        flex: 1;
        text-align: center;
        background: #9b1c4a;
        color: white;
        text-decoration: none;
        padding: 0.85rem;
        border-radius: 8px;
        font-weight: 700;
        transition: 0.2s;
    }
    .btn-proceed:hover { background: #7a153e; }
    .btn-cancel {
        flex: 1;
        text-align: center;
        background: #f3f4f6;
        color: #4b5563;
        text-decoration: none;
        padding: 0.85rem;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.2s;
    }
    .btn-cancel:hover { background: #e5e7eb; }
</style>
@endsection

@section('content')
<div class="consent-container">
    <div class="consent-header">
        <h2>Study Information</h2>
    </div>

    <div class="consent-body">
        <h3>Welcome, {{ $session->participant->name }}</h3>
        <p>You have successfully connected to test session <strong>#{{ $session->access_code }}</strong>.</p>
        
        <h3>What to Expect</h3>
        <p>During this session, you will be asked to:</p>
        <ul>
            <li>Answer a brief pre-test questionnaire about your current stress levels.</li>
            <li>Complete a computerized cognitive task (Stroop, MIST, or TSST).</li>
            <li>Answer a final post-test questionnaire.</li>
        </ul>

        <h3>Privacy & Data</h3>
        <p>Your responses and any data collected during the test will be kept strictly confidential and will only be used for research purposes by the organizing institution.</p>

        <div class="action-row">
            <a href="{{ auth()->check() ? '/test-sessions' : '/' }}" class="btn-cancel">Cancel</a>
            <a href="/test-sessions/{{ $session->id }}/pre-test" class="btn-proceed">I Understand & Continue</a>
        </div>
    </div>
</div>
@endsection