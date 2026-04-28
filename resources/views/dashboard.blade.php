@extends('layouts.app')

@section('title', 'Dashboard - Stress System')

@section('styles')
<style>
    h1 { font-size: 24px; color: #333; margin-bottom: 30px; }
    .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
    .metric-card { background: #ffffff; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; border-left: 4px solid #4b6bfb;}
    .metric-title { font-size: 14px; color: #888; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;}
    .metric-value { font-size: 36px; font-weight: bold; color: #333; }
</style>
@endsection

@section('content')
    <h1>Dashboard Overview</h1>
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-title">Total Participants</div>
            <div class="metric-value">{{ $totalParticipants }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Total Test Sessions</div>
            <div class="metric-value">{{ $totalSessions }}</div>
        </div>
    </div>
@endsection