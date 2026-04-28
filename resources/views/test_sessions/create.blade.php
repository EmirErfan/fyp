@extends('layouts.app')

@section('title', 'New Test Session - Stress System')

@section('styles')
<style>
    .form-container { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 30px; max-width: 600px; margin: 0 auto; margin-top: 20px;}
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px; }
    .form-header h2 { font-size: 20px; color: #333; margin: 0;}
    
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
    
    select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; transition: 0.2s; font-family: inherit; color: #333; background-color: #fff; cursor: pointer;}
    select:focus { border-color: #4b6bfb; outline: none; box-shadow: 0 0 0 3px rgba(75, 107, 251, 0.1); }
    
    .btn-submit { background-color: #4b6bfb; color: white; border: none; padding: 14px 20px; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.2s; margin-top: 10px; }
    .btn-submit:hover { background-color: #3a56d4; }
    
    .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #666; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s;}
    .back-link:hover { color: #4b6bfb; }
</style>
@endsection

@section('content')
    <a href="/test-sessions" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

    <div class="form-container">
        <div class="form-header">
            <h2>Start New Test Session</h2>
        </div>

        <form action="/test-sessions" method="POST">
            @csrf

            <div class="form-group">
                <label for="test_schedule_id">Select Schedule (Date & Time)</label>
                <select name="test_schedule_id" id="test_schedule_id" required>
                    <option value="" disabled selected>-- Choose a Schedule --</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            {{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }} at {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="participant_id">Select Participant</label>
                <select name="participant_id" id="participant_id" required>
                    <option value="" disabled selected>-- Choose a Participant --</option>
                    @foreach($participants as $participant)
                        <option value="{{ $participant->id }}">
                            #P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }} - {{ $participant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="test_id">Select Test Protocol</label>
                <select name="test_id" id="test_id" required>
                    <option value="" disabled selected>-- Choose a Test --</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}">
                            {{ $test->test_type }} ({{ $test->test_level }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-submit">Start Session</button>
        </form>
    </div>
@endsection