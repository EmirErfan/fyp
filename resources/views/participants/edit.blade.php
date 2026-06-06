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

    <div style="max-width: 650px; margin: 0 auto; background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f3f4f6;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;">
            Edit Participant #P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}
        </h2>

        <form action="/participants/{{ $participant->id }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $participant->name) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', \Carbon\Carbon::parse($participant->dob)->format('Y-m-d')) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Gender</label>
                    <select name="gender" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="Male" {{ old('gender', $participant->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $participant->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $participant->email) }}" placeholder="e.g. ahmad@email.com" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $participant->phone) }}" placeholder="e.g. 012-3456789" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $participant->occupation) }}" placeholder="e.g. Student, Engineer" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Education Level</label>
                    <select name="education_level" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="">Select Level</option>
                        <option value="High School / SPM" {{ old('education_level', $participant->education_level) == 'High School / SPM' ? 'selected' : '' }}>High School / SPM</option>
                        <option value="Diploma" {{ old('education_level', $participant->education_level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="Bachelor's Degree" {{ old('education_level', $participant->education_level) == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                        <option value="Master's / PhD" {{ old('education_level', $participant->education_level) == "Master's / PhD" ? 'selected' : '' }}>Master's / PhD</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 1.5rem; margin-bottom: 1.5rem; border-top: 1px dashed #e5e7eb; padding-top: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                    <i class="fas fa-notes-medical" style="color: #f59e0b; font-size: 0.9rem;"></i>
                    <h3 style="font-size: 0.85rem; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">
                        Medical & Baseline Details
                    </h3>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Color Blindness Status</label>
                        <select name="color_blindness" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                            <option value="None" {{ old('color_blindness', $participant->color_blindness) == 'None' ? 'selected' : '' }}>Normal Vision (None)</option>
                            <option value="Red-Green" {{ old('color_blindness', $participant->color_blindness) == 'Red-Green' ? 'selected' : '' }}>Red-Green Color Blindness</option>
                            <option value="Blue-Yellow" {{ old('color_blindness', $participant->color_blindness) == 'Blue-Yellow' ? 'selected' : '' }}>Blue-Yellow Color Blindness</option>
                            <option value="Total" {{ old('color_blindness', $participant->color_blindness) == 'Total' ? 'selected' : '' }}>Total Color Blindness</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Existing Conditions</label>
                        <select name="existing_condition" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                            <option value="Normal" {{ old('existing_condition', $participant->existing_condition) == 'Normal' ? 'selected' : '' }}>None (Normal Baseline)</option>
                            <option value="ADHD" {{ old('existing_condition', $participant->existing_condition) == 'ADHD' ? 'selected' : '' }}>ADHD</option>
                            <option value="Anxiety" {{ old('existing_condition', $participant->existing_condition) == 'Anxiety' ? 'selected' : '' }}>Anxiety Disorder</option>
                            <option value="Cardiovascular" {{ old('existing_condition', $participant->existing_condition) == 'Cardiovascular' ? 'selected' : '' }}>Cardiovascular Issue</option>
                            <option value="Other" {{ old('existing_condition', $participant->existing_condition) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="margin-top: 1.5rem; border-top: 1px dashed #e5e7eb; padding-top: 1.5rem; margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Date Joined</label>
                        <input type="date" name="date_joined" value="{{ old('date_joined', \Carbon\Carbon::parse($participant->date_joined)->format('Y-m-d')) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Assign to Researcher</label>
                        <select name="researcher_id" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                            <option value="">Select Researcher</option>
                            @if(isset($researchers))
                                @foreach($researchers as $researcher)
                                    <option value="{{ $researcher->id }}" {{ old('researcher_id', $participant->researcher_id ?? '') == $researcher->id ? 'selected' : '' }}>
                                        {{ $researcher->name }} ({{ ucfirst($researcher->role) }})
                                    </option>
                                @endforeach
                            @else
                                <option value="{{ $participant->researcher_id ?? '' }}" selected>Maintain Current Assignment</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" style="width: 100%; background: #4f46e5; color: white; padding: 0.85rem; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
                Update Participant
            </button>
        </form>
    </div>
@endsection
