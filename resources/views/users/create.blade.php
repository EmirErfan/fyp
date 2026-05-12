@extends('layouts.app')

@section('title', 'Add New User | Stress System')
@section('page-title', 'User Management')

@section('content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03); max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin-bottom: 0.25rem;">Register New Account</h2>
        <p style="color: #666; font-size: 0.9rem;">Provide credentials for a new admin or researcher.</p>
    </div>

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/users" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        
        <!-- Name -->
        <div>
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem;">Full Name</label>
            <input type="text" name="name" required placeholder="e.g. Dr. Jane Smith" value="{{ old('name') }}" style="width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#1a73e8'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <!-- Email -->
        <div>
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem;">Email Address</label>
            <input type="email" name="email" required placeholder="name@institution.edu" value="{{ old('email') }}" style="width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#1a73e8'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <!-- Role -->
        <div>
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem;">Account Role</label>
            <select name="role" required style="width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; background: white; cursor: pointer;">
                <option value="researcher" {{ old('role') == 'researcher' ? 'selected' : '' }}>Researcher (Standard Access)</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full System Access)</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <!-- Password -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#1a73e8'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Confirm Password -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem;">Confirm Password</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#1a73e8'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="flex: 1; background: #1a73e8; color: white; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s;">
                Register Account
            </button>
            <a href="/users" style="display: flex; align-items: center; justify-content: center; flex: 1; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
