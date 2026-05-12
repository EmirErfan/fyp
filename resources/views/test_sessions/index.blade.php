@extends('layouts.app')

@section('title', 'Test Sessions - Stress System')

@section('styles')
<style>
    .table-section { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 25px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-header h2 { font-size: 18px; color: #333; margin: 0;}
    .header-actions { display: flex; gap: 15px; }
    
    /* Search & Filter Styles */
    .filter-section { display: flex; gap: 10px; margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eaeaea; align-items: center; flex-wrap: wrap;}
    .filter-input { padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; flex-grow: 1; min-width: 250px; outline: none; transition: 0.2s;}
    .filter-input:focus { border-color: #4b6bfb; box-shadow: 0 0 0 3px rgba(75, 107, 251, 0.1); }
    .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; outline: none; background: white; cursor: pointer;}
    .filter-btn { background-color: #212529; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; transition: 0.2s;}
    .filter-btn:hover { background-color: #000; }
    .clear-btn { color: #888; text-decoration: none; font-size: 14px; margin-left: 5px; transition: 0.2s;}
    .clear-btn:hover { color: #dc3545; }

    .btn-primary { background-color: #4b6bfb; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s;}
    .btn-primary:hover { background-color: #3a56d4; }
    
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
    .start-test-btn { background-color: #4b6bfb; color: white; border: none; padding: 6px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-block; margin-right: 5px; transition: 0.2s;}
    .start-test-btn:hover { background-color: #3a56d4; }
</style>
@endsection

@section('content')
    <div class="table-section">
        <div class="table-header">
            <h2>Test Sessions Management</h2>
            <div class="header-actions">
                <a href="/test-sessions/create" class="btn-primary"><i class="fas fa-plus"></i> New Session</a>
            </div>
        </div>

        <form action="/test-sessions" method="GET" class="filter-section">
            <input type="text" name="search" class="filter-input" placeholder="Search participant name, ID, or test type..." value="{{ request('search') }}">
            
            <select name="status" class="filter-select">
                <option value="">All Statuses</option>
                <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
            </select>

            <button type="submit" class="filter-btn">Search</button>

            @if(request('search') || request('status'))
                <a href="/test-sessions" class="clear-btn">Clear Filters</a>
            @endif
        </form>

        @if($testSessions->isEmpty())
            <p style="color: #888; text-align: center; padding: 40px; background: #fcfcfc; border-radius: 8px; border: 1px dashed #ccc;">No test sessions match your search criteria.</p>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Participant ID</th>
                            <th>Participant Name</th>
                            <th>Test Type</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testSessions as $session)
                            <tr>
                                <td><strong>#P-{{ str_pad($session->participant->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $session->participant->name }}</td>
                                <td>{{ $session->test->test_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($session->testSchedule->date)->format('d/m/Y') }} at {{ \Carbon\Carbon::parse($session->testSchedule->time)->format('h:i A') }}</td>
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