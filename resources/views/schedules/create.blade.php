@extends('layouts.app')

@section('title', 'New Schedule - Stress System')

@section('styles')
<style>
    .form-container { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 30px; max-width: 500px; margin: 0 auto; margin-top: 20px;}
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px; }
    .form-header h2 { font-size: 20px; color: #333; margin: 0;}
    
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
    
    input[type="date"], input[type="time"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; transition: 0.2s; font-family: inherit; color: #333;}
    input[type="date"]:focus, input[type="time"]:focus { border-color: #4b6bfb; outline: none; box-shadow: 0 0 0 3px rgba(75, 107, 251, 0.1); }
    
    .btn-submit { background-color: #4b6bfb; color: white; border: none; padding: 14px 20px; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.2s; margin-top: 10px; }
    .btn-submit:hover { background-color: #3a56d4; }
    
    .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #666; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s;}
    .back-link:hover { color: #4b6bfb; }
</style>
@endsection

@section('content')
    <a href="/schedules" class="back-link"><i class="fas fa-arrow-left"></i> Back to Schedules</a>

    <div class="form-container">
        <div class="form-header">
            <h2>Create New Test Schedule</h2>
        </div>

        <form action="/schedules" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="date">Select Date</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Select Time</label>
                <input type="time" id="time" name="time" required>
            </div>

            <button type="submit" class="btn-submit">Save Schedule</button>
        </form>
    </div>
@endsection