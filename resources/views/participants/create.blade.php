@extends('layouts.app')

@section('title', 'Add Participant - Stress System')

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
            Register a New Participant
        </h2>

        <form action="/participants" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Full Name</label>
                <input type="text" name="name" placeholder="e.g. Ahmad Emir" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Date of Birth</label>
                    <input type="date" name="dob" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Gender</label>
                    <select name="gender" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Email Address</label>
                    <input type="email" name="email" placeholder="e.g. ahmad@email.com" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. 012-3456789" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Occupation</label>
                    <input type="text" name="occupation" placeholder="e.g. Student, Engineer" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Education Level</label>
                    <select name="education_level" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="">Select Level</option>
                        <option value="High School / SPM">High School / SPM</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                        <option value="Master's / PhD">Master's / PhD</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; background: #fefce8; padding: 1rem; border-radius: 8px; border: 1px solid #fef08a;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #854d0e; margin-bottom: 0.4rem;">Color Blindness Status</label>
                    <select name="color_blindness" required style="width: 100%; padding: 0.75rem; border: 1px solid #fde047; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="None">Normal Vision (None)</option>
                        <option value="Red-Green">Red-Green Color Blindness</option>
                        <option value="Blue-Yellow">Blue-Yellow Color Blindness</option>
                        <option value="Total">Total Color Blindness</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #854d0e; margin-bottom: 0.4rem;">Existing Conditions</label>
                    <select name="existing_condition" style="width: 100%; padding: 0.75rem; border: 1px solid #fde047; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="Normal">None (Normal Baseline)</option>
                        <option value="ADHD">ADHD</option>
                        <option value="Anxiety">Anxiety Disorder</option>
                        <option value="Cardiovascular">Cardiovascular Issue</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 0.4rem;">Date Joined</label>
                <input type="date" name="date_joined" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <button type="submit" style="width: 100%; background: #4f46e5; color: white; padding: 0.85rem; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
                Register Participant
            </button>
        </form>
    </div>
    
@endsection