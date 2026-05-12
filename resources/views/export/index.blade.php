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
            <p style="color: #666; font-size: 0.9rem;">Export all participant session data, including performance metrics and DESS scale scores.</p>
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
                <i class="fas fa-check-circle" style="color: #10b981;"></i> Raw Pre/Post DESS Scores
            </li>
        </ul>
    </div>

    <div style="display: flex; gap: 1rem;">
        <a href="{{ url('/export/csv') }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; background: #1a73e8; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: background 0.2s, transform 0.1s;">
            <i class="fas fa-download"></i> Download CSV Report
        </a>
    </div>
</div>

<style>
    a:hover { background: #1557b0 !important; }
    a:active { transform: scale(0.98); }
</style>
@endsection
