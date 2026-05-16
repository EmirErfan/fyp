@extends('layouts.app')

@section('title', 'Data Export | Stress System')
@section('page-title', 'Data Export')

@section('content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03); max-width: 800px;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="width: 48px; height: 48px; background: #e8f0fe; color: #1a73e8; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-file-csv"></i>
        </div>
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin-bottom: 0.25rem;">Research Data Export</h2>
            <p style="color: #666; font-size: 0.9rem;">Export participant session data, including performance metrics, video links, and DESS scale scores.</p>
        </div>
    </div>

    <div style="background: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 1rem;">Export Contains:</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
            <li style="display: flex; align-items: center; gap: 0.5rem; color: #555; font-size: 0.9rem;">
                <i class="fas fa-check-circle" style="color: #10b981;"></i> Participant Demographics
            </li>
            <li style="display: flex; align-items: center; gap: 0.5rem; color: #555; font-size: 0.9rem;">
                <i class="fas fa-check-circle" style="color: #10b981;"></i> Test Session Details
            </li>
            <li style="display: flex; align-items: center; gap: 0.5rem; color: #555; font-size: 0.9rem;">
                <i class="fas fa-check-circle" style="color: #10b981;"></i> Accuracy & Reaction Times
            </li>
            <li style="display: flex; align-items: center; gap: 0.5rem; color: #555; font-size: 0.9rem;">
                <i class="fas fa-check-circle" style="color: #10b981;"></i> Session Video Links
            </li>
            <li style="display: flex; align-items: center; gap: 0.5rem; color: #555; font-size: 0.9rem;">
                <i class="fas fa-check-circle" style="color: #10b981;"></i> Raw Pre/Post DESS Scores
            </li>
        </ul>
    </div>

    <form action="{{ url('/export/csv') }}" method="GET" style="display: flex; flex-direction: column; gap: 1.25rem;">
        
        <div>
            <label for="participant_id" style="display: block; font-weight: 600; font-size: 0.9rem; color: #333; margin-bottom: 0.5rem;">Filter by Participant</label>
            <select name="participant_id" id="participant_id" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; font-size: 0.95rem;">
                <option value="all">All Participants</option>
                @foreach($participants as $participant)
                    <option value="{{ $participant->id }}">{{ $participant->name }} (#P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }})</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <div style="flex: 1;">
                <label for="start_date" style="display: block; font-weight: 600; font-size: 0.9rem; color: #333; margin-bottom: 0.5rem;">Start Date</label>
                <input type="date" name="start_date" id="start_date" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="flex: 1;">
                <label for="end_date" style="display: block; font-weight: 600; font-size: 0.9rem; color: #333; margin-bottom: 0.5rem;">End Date</label>
                <input type="date" name="end_date" id="end_date" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; font-size: 0.95rem;">
            </div>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: #1a73e8; color: white; padding: 0.85rem 1.75rem; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s, transform 0.1s; font-family: inherit;">
                <i class="fas fa-download"></i> Generate CSV Report
            </button>
        </div>
    </form>
</div>

<style>
    button[type="submit"]:hover { background: #1557b0 !important; }
    button[type="submit"]:active { transform: scale(0.98); }
</style>
@endsection