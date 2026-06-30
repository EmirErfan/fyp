@extends('layouts.app')

@section('title', 'Session Report — StressLab')

@section('styles')
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

    /* ── REPORT GRID ── */
    .report-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .report-grid.full { grid-template-columns: 1fr; }

    /* ── SECTION CARD ── */
    .section-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .section-card:hover { box-shadow: 0 6px 24px rgba(200,24,74,0.07); }

    .section-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.35rem;
        border-bottom: 1px solid var(--gray-2);
        background: var(--gray-1);
    }
    .section-num {
        width: 26px; height: 26px;
        background: var(--pink);
        color: #fff;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .section-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }
    .section-body { padding: 1.35rem; }

    /* ── DATA ROWS ── */
    .data-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.65rem 0;
        border-bottom: 1px solid var(--gray-1);
        font-size: 0.83rem;
    }
    .data-row:last-child { border-bottom: none; padding-bottom: 0; }
    .data-key {
        color: var(--text-muted);
        font-weight: 500;
        flex-shrink: 0;
    }
    .data-val {
        font-weight: 600;
        color: var(--text-primary);
        text-align: right;
    }

    /* ── METRIC CARDS ── */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.35rem;
    }
    .metric-card {
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        border-radius: 11px;
        padding: 1rem;
        text-align: center;
    }
    .metric-label {
        font-size: 0.67rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .metric-value {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--text-primary);
        font-family: var(--mono);
        line-height: 1;
    }
    .metric-unit {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }
    .metric-card.highlight-card {
        background: var(--pink-lt);
        border-color: var(--pink-soft);
    }
    .metric-card.highlight-card .metric-value { color: var(--pink); }
    .metric-card.highlight-card .metric-label { color: var(--pink); }

    /* Video section */
    .video-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .video-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--gray-2);
    }
    .video-wrapper {
        background: #0d0d0d;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--gray-2);
    }
    .video-wrapper video { display: block; width: 100%; }
    .video-caption {
        text-align: center;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.6rem;
    }
    .video-empty {
        background: var(--gray-1);
        border: 1px dashed var(--gray-3);
        border-radius: 10px;
        padding: 2rem 1rem;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.82rem;
    }
    .video-empty i { font-size: 1.5rem; margin-bottom: 0.5rem; display: block; color: var(--gray-3); }

    /* No result alert */
    .no-result {
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        text-align: center;
        color: var(--pink-deep);
        font-size: 0.83rem;
        font-weight: 600;
    }

    /* ── ASSESSMENT TABLE ── */
    .assess-table { width: 100%; border-collapse: collapse; }
    .assess-table thead tr { background: var(--gray-1); }
    .assess-table th {
        padding: 0.65rem 1rem;
        font-size: 0.67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        text-align: left;
        border-bottom: 1px solid var(--gray-2);
    }
    .assess-table td {
        padding: 0.8rem 1rem;
        font-size: 0.82rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--gray-1);
    }
    .assess-table tbody tr:last-child td { border-bottom: none; }
    .assess-table tbody tr:hover { background: #fdf8f9; }
    .assess-table td:first-child { font-weight: 500; color: var(--text-primary); }
    .score-pill {
        display: inline-block;
        min-width: 36px;
        text-align: center;
        padding: 0.2rem 0.55rem;
        border-radius: 7px;
        font-weight: 700;
        font-family: var(--mono);
        font-size: 0.82rem;
    }
    .score-pre { background: var(--gray-1); color: var(--text-secondary); border: 1px solid var(--gray-2); }
    .score-post { background: var(--pink-lt); color: var(--pink-deep); border: 1px solid var(--pink-soft); }
    .score-na { color: var(--text-muted); font-style: italic; }

    .assess-note {
        font-size: 0.72rem;
        color: var(--text-muted);
        text-align: center;
        padding: 0.75rem;
        border-top: 1px solid var(--gray-1);
    }

    /* ── LAYOUT ── */
    @media (max-width: 768px) {
        .report-grid { grid-template-columns: 1fr; }
        .metrics-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .metrics-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1>Session <em>Report</em></h1>
            <p>Final experiment output for {{ $session->participant->name }}</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            @if(!$session->result)
            <form action="{{ route('test-sessions.send-email', $session->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-back" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; cursor: pointer; {{ !$session->participant->email ? 'opacity: 0.5; cursor: not-allowed;' : '' }}" title="Send Access Code via Email" {{ !$session->participant->email ? 'disabled' : '' }}>
                    <i class="fas fa-envelope"></i> Send Email
                </button>
            </form>
            @endif
            <a href="/test-sessions" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Sessions</a>
        </div>
    </div>

    <!-- ROW 1: Info + Results -->
    <div class="report-grid" style="margin-bottom:1rem;">

        <!-- 1. Participant & Test Info -->
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-num">1</div>
                <span class="section-title">Participant &amp; Test Info</span>
            </div>
            <div class="section-body">
                <div class="data-row">
                    <span class="data-key">Name</span>
                    <span class="data-val">{{ $session->participant->name }}</span>
                </div>
                <div class="data-row">
                    <span class="data-key">Age / Gender</span>
                    <span class="data-val">{{ $session->participant->age }} yrs &nbsp;·&nbsp; {{ $session->participant->gender }}</span>
                </div>
                <div class="data-row">
                    <span class="data-key">Test Protocol</span>
                    <span class="data-val">{{ $session->test->test_type }} — {{ $session->test->test_level }}</span>
                </div>
                <div class="data-row">
                    <span class="data-key">Date</span>
                    <span class="data-val">{{ \Carbon\Carbon::parse($session->testSchedule->date)->format('d M Y') }}</span>
                </div>
                <div class="data-row">
                    <span class="data-key">Time</span>
                    <span class="data-val" style="font-family:var(--mono);">{{ \Carbon\Carbon::parse($session->testSchedule->time)->format('h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Stress Test Results -->
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-num">2</div>
                <span class="section-title">Stress Test Results</span>
            </div>
            <div class="section-body">
                @if($session->result)
                    <div class="metrics-grid">
                        <div class="metric-card">
                            <div class="metric-label">Accuracy</div>
                            <div class="metric-value">{{ $session->result->accuracy_rate }}</div>
                            <div class="metric-unit">percent %</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-label">Avg Reaction</div>
                            <div class="metric-value">{{ $session->result->average_reaction_time }}</div>
                            <div class="metric-unit">milliseconds</div>
                        </div>
                        <div class="metric-card highlight-card">
                            <div class="metric-label">Total Errors</div>
                            <div class="metric-value">{{ $session->result->total_error }}</div>
                            <div class="metric-unit">mistakes</div>
                        </div>
                    </div>

                    <div class="video-label">Recording Evidence</div>

                    @if($session->result->face_video_path)
                        <div class="video-wrapper">
                            <video controls preload="metadata">
                                <source src="{{ asset('storage/' . $session->result->face_video_path) }}" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <p class="video-caption">Synchronized participant reaction &amp; task interface recording</p>
                    @else
                        <div class="video-empty">
                            <i class="fas fa-video-slash"></i>
                            No video recording found for this session.
                        </div>
                    @endif
                @else
                    <div class="no-result">
                        <i class="fas fa-clock" style="margin-right:0.4rem;"></i>
                        No results have been entered yet for this session.
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- ROW 2: Assessment Table (full width) -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-num">3</div>
            <span class="section-title">Psychological Assessment — Pre vs Post Comparison</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="assess-table">
                <thead>
                    <tr>
                        <th>Metric (1–5 Scale)</th>
                        <th>Pre-Test Score</th>
                        <th>Post-Test Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Anxiety Level</td>
                        <td><span class="score-pill score-pre">{{ $preTest ? $preTest->distress_item_01 : '—' }}</span></td>
                        <td><span class="score-pill score-post">{{ $postTest ? $postTest->distress_item_01 : '—' }}</span></td>
                    </tr>
                    <tr>
                        <td>Feeling Overwhelmed</td>
                        <td><span class="score-pill score-pre">{{ $preTest ? $preTest->distress_item_03 : '—' }}</span></td>
                        <td><span class="score-pill score-post">{{ $postTest ? $postTest->distress_item_03 : '—' }}</span></td>
                    </tr>
                    <tr>
                        <td>Mental Fatigue</td>
                        <td><span class="score-pill score-pre">{{ $preTest ? $preTest->distress_item_05 : '—' }}</span></td>
                        <td><span class="score-pill score-post">{{ $postTest ? $postTest->distress_item_05 : '—' }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="assess-note">Showing selected key metrics for quick review. Post-test scores highlighted in pink.</div>
    </div>

@endsection