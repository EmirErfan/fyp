@extends('layouts.app')

@section('title', 'Schedule Details — StressLab')

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

    /* ── SCHEDULE HERO CARD ── */
    .hero-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    /* Pink left accent bar */
    .hero-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--pink), var(--pink-deep));
        border-radius: 16px 0 0 16px;
    }
    .hero-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .hero-icon {
        width: 52px; height: 52px;
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        color: var(--pink);
        flex-shrink: 0;
    }
    .hero-id {
        font-size: 0.72rem;
        font-weight: 700;
        font-family: var(--mono);
        color: var(--pink);
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        padding: 0.18rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 0.4rem;
    }
    .hero-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .hero-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .hero-meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
    .hero-meta-item i { color: var(--text-muted); font-size: 0.75rem; }
    .meta-sep { color: var(--gray-3); font-size: 0.7rem; }

    .btn-assign {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--pink);
        color: #fff;
        padding: 0.55rem 1.1rem;
        border-radius: 9px;
        font-size: 0.83rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 3px 12px rgba(200,24,74,0.28);
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-assign:hover {
        background: var(--pink-deep);
        box-shadow: 0 5px 18px rgba(200,24,74,0.38);
        transform: translateY(-1px);
    }

    /* ── TABLE CARD ── */
    .table-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        overflow: hidden;
    }
    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--gray-2);
    }
    .table-card-title {
        font-size: 0.83rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .record-count {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-muted);
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        padding: 0.18rem 0.6rem;
        border-radius: 100px;
        font-family: var(--mono);
    }

    /* ── TABLE ── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: var(--gray-1); }
    .data-table th {
        padding: 0.7rem 1.2rem;
        font-size: 0.67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        text-align: left;
        white-space: nowrap;
    }
    .data-table td {
        padding: 0.95rem 1.2rem;
        font-size: 0.83rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--gray-1);
        vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr { transition: background 0.12s; }
    .data-table tbody tr:hover { background: #fdf8f9; }

    .pid {
        display: inline-flex;
        align-items: center;
        font-family: var(--mono);
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--pink-deep);
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
    }
    .participant-name { font-weight: 600; color: var(--text-primary); }
    .test-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.65rem;
        border-radius: 6px;
        background: var(--gray-1);
        color: var(--text-secondary);
        border: 1px solid var(--gray-2);
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.22rem 0.65rem;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .status.complete { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .status.complete .status-dot { background: #15803d; }
    .status.in-progress { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .status.in-progress .status-dot { background: #f59e0b; animation: blink 1.5s ease-in-out infinite; }
    @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

    .action-group { display: flex; align-items: center; gap: 0.4rem; }
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
        white-space: nowrap;
    }
    .btn-view { background: var(--gray-1); color: var(--text-secondary); border: 1px solid var(--gray-2); }
    .btn-view:hover { background: var(--white); border-color: var(--gray-3); color: var(--text-primary); }
    .btn-start { background: var(--pink); color: #fff; border: 1px solid transparent; box-shadow: 0 2px 8px rgba(200,24,74,0.2); }
    .btn-start:hover { background: var(--pink-deep); box-shadow: 0 3px 12px rgba(200,24,74,0.3); }

    /* Empty state */
    .empty-state { padding: 3.5rem 1.5rem; text-align: center; color: var(--text-muted); }
    .empty-icon {
        width: 50px; height: 50px;
        background: var(--pink-lt);
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: var(--pink);
        margin: 0 auto 0.85rem;
    }
    .empty-state h3 { font-size: 0.92rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.3rem; }
    .empty-state p { font-size: 0.8rem; }

    @media (max-width: 640px) {
        .hero-card { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('content')

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1>Schedule <em>Details</em></h1>
            <p>Viewing slot #S-{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }} and its assigned participants.</p>
        </div>
        <a href="/schedules" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Schedules</a>
    </div>

    <!-- HERO CARD -->
    <div class="hero-card">
        <div class="hero-left">
            <div class="hero-icon"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="hero-id">#S-{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }}</div>
                <div class="hero-title">{{ \Carbon\Carbon::parse($schedule->date)->format('l, d F Y') }}</div>
                <div class="hero-meta">
                    <span class="hero-meta-item">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}
                    </span>
                    <span class="meta-sep">·</span>
                    <span class="hero-meta-item">
                        <i class="fas fa-users"></i>
                        {{ $schedule->testSessions->count() }} participant{{ $schedule->testSessions->count() !== 1 ? 's' : '' }} assigned
                    </span>
                </div>
            </div>
        </div>
        <a href="/test-sessions/create" class="btn-assign">
            <i class="fas fa-user-plus"></i> Assign Participant
        </a>
    </div>

    <!-- PARTICIPANTS TABLE -->
    <div class="table-card">
        <div class="table-card-header">
            <span class="table-card-title">Assigned Participants</span>
            <span class="record-count">{{ $schedule->testSessions->count() }} records</span>
        </div>

        @if($schedule->testSessions->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
                <h3>No participants assigned</h3>
                <p>No participants have been assigned to this time slot yet.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Test Protocol</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedule->testSessions as $session)
                        <tr>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:0.3rem;">
                                    <span class="pid">#P-{{ str_pad($session->participant->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <span class="participant-name">{{ $session->participant->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="test-badge">{{ $session->test->test_type }}</span>
                            </td>
                            <td>
                                @if($session->result)
                                    <span class="status complete">
                                        <span class="status-dot"></span> Complete
                                    </span>
                                @else
                                    <span class="status in-progress">
                                        <span class="status-dot"></span> In Progress
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    @if(!$session->result)
                                        <a href="/test-sessions/{{ $session->id }}/consent" class="btn-action btn-start">
                                            <i class="fas fa-play"></i> Start Test
                                        </a>
                                    @else
                                        <a href="/test-sessions/{{ $session->id }}" class="btn-action btn-view">
                                            <i class="fas fa-file-lines"></i> View Report
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection