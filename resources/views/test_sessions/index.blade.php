@extends('layouts.app')

@section('title', 'Test Sessions — StressLab')

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
        line-height: 1.15;
        margin-bottom: 0.3rem;
    }
    .page-header h1 em { font-style: normal; color: var(--pink); }
    .page-header p { font-size: 0.83rem; color: var(--text-muted); }

    .btn-new {
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
        letter-spacing: 0.01em;
        box-shadow: 0 3px 12px rgba(200,24,74,0.28);
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
        white-space: nowrap;
    }
    .btn-new:hover {
        background: var(--pink-deep);
        box-shadow: 0 5px 18px rgba(200,24,74,0.38);
        transform: translateY(-1px);
    }
    .btn-new i { font-size: 0.78rem; }

    /* ── FILTER BAR ── */
    .filter-bar {
        display: flex;
        gap: 0.6rem;
        align-items: center;
        flex-wrap: wrap;
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
        margin-bottom: 1.25rem;
    }
    .filter-input {
        flex: 1;
        min-width: 220px;
        padding: 0.55rem 0.9rem;
        border: 1px solid var(--gray-2);
        border-radius: 8px;
        font-size: 0.83rem;
        font-family: var(--sans);
        color: var(--text-primary);
        background: var(--gray-1);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-input::placeholder { color: var(--text-muted); }
    .filter-input:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px rgba(200,24,74,0.1);
        background: var(--white);
    }
    .filter-select {
        padding: 0.55rem 0.9rem;
        border: 1px solid var(--gray-2);
        border-radius: 8px;
        font-size: 0.83rem;
        font-family: var(--sans);
        color: var(--text-primary);
        background: var(--gray-1);
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .filter-select:focus { border-color: var(--pink); box-shadow: 0 0 0 3px rgba(200,24,74,0.1); }
    .filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        background: var(--pink);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 600;
        font-family: var(--sans);
        cursor: pointer;
        transition: background 0.15s;
    }
    .filter-btn:hover { background: var(--pink-deep); }
    .clear-btn {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-decoration: none;
        padding: 0.45rem 0.7rem;
        border-radius: 7px;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }
    .clear-btn:hover { background: var(--pink-lt); color: var(--pink); }

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
        letter-spacing: -0.01em;
    }
    .session-count {
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
        padding: 0.9rem 1.2rem;
        font-size: 0.83rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--gray-1);
        vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr {
        transition: background 0.12s;
    }
    .data-table tbody tr:hover { background: #fdf8f9; }

    /* Participant ID pill */
    .pid {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-family: var(--mono);
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--pink-deep);
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
    }

    .participant-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .test-type-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.65rem;
        border-radius: 6px;
        background: var(--gray-1);
        color: var(--text-secondary);
        border: 1px solid var(--gray-2);
    }

    .datetime {
        font-size: 0.8rem;
        color: var(--text-secondary);
        white-space: nowrap;
    }
    .datetime .date { font-weight: 600; color: var(--text-primary); }
    .datetime .time { color: var(--text-muted); font-family: var(--mono); font-size: 0.75rem; }

    /* Status badges */
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
    .status.complete {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .status.complete .status-dot { background: #15803d; }
    .status.in-progress {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .status.in-progress .status-dot { background: #f59e0b; animation: blink 1.5s ease-in-out infinite; }
    @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

    /* Action buttons */
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
    .btn-view {
        background: var(--gray-1);
        color: var(--text-secondary);
        border: 1px solid var(--gray-2);
    }
    .btn-view:hover {
        background: var(--white);
        border-color: var(--gray-3);
        color: var(--text-primary);
    }
    .btn-start {
        background: var(--pink);
        color: #fff;
        border: 1px solid transparent;
        box-shadow: 0 2px 8px rgba(200,24,74,0.2);
    }
    .btn-start:hover {
        background: var(--pink-deep);
        box-shadow: 0 3px 12px rgba(200,24,74,0.3);
    }
    .btn-email {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        cursor: pointer;
        font-family: inherit;
    }
    .btn-email:hover:not(:disabled) {
        background: #dbeafe;
        border-color: #93c5fd;
    }
    .btn-email:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty state */
    .empty-state {
        padding: 4rem 1.5rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-icon {
        width: 54px; height: 54px;
        background: var(--pink-lt);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: var(--pink);
        margin: 0 auto 1rem;
    }
    .empty-state h3 { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; }
    .empty-state p { font-size: 0.82rem; }

    @media (max-width: 700px) {
        .data-table th:nth-child(5),
        .data-table td:nth-child(5) { display: none; }
    }
</style>
@endsection

@section('content')

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1>Test <em>Sessions</em></h1>
            <p>Manage and track all stress induction sessions.</p>
        </div>
        <a href="/test-sessions/create" class="btn-new"><i class="fas fa-plus"></i> New Session</a>
    </div>

    <!-- FILTER BAR -->
    <form action="/test-sessions" method="GET" class="filter-bar">
        <input type="text" name="search" class="filter-input"
               placeholder="&#xf002;  Search participant name, ID, or test type…"
               value="{{ request('search') }}">

        <select name="status" class="filter-select">
            <option value="">All Statuses</option>
            <option value="complete"     {{ request('status') == 'complete'     ? 'selected' : '' }}>Complete</option>
            <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
        </select>

        <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.83rem; font-weight: 600; color: var(--text-secondary); cursor: pointer; margin-left: 0.5rem; user-select: none;">
            <input type="checkbox" name="include_past" value="1" {{ request('include_past') ? 'checked' : '' }} onchange="this.form.submit()" style="accent-color: var(--pink); width: 16px; height: 16px; cursor: pointer;">
            Show Past Sessions
        </label>

        <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Search</button>

        @if(request('search') || request('status') || request('include_past'))
            <a href="/test-sessions" class="clear-btn"><i class="fas fa-times"></i> Clear</a>
        @endif
    </form>

    <!-- TABLE CARD -->
    <div class="table-card">
        <div class="table-card-header">
            <span class="table-card-title">All Sessions</span>
            <span class="session-count">{{ $testSessions->count() }} records</span>
        </div>

        @if($testSessions->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-vial"></i></div>
                <h3>No sessions found</h3>
                <p>No test sessions match your search criteria. Try adjusting the filters.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Test Type</th>
                            <th>Date &amp; Time</th>
                            <th>Access Code</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testSessions as $session)
                        <tr>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:0.3rem;">
                                    <span class="pid">#P-{{ str_pad($session->participant->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <span class="participant-name">{{ $session->participant->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="test-type-badge">{{ $session->test->test_type }}</span>
                            </td>
                            <td>
                                <div class="datetime">
                                    <div class="date">{{ \Carbon\Carbon::parse($session->testSchedule->date)->format('d M Y') }}</div>
                                    <div class="time">{{ \Carbon\Carbon::parse($session->testSchedule->time)->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td style="vertical-align: middle;">
                                @if($session->access_code)
                                    <span style="background: #f0f7ff; color: #1a73e8; padding: 6px 12px; border-radius: 6px; font-family: monospace; font-size: 1.1rem; font-weight: 700; letter-spacing: 2px; border: 1px solid #bfdbfe;">
                                        {{ $session->access_code }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-size: 0.9rem; font-style: italic;">N/A</span>
                                @endif
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
                                        <form action="{{ route('test-sessions.send-email', $session->id) }}" method="POST" style="margin:0; padding:0; display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-email" title="Send Access Code via Email" {{ !$session->participant->email ? 'disabled' : '' }}>
                                                <i class="fas fa-envelope"></i> Email
                                            </button>
                                        </form>
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

    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $testSessions->links('vendor.pagination.custom') }}
    </div>

@endsection