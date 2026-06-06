@extends('layouts.app')

@section('title', 'Profile Settings | Stress System')
@section('page-title', 'Account Settings')

@section('content')
<div style="max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    
    <div style="margin-bottom: 2rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #111;">Account Settings</h2>
        <p style="color: #6b7280; font-size: 0.95rem;">Update your personal details, institutional affiliation, and profile photo.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form action="/profile" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 2px solid #e5e7eb;">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 1.5rem; font-weight: 700; color: #9ca3af;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Profile Photo</label>
                <input type="file" name="profile_picture" accept="image/*" style="font-size: 0.9rem; color: #6b7280;">
                <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Recommended: Square image, max 2MB.</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">University / Institution</label>
                <input type="text" name="university" value="{{ old('university', auth()->user()->university) }}" placeholder="e.g., UiTM Kampus Jasin" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Department / Lab</label>
                <input type="text" name="department" value="{{ old('department', auth()->user()->department) }}" placeholder="e.g., Computer Science" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Research Bio (Optional)</label>
            <textarea name="bio" rows="3" placeholder="Briefly describe your research focus..." style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; resize: vertical;">{{ old('bio', auth()->user()->bio) }}</textarea>
        </div>

        <button type="submit" style="width: 100%; background: #4f46e5; color: white; padding: 0.85rem; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
            Save Changes
        </button>
    </form>
</div>
@endsection