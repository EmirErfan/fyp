@extends('layouts.app')

@section('title', 'Review Request | Stress System')
@section('page-title', 'Review Access Request')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem;">Review Access Request</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Verify institutional details before granting platform access.</p>
        </div>
        <a href="/dashboard" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--white); border: 1px solid var(--gray-2); color: var(--text-secondary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: 0.2s;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div style="background: var(--white); border-radius: 16px; padding: 2.5rem; box-shadow: var(--shadow-md); border: 1px solid var(--gray-2);">
        
        <div style="margin-bottom: 2rem;">
            <span style="background: #fef9c3; color: #854d0e; padding: 0.3rem 0.85rem; border-radius: 100px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; border: 1px solid #fef08a;">
                {{ $accessRequest->status }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
            
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">Full Name</label>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">
                    {{ $accessRequest->name }}
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">Institutional Email</label>
                <div style="font-size: 1.1rem; font-weight: 500; color: #0284c7;">
                    <a href="mailto:{{ $accessRequest->email }}" style="color: inherit; text-decoration: none;">{{ $accessRequest->email }}</a>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">
                    Institution Name
                </span>
                <div style="font-size: 1.1rem; font-weight: 600; color: #1a0a10;">
                    {{ $accessRequest->institution }}
                </div>
            </div>

            <div style="background: var(--off-white); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--gray-2); margin-top: 0.5rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Research Purpose & Institutional Context</label>
                <div style="font-size: 0.95rem; line-height: 1.7; color: var(--text-secondary); white-space: pre-wrap;">{{ $accessRequest->purpose }}</div>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">Submitted On</label>
                <div style="font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">
                    {{ $accessRequest->created_at->format('l, F j, Y \a\t h:i A') }}
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid var(--gray-2); padding-top: 2rem; display: flex; gap: 1rem;">
            <form method="POST" action="{{ url('/admin/requests/' . $accessRequest->id . '/reject') }}" style="margin: 0; flex: 1;" onsubmit="return confirm('Are you sure you want to reject this request? An email will be sent to the applicant.');">
                @csrf
                <button type="submit" style="width: 100%; display: inline-flex; justify-content: center; align-items: center; gap: 0.5rem; background: var(--white); color: #ef4444; border: 1px solid #ef4444; padding: 0.85rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='var(--white)'">
                    <i class="fas fa-times-circle"></i> Reject Request
                </button>
            </form>
            <form method="POST" action="{{ url('/admin/requests/' . $accessRequest->id . '/approve') }}" style="margin: 0; flex: 2;">
                @csrf
                <button type="submit" style="width: 100%; display: inline-flex; justify-content: center; align-items: center; gap: 0.5rem; background: var(--pink); color: white; padding: 0.85rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: 0.2s; box-shadow: var(--shadow-pink);">
                    <i class="fas fa-check-circle"></i> Approve & Send Login Credentials
                </button>
            </form>
        </div>

    </div>
</div>
@endsection