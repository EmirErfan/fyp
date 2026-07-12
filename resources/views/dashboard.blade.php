@extends('layouts.app')

@section('title', 'Dashboard — StressLab')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header {
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .page-header h1 {
        font-size: 1.5rem;
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
        border-radius: 12px;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .card:hover {
        border-color: var(--pink-soft);
        box-shadow: 0 4px 20px rgba(200,24,74,0.06);
        transform: translateY(-2px);
    }

    /* ── TOP ROW & STAT CARDS ── */
    .top-row {
        display: grid;
        grid-template-columns: 2fr 1fr; 
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); 
        gap: 1rem;
    }
    .stat-card {
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
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
        font-size: 1.8rem;
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

    /* ── SECTION HEADING ── */
    .section-head {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.75rem;
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
        grid-template-columns: 1fr 1fr; /* 50/50 Split */
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

    /* Quick Actions */
    .quick-actions { 
        display: grid; 
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem; 
    }
    .qa-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
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
        padding: 1.25rem !important;
        background: linear-gradient(160deg, var(--white) 60%, var(--pink-lt)) !important;
        height: 100%; 
    }
    .profile-avatar-lg {
        width: 50px; height: 50px;
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

    /* Chart Select Dropdown */
    .chart-select {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        border: 1px solid var(--gray-2);
        background: #f8fafc;
        color: var(--text-muted);
        cursor: pointer;
        outline: none;
        font-weight: 600;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 960px) {
        .top-row, .bottom-row { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .stats-grid, .quick-actions { grid-template-columns: 1fr; }
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

    <div class="top-row">
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
        </div>

        <div class="panel-card card profile-panel">
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

    <div class="section-head">
        <span class="section-head-title">Quick Access & Analytics</span>
        <div class="section-head-line"></div>
    </div>

    <div class="bottom-row">

        <div class="panel-card card">
            <div class="panel-card-title">Quick Actions</div>
            <div class="quick-actions">
                <a href="/participants/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-user-plus" style="color:var(--pink);"></i></div>
                    Add New Participant
                    <span class="info-btn" style="margin-left: auto;" data-tooltip="Enroll a new participant into the system"><i class="fas fa-info"></i></span>
                </a>
                <a href="/test-sessions/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-vial" style="color:#2563eb;"></i></div>
                    Create Test Session
                    <span class="info-btn" style="margin-left: auto;" data-tooltip="Start a new stress test session"><i class="fas fa-info"></i></span>
                </a>
                <a href="/schedules/create" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-calendar-alt" style="color:#7c3aed;"></i></div>
                    Schedule Session
                    <span class="info-btn" style="margin-left: auto;" data-tooltip="Plan a future test session"><i class="fas fa-info"></i></span>
                </a>
                <a href="/participants" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-users" style="color:#15803d;"></i></div>
                    View Participants
                    <span class="info-btn" style="margin-left: auto;" data-tooltip="Browse all enrolled participants"><i class="fas fa-info"></i></span>
                </a>
                <a href="/export" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-file-export" style="color:var(--gray-5);"></i></div>
                    Export Data
                    <span class="info-btn" style="margin-left: auto;" data-tooltip="Download session results as CSV/Excel"><i class="fas fa-info"></i></span>
                </a>
                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="/team-management" class="qa-btn">
                    <div class="qa-icon"><i class="fas fa-users-cog" style="color:#f59e0b;"></i></div>
                    Manage Team
                    <span class="info-btn" style="margin-left: auto;" data-tooltip="Administer team members and access"><i class="fas fa-info"></i></span>
                </a>
                @endif
            </div>
        </div>

        <div class="panel-card card">
            <div class="panel-card-title" style="display: flex; justify-content: space-between; align-items: center; border-bottom: none; padding-bottom: 0; margin-bottom: 0.5rem;">
                <span>Pre-Test vs Post-Test Stress</span>
                <select id="demographicFilter" class="chart-select" onchange="updateChartData()">
                    <option value="gender">By Gender</option>
                    <option value="age">By Age Group</option>
                    <option value="education">By Education</option>
                </select>
            </div>
            
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="deltaChart"></canvas>
            </div>
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
                        @foreach($pendingRequests->take(5) as $request) 
                            <tr style="border-bottom: 1px solid var(--gray-1);">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartDataSets = @json($chartData);

        const ctx = document.getElementById('deltaChart').getContext('2d');
        let deltaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartDataSets.gender.labels,
                datasets: [
                    {
                        label: 'Pre-Test Average',
                        data: chartDataSets.gender.pre,
                        backgroundColor: 'rgba(37, 99, 235, 0.8)', // Blue
                        borderRadius: 4,
                    },
                    {
                        label: 'Post-Test Average',
                        data: chartDataSets.gender.post,
                        backgroundColor: 'rgba(219, 39, 119, 0.8)', // Pink
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            font: { size: 10, family: 'system-ui' }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });

        function updateChartData() {
            const filter = document.getElementById('demographicFilter').value;
            const newData = chartDataSets[filter];

            deltaChart.data.labels = newData.labels;
            deltaChart.data.datasets[0].data = newData.pre;
            deltaChart.data.datasets[1].data = newData.post;
            deltaChart.update();
        }
    </script>
@endsection