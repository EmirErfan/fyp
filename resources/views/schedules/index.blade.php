@extends('layouts.app')

@section('title', 'Schedules — StressLab')

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
        box-shadow: 0 3px 12px rgba(200,24,74,0.28);
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
        white-space: nowrap;
    }
    .btn-new:hover {
        background: var(--pink-deep);
        box-shadow: 0 5px 18px rgba(200,24,74,0.38);
        transform: translateY(-1px);
    }

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

    /* Schedule ID pill */
    .sid {
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

    .sched-date {
        font-weight: 600;
        color: var(--text-primary);
    }
    .sched-time {
        font-family: var(--mono);
        font-size: 0.8rem;
        color: var(--text-secondary);
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
    }

    /* Action button */
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
        background: var(--pink-lt);
        color: var(--pink);
        border: 1px solid var(--pink-soft);
    }
    .btn-action:hover {
        background: var(--pink);
        color: #fff;
        border-color: var(--pink);
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

    /* ── LAYOUT GRID ── */
    .layout-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 992px) {
        .layout-grid {
            grid-template-columns: 1fr;
        }
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.4rem 0.6rem;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        background: var(--gray-1);
        color: var(--text-muted);
        border: 1px solid var(--gray-2);
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-delete:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #f87171;
    }

    /* ── CALENDAR ── */
    .calendar-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .fc {
        font-family: var(--sans);
    }
    .fc-theme-standard .fc-scrollgrid {
        border-color: var(--gray-2);
        border-radius: 8px;
        overflow: hidden;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: var(--gray-2);
    }
    .fc .fc-col-header-cell-cushion {
        padding: 12px 8px;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        font-weight: 700;
        text-decoration: none;
    }
    .fc-daygrid-day-number {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        padding: 8px !important;
        text-decoration: none;
    }
    .fc-daygrid-day {
        cursor: pointer;
        transition: background 0.15s;
    }
    .fc-daygrid-day:hover {
        background: #fdf8f9;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 3px 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
    }
    .fc-event-title { font-weight: 700; }
    .fc .fc-button-primary {
        background: var(--white);
        border: 1px solid var(--gray-2);
        color: var(--text-primary);
        font-weight: 600;
        text-transform: capitalize;
        box-shadow: none !important;
    }
    .fc .fc-button-primary:hover {
        background: var(--gray-1);
        color: var(--pink);
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background: var(--pink-lt);
        color: var(--pink);
        border-color: var(--pink-soft);
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-primary);
    }
</style>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1>Test <em>Schedules</em></h1>
            <p>Manage all session time slots and schedule assignments.</p>
        </div>
        <a href="/schedules/create" class="btn-new"><i class="fas fa-plus"></i> New Schedule</a>
    </div>

    <div class="layout-grid">
        <!-- Left Column: Calendar -->
        <div class="calendar-card">
            <div id="calendar"></div>
        </div>

        <!-- Right Column: List and Filter -->
        <div class="list-section">
            <form action="/schedules" method="GET" class="filter-bar">
                <input type="text" name="search" class="filter-input"
                       placeholder="Search by ID, date, or time…"
                       value="{{ request('search') }}">
                <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Search</button>
                @if(request('search'))
                    <a href="/schedules" class="clear-btn"><i class="fas fa-times"></i></a>
                @endif
            </form>

    <div class="table-card">
        <div class="table-card-header">
            <span class="table-card-title">All Schedules</span>
            <span class="record-count">{{ $schedules->count() }} records</span>
        </div>

        @if($schedules->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-calendar-alt"></i></div>
                <h3>No schedules found</h3>
                <p>No schedules match your search. Try a different keyword or create a new one.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Schedule ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td><span class="sid">#S-{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="sched-date">{{ \Carbon\Carbon::parse($schedule->date)->format('l, d F Y') }}</span></td>
                            <td><span class="sched-time">{{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</span></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <a href="/schedules/{{ $schedule->id }}" class="btn-action">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <form action="/schedules/{{ $schedule->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Delete Schedule">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    @if($schedules->hasPages())
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $schedules->links('vendor.pagination.custom') }}
        </div>
    @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                height: 420,
                events: [
                    @foreach($schedules as $schedule)
                    {
                        title: '#S-{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }}',
                        start: '{{ $schedule->date }}T{{ $schedule->time }}',
                        url: '/schedules/{{ $schedule->id }}',
                        backgroundColor: '#d53f8c', /* var(--pink) */
                        borderColor: '#d53f8c',
                        textColor: '#fff'
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                dateClick: function(info) {
                    window.location.href = '/schedules/create?date=' + info.dateStr;
                }
            });
            calendar.render();
        });
    </script>
@endsection