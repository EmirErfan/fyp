@extends('layouts.app')

@section('title', 'Edit Participant - Stress System')

@section('styles')
<style>
    .form-container { background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #eaeaea; padding: 30px; max-width: 500px; margin: 0 auto; margin-top: 20px;}
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px; }
    .form-header h2 { font-size: 20px; color: #333; margin: 0;}
    
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
    
    input[type="text"], input[type="number"], input[type="date"], select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; transition: 0.2s; font-family: inherit; color: #333; box-sizing: border-box;}
    input[type="text"]:focus, input[type="number"]:focus, input[type="date"]:focus, select:focus { border-color: #4b6bfb; outline: none; box-shadow: 0 0 0 3px rgba(75, 107, 251, 0.1); }
    
    .btn-submit { background-color: #4b6bfb; color: white; border: none; padding: 14px 20px; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.2s; margin-top: 10px; }
    .btn-submit:hover { background-color: #3a56d4; }
    
    .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #666; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s;}
    .back-link:hover { color: #4b6bfb; }

    .row { display: flex; gap: 15px; }
    .col { flex: 1; }
</style>
@endsection

@section('content')
    <a href="/participants" class="back-link"><i class="fas fa-arrow-left"></i> Back to Participants</a>

    <div class="form-container">
        <div class="form-header">
            <h2>Edit Participant #P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}</h2>
        </div>

        <form action="/participants/{{ $participant->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ $participant->name }}" required>
            </div>

            <div class="form-group row">
                <div class="col">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="{{ \Carbon\Carbon::parse($participant->dob)->format('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="Male" {{ $participant->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $participant->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="date_joined">Date Joined</label>
                <input type="date" id="date_joined" name="date_joined" value="{{ \Carbon\Carbon::parse($participant->date_joined)->format('Y-m-d') }}" required>
            </div>

            <button type="submit" class="btn-submit">Update Participant</button>
        </form>
    </div>
@endsection
