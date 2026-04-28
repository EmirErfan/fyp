@extends('layouts.app')

@section('title', 'Schedules - Stress System')

@section('styles')
<style>
    .table-section { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 25px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-header h2 { font-size: 18px; color: #333; margin: 0;}
    
    .header-actions { display: flex; gap: 15px; }
    .btn-primary { background-color: #4b6bfb; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; }
    
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { padding: 15px 10px; border-bottom: 2px solid #eaeaea; color: #888; font-size: 13px; text-transform: uppercase; font-weight: 600; }
    td { padding: 15px 10px; border-bottom: 1px solid #eaeaea; color: #444; font-size: 14px; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover { background-color: #fcfcfc; }

    .action-btn { background-color: #f8f9fa; border: 1px solid #ddd; padding: 6px 15px; border-radius: 6px; color: #333; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.2s; }
    .action-btn:hover { background-color: #eef2ff; color: #4b6bfb; border-color: #4b6bfb; }
</style>
@endsection

@section('content')
    <div class="table-section">
        <div class="table-header">
            <h2>Test Schedules</h2>
            <div class="header-actions">
                <a href="/schedules/create" class="btn-primary"><i class="fas fa-plus"></i> New Schedule</a>
            </div>
        </div>

        @if($schedules->isEmpty())
            <p style="color: #888; text-align: center; padding: 20px;">No schedules have been created yet.</p>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Schedule ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td><strong>#S-{{ str_pad($schedule->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($schedule->date)->format('l, d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</td>
                                <td>
                                    <a href="/schedules/{{ $schedule->id }}" class="action-btn" style="background-color: #eef2ff; color: #4b6bfb; border-color: #4b6bfb;"><i class="fas fa-eye"></i> View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection