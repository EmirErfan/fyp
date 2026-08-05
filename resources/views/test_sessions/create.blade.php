@extends('layouts.app')

@section('title', 'New Test Session — StressLab')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        margin-bottom: 0.3rem;
    }
    .page-header h1 em { font-style: normal; color: var(--pink); }
    .page-header p { font-size: 0.83rem; color: var(--text-muted); }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1rem;
        border-radius: 9px;
        background: var(--white);
        border: 1px solid var(--gray-2);
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s, background 0.15s;
        white-space: nowrap;
    }
    .btn-back:hover { border-color: var(--pink-soft); background: var(--pink-lt); color: var(--pink); }

    /* ── LAYOUT ── */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        overflow: hidden;
    }
    .form-card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--gray-2);
        background: var(--gray-1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-card-icon {
        width: 32px; height: 32px;
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.88rem;
        color: var(--pink);
        flex-shrink: 0;
    }
    .form-card-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }
    .form-card-subtitle {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.1rem;
    }
    .form-body { padding: 1.5rem; }

    /* ── FORM GROUPS ── */
    .form-group { margin-bottom: 1.35rem; }
    .form-group:last-of-type { margin-bottom: 0; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: 0.01em;
    }
    .form-label i {
        width: 14px;
        font-size: 0.78rem;
        color: var(--pink);
        text-align: center;
    }
    .required-dot {
        width: 5px; height: 5px;
        background: var(--pink);
        border-radius: 50%;
        display: inline-block;
        margin-left: 2px;
        flex-shrink: 0;
    }

    .form-select {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1px solid var(--gray-2);
        border-radius: 9px;
        font-size: 0.85rem;
        font-family: var(--sans);
        color: var(--text-primary);
        background: var(--white);
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239898a8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.9rem center;
        padding-right: 2.25rem;
    }
    .form-select:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px rgba(200,24,74,0.1);
    }
    .form-select:invalid,
    .form-select option[value=""] { color: var(--text-muted); }

    .field-hint {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.73rem;
        color: var(--text-muted);
        margin-top: 0.4rem;
    }
    .field-hint i { font-size: 0.7rem; }

    /* Divider */
    .form-divider {
        height: 1px;
        background: var(--gray-2);
        margin: 1.35rem 0;
    }

    /* Submit button */
    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.8rem;
        background: var(--pink);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: var(--sans);
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(200,24,74,0.28);
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
        letter-spacing: 0.01em;
        margin-top: 1.5rem;
    }
    .btn-submit:hover {
        background: var(--pink-deep);
        box-shadow: 0 6px 20px rgba(200,24,74,0.38);
        transform: translateY(-1px);
    }
    .btn-submit:active { transform: translateY(0); }

    /* ── SIDE INFO CARD ── */
    .info-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: calc(var(--navbar-h) + 1.5rem);
    }
    .info-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-2);
        background: var(--pink-lt);
    }
    .info-card-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--pink-deep);
        letter-spacing: -0.01em;
    }
    .info-card-body { padding: 1.25rem; }

    .info-step {
        display: flex;
        gap: 0.85rem;
        margin-bottom: 1.1rem;
    }
    .info-step:last-child { margin-bottom: 0; }
    .step-num {
        width: 24px; height: 24px;
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.68rem;
        font-weight: 800;
        color: var(--pink);
        flex-shrink: 0;
        margin-top: 1px;
    }
    .step-content h4 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.2rem;
    }
    .step-content p {
        font-size: 0.75rem;
        color: var(--text-muted);
        line-height: 1.55;
    }

    .info-divider {
        height: 1px;
        background: var(--gray-2);
        margin: 1.1rem 0;
    }

    .info-tip {
        display: flex;
        gap: 0.6rem;
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        border-radius: 9px;
        padding: 0.85rem 1rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.55;
    }
    .info-tip i { color: var(--pink); margin-top: 1px; flex-shrink: 0; font-size: 0.8rem; }

    /* Test Protocol Cards */
    .test-protocol-cards { display: flex; flex-direction: column; gap: 0.75rem; }
    .test-card { cursor: pointer; display: block; margin: 0; }
    .test-card input { display: none; }
    .test-card-inner {
        display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem;
        border: 2px solid var(--gray-2); border-radius: 12px;
        background: var(--white); transition: all 0.2s ease;
    }
    .test-icon {
        width: 42px; height: 42px; border-radius: 10px;
        background: var(--gray-1); color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; transition: all 0.2s ease;
    }
    .test-info { display: flex; flex-direction: column; }
    .test-info strong { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.15rem; }
    .test-info span { font-size: 0.8rem; color: var(--text-muted); }

    .test-card input:checked + .test-card-inner { border-color: var(--pink); background: var(--pink-lt); box-shadow: 0 4px 12px rgba(200,24,74,0.1); }
    .test-card input:checked + .test-card-inner .test-icon { background: var(--pink); color: white; }

    /* TomSelect Overrides */
    .ts-wrapper.form-select { padding: 0; border: none; background: none; }
    .ts-control {
        padding: 0.7rem 1rem !important; border: 1px solid var(--gray-2) !important;
        border-radius: 9px !important; font-size: 0.85rem !important;
        font-family: var(--sans) !important; color: var(--text-primary) !important;
        background: var(--white) !important; box-shadow: none !important;
    }
    .ts-wrapper.focus .ts-control { border-color: var(--pink) !important; box-shadow: 0 0 0 3px rgba(200,24,74,0.1) !important; }

    @media (max-width: 860px) {
        .create-layout { grid-template-columns: 1fr; }
        .info-card { position: static; }
    }
</style>
@endsection

@section('content')

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1>New <em>Session</em></h1>
            <p>Configure and start a new stress induction test session.</p>
        </div>
        <a href="/test-sessions" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Sessions</a>
    </div>

    <div class="create-layout">

        <!-- FORM -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-vial"></i></div>
                <div>
                    <div class="form-card-title">Session Configuration</div>
                    <div class="form-card-subtitle">All fields are required to proceed</div>
                </div>
            </div>
            <div class="form-body">
                <form action="/test-sessions" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="test_schedule_id">
                            <i class="fas fa-calendar-alt"></i>
                            Schedule Slot
                            <span class="required-dot"></span>
                        </label>
                        @if($schedules->isEmpty())
                            <div style="background: var(--pink-lt); border: 1px dashed var(--pink-soft); border-radius: 9px; padding: 1.5rem; text-align: center;">
                                <p style="font-size: 0.85rem; color: var(--pink-deep); margin-bottom: 0.8rem; font-weight: 500;">No upcoming schedule slots available.</p>
                                <a href="/schedules/create" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.2rem; background: var(--pink); color: white; text-decoration: none; border-radius: 8px; font-size: 0.8rem; font-weight: 700; transition: background 0.15s;">
                                    <i class="fas fa-plus"></i> Create Schedule
                                </a>
                            </div>
                        @else
                            <select name="test_schedule_id" id="test_schedule_id" class="form-select" required>
                                <option value="" disabled selected>— Choose a date &amp; time slot —</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }} at {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="field-hint"><i class="fas fa-circle-info"></i> Only available upcoming slots are shown.</div>
                        @endif
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-group">
                        <label class="form-label" for="participant_id">
                            <i class="fas fa-user"></i>
                            Participant
                            <span class="required-dot"></span>
                        </label>
                        <select name="participant_id" id="participant_id" class="form-select" required>
                            <option value="" disabled selected>— Choose a participant —</option>
                            @foreach($participants as $participant)
                                <option value="{{ $participant->id }}">
                                    #P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp; {{ $participant->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint"><i class="fas fa-circle-info"></i> Participants are linked to their enrolled profile.</div>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-clipboard-list"></i>
                            Test Protocol
                            <span class="required-dot"></span>
                        </label>
                        <div class="test-protocol-cards">
                            @foreach($tests as $test)
                                <label class="test-card">
                                    <input type="radio" name="test_id" value="{{ $test->id }}" required>
                                    <div class="test-card-inner">
                                        <div class="test-icon">
                                            @if($test->test_type === 'TSST')
                                                <i class="fas fa-users"></i>
                                            @elseif($test->test_type === 'Stroop')
                                                <i class="fas fa-brain"></i>
                                            @elseif($test->test_type === 'MIST')
                                                <i class="fas fa-calculator"></i>
                                            @else
                                                <i class="fas fa-vial"></i>
                                            @endif
                                        </div>
                                        <div class="test-info">
                                            <strong>{{ $test->test_type }}</strong>
                                            <span>
                                                @if($test->test_type === 'TSST') Trier Social Stress Test
                                                @elseif($test->test_type === 'Stroop') Stroop Color &amp; Word Test
                                                @elseif($test->test_type === 'MIST') Montreal Imaging Stress Task
                                                @else Standard test protocol @endif
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-play"></i> Start Session
                    </button>
                </form>
            </div>
        </div>

        <!-- SIDE INFO -->
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-title"><i class="fas fa-circle-info" style="margin-right:0.4rem;"></i>Session Setup Guide</div>
            </div>
            <div class="info-card-body">
                <div class="info-step">
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <h4>Pick a Schedule Slot</h4>
                        <p>Select a pre-created date and time for this session. Slots must be created in Schedules first.</p>
                    </div>
                </div>
                <div class="info-step">
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <h4>Select the Participant</h4>
                        <p>Choose from enrolled participants. Make sure their profile is complete before proceeding.</p>
                    </div>
                </div>
                <div class="info-step">
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <h4>Choose the Test Protocol</h4>
                        <p>Select the validated stress induction method suitable for this session's objectives.</p>
                    </div>
                </div>

                <div class="info-divider"></div>

                <div class="info-tip">
                    <i class="fas fa-lightbulb"></i>
                    After submission, the participant will be guided through the consent form before the test begins.
                </div>
            </div>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#participant_id', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "— Type to search participant —"
            });
        });
    </script>
@endsection