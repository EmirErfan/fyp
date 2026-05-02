@extends('layouts.app')

@section('title', 'Dashboard — Stress System')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header {
        margin-bottom: 2rem;
    }
    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0d0d0d;
        letter-spacing: -0.04em;
        line-height: 1.1;
        margin-bottom: 0.35rem;
    }
    .page-header p {
        font-size: 0.875rem;
        color: #888;
        font-weight: 400;
    }

    /* ── STAT CARDS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e2e2;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .stat-card:hover {
        border-color: #bbb;
        box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        transform: translateY(-2px);
    }
    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #888;
    }
    .stat-icon {
        width: 36px; height: 36px;
        background: #f5f5f5;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0d0d0d;
        letter-spacing: -0.04em;
        line-height: 1;
    }
    .stat-sub {
        font-size: 0.75rem;
        color: #888;
        font-weight: 400;
    }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 100px;
        background: #e8f0fe;
        color: #1a73e8;
    }

    /* ── SECTION TITLE ── */
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0d0d0d;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }

    /* ── METHOD CARDS ── */
    .methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .method-card {
        background: #ffffff;
        border: 1px solid #e2e2e2;
        border-radius: 16px;
        padding: 1.5rem;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .method-card:hover {
        border-color: #bbb;
        box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        transform: translateY(-3px);
    }
    .mc-num {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #bbb;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }
    .mc-icon {
        width: 42px; height: 42px;
        background: #f5f5f5;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
    .mc-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0d0d0d;
        margin-bottom: 0.4rem;
        letter-spacing: -0.01em;
    }
    .mc-desc {
        font-size: 0.8rem;
        line-height: 1.6;
        color: #555;
    }
    .mc-tag {
        display: inline-block;
        margin-top: 1rem;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #1a73e8;
        background: #e8f0fe;
        padding: 0.2rem 0.55rem;
        border-radius: 100px;
    }
    .mc-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #0d0d0d;
        background: #f5f5f5;
        padding: 0.45rem 0.9rem;
        border-radius: 100px;
        transition: background 0.15s;
    }
    .method-card:hover .mc-action { background: #e2e2e2; }

    /* ── BOTTOM ROW ── */
    .bottom-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .panel-card {
        background: #ffffff;
        border: 1px solid #e2e2e2;
        border-radius: 16px;
        padding: 1.5rem;
    }
    .panel-card-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #0d0d0d;
        letter-spacing: -0.01em;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f5f5f5;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }
    .qa-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        color: #333;
        border: 1px solid #e2e2e2;
        transition: background 0.15s, border-color 0.15s, transform 0.15s;
    }
    .qa-btn:hover {
        background: #f5f5f5;
        border-color: #bbb;
        transform: translateX(3px);
    }
    .qa-icon {
        width: 32px; height: 32px;
        background: #f5f5f5;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .qa-btn:hover .qa-icon { background: #e2e2e2; }

    /* Info Panel */
    .info-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 0.835rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-key { color: #888; font-weight: 500; }
    .info-val { color: #0d0d0d; font-weight: 600; }
    .badge-blue {
        background: #e8f0fe;
        color: #1a73e8;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.18rem 0.55rem;
        border-radius: 100px;
    }
    .badge-green {
        background: #e6f4ea;
        color: #188038;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.18rem 0.55rem;
        border-radius: 100px;
    }

    @media (max-width: 960px) {
        .methods-grid { grid-template-columns: 1fr; }
        .bottom-row   { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .page-header h1 { font-size: 1.4rem; }
    }
</style>
@endsection

@section('content')

    <!-- PAGE HEADER -->
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Welcome back. Here's an overview of your research platform.</p>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Participants</span>
                <div class="stat-icon"><i class="fas fa-users" style="color: #555;"></i></div>
            </div>
            <div class="stat-value">{{ $totalParticipants }}</div>
            <div class="stat-sub">Total enrolled participants</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Test Sessions</span>
                <div class="stat-icon"><i class="fas fa-vial" style="color: #555;"></i></div>
            </div>
            <div class="stat-value">{{ $totalSessions }}</div>
            <div class="stat-sub">All-time sessions run</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Methods</span>
                <div class="stat-icon"><i class="fas fa-clipboard-list" style="color: #555;"></i></div>
            </div>
            <div class="stat-value">3</div>
            <div class="stat-sub">Validated stress protocols</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Status</span>
                <div class="stat-icon"><i class="fas fa-check-circle" style="color: #1a73e8;"></i></div>
            </div>
            <div style="display:flex;align-items:baseline;gap:0.5rem;">
                <div class="stat-value" style="font-size:1.6rem;">Active</div>
            </div>
            <span class="stat-badge">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                System Online
            </span>
        </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="bottom-row">

        <!-- QUICK ACTIONS -->
        <div class="panel-card">
            <div class="panel-card-title">Quick Actions</div>
            <div class="quick-actions">
                <a href="/participants/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-user-plus" style="color: #555;"></i></div>
                    Add New Participant
                </a>
                <a href="/test-sessions/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-vial" style="color: #555;"></i></div>
                    Create Test Session
                </a>
                <a href="/schedules/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-calendar-alt" style="color: #555;"></i></div>
                    Schedule a Session
                </a>
                <a href="/participants" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-users" style="color: #555;"></i></div>
                    View All Participants
                </a>
            </div>
        </div>

        <!-- USER PROFILE -->
        <div class="panel-card" style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding: 2.5rem 1.5rem;">
            <div style="width: 64px; height: 64px; background: #e8f0fe; color: #1a73e8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">
                {{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0d0d0d; margin-bottom: 0.35rem;">{{ auth()->user()->name ?? 'Researcher Profile' }}</h3>
            <span class="badge-blue" style="margin-bottom: 1.25rem;">Authorized Access</span>
            <p style="font-size: 0.85rem; color: #888; line-height: 1.6; margin: 0;">
                Welcome to the Centralized Stress System.<br>Your research data is securely logged and ready for export.
            </p>
        </div>

    </div>

@endsection