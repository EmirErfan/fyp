@extends('layouts.app')

@section('title', 'Participants - Stress System')

@section('styles')
<style>
    .table-section { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 25px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-header h2 { font-size: 18px; color: #333; margin: 0;}
    
    .header-actions { display: flex; gap: 15px; align-items: center; }
    .btn-primary { background-color: #4b6bfb; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; }
    
    /* Search Bar Styles (Matched with other pages) */
    .filter-section { display: flex; gap: 10px; margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eaeaea; align-items: center; flex-wrap: wrap;}
    .filter-input { padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; flex-grow: 1; min-width: 250px; outline: none; transition: 0.2s;}
    .filter-input:focus { border-color: #4b6bfb; box-shadow: 0 0 0 3px rgba(75, 107, 251, 0.1); }
    .filter-btn { background-color: #212529; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; transition: 0.2s;}
    .filter-btn:hover { background-color: #000; }
    .clear-btn { color: #888; text-decoration: none; font-size: 14px; margin-left: 5px; transition: 0.2s;}
    .clear-btn:hover { color: #dc3545; }

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
            <h2>
                Participants Management
                @if(isset($researcherId) && $researcherId)
                    <span style="font-size: 14px; font-weight: normal; color: #666; margin-left: 10px;">(Filtered by Researcher)</span>
                    <a href="/participants" style="font-size: 12px; color: #4b6bfb; margin-left: 5px; text-decoration: none;">Clear Filter</a>
                @endif
            </h2>
            <div class="header-actions">
                <a href="/participants/create" class="btn-primary"><i class="fas fa-plus"></i> Add Participant</a>
            </div>
        </div>

        <form action="/participants" method="GET" class="filter-section">
            <input type="text" name="search" class="filter-input" placeholder="Search by Name or Participant ID (e.g. #P-001)..." value="{{ request('search') }}">
            <button type="submit" class="filter-btn">Search</button>

            @if(request('search'))
                <a href="/participants" class="clear-btn">Clear Search</a>
            @endif
        </form>

        @if($participants->isEmpty())
            <p style="color: #888; text-align: center; padding: 40px; background: #fcfcfc; border-radius: 8px; border: 1px dashed #ccc;">No participants match your search.</p>
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
                            @if(auth()->user()->role === 'admin')
                            <th>Researcher</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $participant)
                            <tr>
                                <td><strong>#P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $participant->name }}</td>
                                <td>{{ $participant->age }}</td>
                                <td>{{ ucfirst($participant->gender) }}</td>
                                <td>{{ \Carbon\Carbon::parse($participant->date_joined)->format('d/m/Y') }}</td>
                                @if(auth()->user()->role === 'admin')
                                <td>
                                    @if($participant->researcher)
                                        <span style="background: #f0f9ff; color: #0284c7; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">{{ $participant->researcher->name }}</span>
                                    @else
                                        <span style="color: #999; font-style: italic; font-size: 0.85rem;">Unassigned</span>
                                    @endif
                                </td>
                                @endif
                                <td style="display: flex; gap: 8px;">
                                    <a href="/participants/{{ $participant->id }}/edit" class="action-btn">Edit</a>
                                    <form action="/participants/{{ $participant->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this participant?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn" style="color: #dc3545; border-color: #f8d7da; background: #fff; cursor: pointer;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection