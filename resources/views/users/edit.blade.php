@extends('layouts.app')

@section('title', 'Edit User | Stress System')
@section('page-title', 'Edit User')

@section('content')
<div style="max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    
    <div style="margin-bottom: 2rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #111;">Edit User Account</h2>
        <p style="color: #6b7280; font-size: 0.95rem;">Manage details and system permissions for {{ $editUser->name }}.</p>
    </div>

    <form action="/admin/users/{{ $editUser->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 2px solid #e5e7eb;">
                @if($editUser->profile_picture)
                    <img src="{{ asset('storage/' . $editUser->profile_picture) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 1.5rem; font-weight: 700; color: #9ca3af;">{{ strtoupper(substr($editUser->name, 0, 1)) }}</span>
                @endif
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Profile Photo</label>
                <input type="file" name="profile_picture" accept="image/*" style="font-size: 0.9rem; color: #6b7280;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $editUser->name) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $editUser->email) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">System Role Level</label>
            <select name="role" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; background: white;">
                <option value="researcher" {{ $editUser->role === 'researcher' ? 'selected' : '' }}>Researcher (Standard Access)</option>
                <option value="admin" {{ $editUser->role === 'admin' ? 'selected' : '' }}>Administrator (Full Access)</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">University / Institution</label>
                <input type="text" name="university" value="{{ old('university', $editUser->university) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Department / Lab</label>
                <input type="text" name="department" value="{{ old('department', $editUser->department) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Research Bio</label>
            <textarea name="bio" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; resize: vertical;">{{ old('bio', $editUser->bio) }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="background: #1a73e8; color: white; padding: 0.85rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; flex: 1; transition: 0.2s;">
                Save Account Changes
            </button>
            <a href="/users" style="display: inline-flex; align-items: center; justify-content: center; background: #f3f4f6; color: #4b5563; padding: 0.85rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem; text-decoration: none; flex: 1; text-align: center; transition: 0.2s;">
                Cancel
            </a>
        </div>
    </form>

    <form action="/admin/users/{{ $editUser->id }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this user account? This action cannot be undone.');" style="margin-top: 1rem; text-align: center;">
        @csrf
        @method('DELETE')
        <button type="submit" style="width: 100%; background-color: #dc3545; color: white; padding: 0.85rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: 0.2s;">
            <i class="fas fa-trash-alt"></i> Delete User
        </button>
    </form>

</div>
@endsection