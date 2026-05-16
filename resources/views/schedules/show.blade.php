@extends('layouts.app')

@section('title', 'Schedule Details - Stress System')

@section('styles')
<style>
    .header-card { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 25px; margin-bottom: 20px; border-left: 4px solid #198754; display: flex; justify-content: space-between; align-items: center;}
    .header-info h2 { margin: 0 0 5px 0; color: #333; font-size: 20px; }
    .header-info p { margin: 0; color: #666; font-size: 14px; }
    
    .table-section { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 25px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-header h3 { font-size: 16px; color: #333; margin: 0;}
    
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { padding: 15px 10px; border-bottom: 2px solid #eaeaea; color: #888; font-size: 13px; text-transform: uppercase; font-weight: 600; }
    td { padding: 15px 10px; border-bottom: 1px solid #eaeaea; color: #444; font-size: 14px; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover { background-color: #fcfcfc; }

    .status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
    .status.complete { background-color: #e6f4ea; color: #1e8e3e; }
    .status.in-progress { background-color: #fef7e0; color: #b06000; }

    .action-btn { background-color: #f8f9fa; border: 1px solid #ddd; padding: 6px 15px; border-radius: 6px; color: #333; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.2s; display: inline-block; margin-right: 5px;}
    .action-btn:hover { background-color: #eef2ff; color: #4b6bfb; border-color: #4b6bfb; }
    .start-test-btn { background-color: #4b6bfb; color: white; border: none; padding: 6px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-block; margin-right: 5px;}
    .start-test-btn:hover { background-color: #3a56d4; }
    
    .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px; color: #666; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s;}
    .back-link:hover { color: #4b6bfb; }
</style>
@endsection

@section('content')
    <a href="/schedules" class="back-link"><i class="fas fa-arrow-left"></i> Back to All Schedules</a>

    <div class="header-card">
        <div class="header-info">
            <h2>Schedule #S-{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }}</h2>
            <p><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($schedule->date)->format('l, d F Y') }} &nbsp;|&nbsp; <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</p>
        </div>
        <div>
            <a href="/test-sessions/create" class="action-btn" style="background-color: #4b6bfb; color: white; border: none;"><i class="fas fa-user-plus"></i> Assign Participant</a>
        </div>
    </div>

    <div class="table-section">
        <div class="table-header">
            <h3>Assigned Participants for this Slot</h3>
        </div>

        @if($schedule->testSessions->isEmpty())
            <p style="color: #888; text-align: center; padding: 20px;">No participants are assigned to this time slot yet.</p>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Participant ID</th>
                            <th>Name</th>
                            <th>Test Protocol</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedule->testSessions as $session)
                            <tr>
                                <td><strong>#P-{{ str_pad($session->participant->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $session->participant->name }}</td>
                                <td><strong>{{ $session->test->test_type }}</strong></td>
                                <td>
                                    @if($session->result)
                                        <span class="status complete">Complete</span>
                                    @else
                                        <span class="status in-progress">In Progress</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$session->result)
                                        <a href="/test-sessions/{{ $session->id }}/consent" class="start-test-btn">Start Test</a>
                                    @else
                                        <a href="/test-sessions/{{ $session->id }}" class="action-btn">View Report</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection