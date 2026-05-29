@extends('layouts.app')

@section('title', 'Dashboard — StressLab')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header {
        margin-bottom: 1rem; /* Reduced from 2rem */
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .page-header h1 {
        font-size: 1.5rem; /* Slightly smaller */
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        line-height: 1.15;
        margin-bottom: 0.2rem;
    }
    .page-header h1 em {
        font-style: normal;
        color: var(--pink);
    }
    .page-header p {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 400;
    }
    .header-date {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-muted);
        font-family: var(--mono);
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        white-space: nowrap;
    }

    /* ── CARD BASE ── */
    .card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 12px; /* Slightly tighter corners */
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .card:hover {
        border-color: var(--pink-soft);
        box-shadow: 0 4px 20px rgba(200,24,74,0.06);
        transform: translateY(-2px);
    }

    /* ── STAT CARDS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1rem; /* Reduced margin */
    }
    .stat-card {
        padding: 1rem 1.25rem; /* Reduced padding */
        display: flex;
        flex-direction: column;
        gap: 0.5rem; /* Tighter gap */
        position: relative;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 60px; height: 60px;
        background: radial-gradient(circle at top right, var(--card-glow, var(--pink-mid)), transparent 70%);
        pointer-events: none;
        border-radius: 0 12px 0 0;
    }
    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-label {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
    }
    .stat-icon {
        width: 28px; height: 28px;
        background: var(--icon-bg, var(--pink-lt));
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
    }
    .stat-value {
        font-size: 1.8rem; /* Smaller font to save space */
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        line-height: 1;
        font-family: var(--mono);
    }
    .stat-sub {
        font-size: 0.7rem;
        color: var(--text-muted);
    }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.15rem 0.5rem;
        border-radius: 100px;
        background: #edfaf3;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .dot-pulse { animation: dotpulse 2s ease-in-out infinite; }
    @keyframes dotpulse { 0%,100%{opacity:1;} 50%{opacity:0.35;} }

    /* ── SECTION HEADING ── */
    .section-head {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.75rem; /* Reduced */
    }
    .section-head-title {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }
    .section-head-line {
        flex: 1; height: 1px; background: var(--gray-2);
    }

    /* ── BOTTOM ROW ── */
    .bottom-row {
        display: grid;
        grid-template-columns: 2fr 1fr; /* Give quick actions more horizontal space */
        gap: 1rem;
    }
    .panel-card { padding: 1.25rem; }
    .panel-card-title {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--gray-2);
    }

    /* Quick Actions - NOW IN A 2-COLUMN GRID */
    .quick-actions { 
        display: grid; 
        grid-template-columns: 1fr 1fr; /* 2 columns to save vertical space! */
        gap: 0.5rem; 
    }
    .qa-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem; /* Tighter padding */
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-secondary);
        border: 1px solid var(--gray-2);
        background: transparent;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .qa-btn:hover {
        background: var(--pink-lt);
        border-color: var(--pink-soft);
        color: var(--pink);
    }
    .qa-icon {
        width: 26px; height: 26px;
        background: var(--gray-1);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .qa-btn:hover .qa-icon { background: var(--pink-mid); }
    
    /* Make the last odd item span across both columns so it looks neat */
    .qa-btn:last-child:nth-child(odd) {
        grid-column: span 2;
    }

    /* Profile panel */
    .profile-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 1.25rem !important; /* Compressed padding */
        background: linear-gradient(160deg, var(--white) 60%, var(--pink-lt)) !important;
    }
    .profile-avatar-lg {
        width: 50px; height: 50px; /* Smaller avatar */
        background: var(--pink);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
        box-shadow: 0 0 0 4px var(--pink-soft), 0 4px 15px rgba(200,24,74,0.2);
    }
    .profile-name-lg {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    .badge-pink {
        background: var(--pink-lt);
        color: var(--pink);
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.15rem 0.6rem;
        border-radius: 100px;
        border: 1px solid var(--pink-soft);
        margin-bottom: 0.75rem;
        display: inline-block;
    }
    .profile-desc {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.5;
        margin: 0;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 960px) {
        .bottom-row { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .quick-actions { grid-template-columns: 1fr; }
        .page-header h1 { font-size: 1.3rem; }
        .page-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1>Welcome back, <em>{{ explode(' ', auth()->user()->name ?? 'System')[0] }}</em></h1>
            <p>Here's an overview of your research platform — all systems operational.</p>
        </div>
        <span class="header-date">{{ now()->format('D, d M Y') }}</span>
    </div>

    <div class="stats-grid">
        <div class="stat-card card" style="--card-glow: rgba(219,39,119,0.08); --icon-bg: var(--pink-lt);">
            <div class="stat-top">
                <span class="stat-label">Participants</span>
                <div class="stat-icon"><i class="fas fa-users" style="color:var(--pink);"></i></div>
            </div>
            <div class="stat-value">{{ $totalParticipants ?? 5 }}</div>
            <div class="stat-sub">Total enrolled participants</div>
        </div>

        <div class="stat-card card" style="--card-glow: rgba(37,99,235,0.07); --icon-bg: #eff6ff;">
            <div class="stat-top">
                <span class="stat-label">Test Sessions</span>
                <div class="stat-icon"><i class="fas fa-vial" style="color:#2563eb;"></i></div>
            </div>
            <div class="stat-value">{{ $totalSessions ?? 12 }}</div>
            <div class="stat-sub">All-time sessions run</div>
        </div>

        <div class="stat-card card" style="--card-glow: rgba(124,58,237,0.07); --icon-bg: #f5f3ff;">
            <div class="stat-top">
                <span class="stat-label">Methods</span>
                <div class="stat-icon"><i class="fas fa-clipboard-list" style="color:#7c3aed;"></i></div>
            </div>
            <div class="stat-value">3</div>
            <div class="stat-sub">Validated stress protocols</div>
        </div>

        <div class="stat-card card" style="--card-glow: rgba(21,128,61,0.06); --icon-bg: #f0fdf4;">
            <div class="stat-top">
                <span class="stat-label">Status</span>
                <div class="stat-icon"><i class="fas fa-circle-check" style="color:#15803d;"></i></div>
            </div>
            <div class="stat-value" style="font-size:1.35rem;font-family:var(--sans);letter-spacing:-0.02em;">Online</div>
            <span class="stat-badge">
                <svg class="dot-pulse" width="6" height="6" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                System Active
            </span>
        </div>
    </div>

    <div class="section-head">
        <span class="section-head-title">Quick Access</span>
        <div class="section-head-line"></div>
    </div>

    <div class="bottom-row">

        <div class="panel-card card">
            <div class="panel-card-title">Quick Actions</div>
            <div class="quick-actions">
                <a href="/participants/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-user-plus" style="color:var(--pink);"></i></div>
                    Add New Participant
                </a>
                <a href="/test-sessions/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-vial" style="color:#2563eb;"></i></div>
                    Create Test Session
                </a>
                <a href="/schedules/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-calendar-alt" style="color:#7c3aed;"></i></div>
                    Schedule Session
                </a>
                <a href="/participants" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-users" style="color:#15803d;"></i></div>
                    View Participants
                </a>
                <a href="/export" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-file-export" style="color:var(--gray-5);"></i></div>
                    Export Data
                </a>
            </div>
        </div>

        <div class="panel-card card profile-panel">
            <div class="panel-card-title" style="width:100%;text-align:left;">Researcher Profile</div>
            <div class="profile-avatar-lg">
                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
            </div>
            <div class="profile-name-lg">{{ auth()->user()->name ?? 'System Administrator' }}</div>
            <span class="badge-pink">Authorized Access</span>
            <p class="profile-desc">
                Welcome to StressLab.<br>Your research data is securely logged and ready for export.
            </p>
        </div>

    </div>

    @if(isset($pendingRequests) && $pendingRequests->count() > 0)
        <div class="section-head" style="margin-top: 1.5rem;">
            <span class="section-head-title">Pending Access Requests</span>
            <div class="section-head-line"></div>
        </div>

        <div class="panel-card card">
            <div class="panel-card-title">Awaiting Approval ({{ $pendingRequests->count() }})</div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--gray-2);">
                            <th style="padding: 0.75rem 1rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Name</th>
                            <th style="padding: 0.75rem 1rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Email</th>
                            <th style="padding: 0.75rem 1rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests->take(5) as $request) <tr style="border-bottom: 1px solid var(--gray-1);">
                                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--text-primary);">{{ $request->name }}</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-secondary);">{{ $request->email }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    <a href="{{ url('/admin/requests/' . $request->id) }}" class="qa-btn" style="display: inline-flex; padding: 0.4rem 0.75rem; background: var(--pink-lt); color: var(--pink); border-color: var(--pink-soft); text-decoration: none; justify-content: center;">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($pendingRequests->count() > 5)
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="/admin/requests" style="font-size: 0.8rem; color: var(--pink); text-decoration: none; font-weight: 600;">View All {{ $pendingRequests->count() }} Requests &rarr;</a>
                </div>
            @endif
        </div>
    @endif
@endsection