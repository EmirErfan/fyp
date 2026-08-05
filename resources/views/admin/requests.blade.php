@extends('layouts.app')

@section('title', 'Access Requests | Stress System')
@section('page-title', 'Access Requests')

@section('content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin-bottom: 0.25rem;">Researcher Access Requests</h2>
            <p style="color: #666; font-size: 0.9rem;">Review institutional requests. Approving will automatically create their account and email them a temporary password.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 500;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($requests->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem; background: #f8f9fa; border-radius: 8px; border: 1px dashed #ddd;">
            <div style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 1rem;"><i class="fas fa-inbox"></i></div>
            <p style="color: #64748b; font-weight: 500; font-size: 1.05rem;">You're all caught up!</p>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.25rem;">There are no pending access requests at the moment.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Applicant Name</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Institutional Email</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Research Purpose</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Date Requested</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            
                            <td style="padding: 1rem; font-weight: 600; color: #1e293b;">{{ $request->name }}</td>
                            
                            <td style="padding: 1rem; color: #4b6bfb; font-weight: 500;">
                                <a href="mailto:{{ $request->email }}" style="color: inherit; text-decoration: none;">{{ $request->email }}</a>
                            </td>
                            
                            <td style="padding: 1rem; color: #64748b; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $request->purpose }}">
                                {{ $request->purpose }}
                            </td>
                            
                            <td style="padding: 1rem; color: #64748b;">{{ $request->created_at->format('M d, Y') }}</td>
                            
                            <td style="padding: 1rem; text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <form method="POST" action="{{ url('/admin/requests/' . $request->id . '/reject') }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to reject this request? An email will be sent.');">
                                    @csrf
                                    <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; background: transparent; color: #ef4444; border: 1px solid #ef4444; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                                <form method="POST" action="{{ url('/admin/requests/' . $request->id . '/approve') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #db2777; color: white; padding: 0.5rem 1rem; border: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#be185d'" onmouseout="this.style.background='#db2777'">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </button>
                                </form>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection