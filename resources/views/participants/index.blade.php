@extends('layouts.app')

@section('title', 'Participants - Stress System')

@section('styles')
<style>
    .table-section { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 25px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-header h2 { font-size: 18px; color: #333; margin: 0;}
    
    .header-actions { display: flex; gap: 15px; align-items: center; }
    .search-bar { padding: 8px 15px; border: 1px solid #eaeaea; border-radius: 6px; font-size: 13px; width: 250px; }
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
            <h2>Participants Management</h2>
            <div class="header-actions">
                <input type="text" class="search-bar" placeholder="Search by name or ID...">
                <a href="/participants/create" class="btn-primary"><i class="fas fa-plus"></i> Add Participant</a>
            </div>
        </div>

        @if($participants->isEmpty())
            <p style="color: #888; text-align: center; padding: 20px;">No participants have been registered yet.</p>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Participant ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Date Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $participant)
                            <tr>
                                <td><strong>#P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $participant->name }}</td>
                                <td>{{ $participant->age }}</td>
                                <td>{{ $participant->gender }}</td>
                                <td>{{ \Carbon\Carbon::parse($participant->date_joined)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="#" class="action-btn">More</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection