@extends('layouts.app')

@section('title', 'User Management | Stress System')
@section('page-title', 'User Management')

@section('content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin-bottom: 0.25rem;">Staff & Researchers</h2>
            <p style="color: #666; font-size: 0.9rem;">Manage system access and roles.</p>
        </div>
        <a href="/users/create" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #1a73e8; color: white; padding: 0.6rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: background 0.2s;">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 500;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Name</th>
                    <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Email</th>
                    <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Joined</th>
                    <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1rem; font-weight: 500; color: #1e293b;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #475569;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td style="padding: 1rem; color: #64748b; font-size: 0.95rem;">{{ $user->email }}</td>
                        <td style="padding: 1rem;">
                            @if($user->role === 'admin')
                                <span style="background: #fef2f2; color: #dc2626; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.02em; border: 1px solid #fecaca;">ADMIN</span>
                            @else
                                <span style="background: #f0f9ff; color: #0284c7; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.02em; border: 1px solid #bae6fd;">RESEARCHER</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #64748b; font-size: 0.95rem;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="padding: 1rem;">
                            @if($user->role === 'researcher')
                            <a href="/participants?researcher_id={{ $user->id }}" style="display: inline-flex; align-items: center; gap: 0.25rem; background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; padding: 0.4rem 0.75rem; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                <i class="fas fa-users"></i> View Participants
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
