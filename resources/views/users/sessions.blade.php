@extends('layouts.app')

@section('title', 'Staff Sessions | Stress System')
@section('page-title', 'Staff Sessions')

@section('content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin-bottom: 0.25rem;">
                Sessions by {{ $staffMember->name }}
            </h2>
            <p style="color: #666; font-size: 0.9rem;">
                Showing all test sessions conducted by this {{ strtoupper($staffMember->role) }}.
            </p>
        </div>
        <a href="/users" style="background: #f4f7f6; color: #555; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; border: 1px solid #eaeaea;">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    @if($sessions->isEmpty())
        <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px; border: 1px dashed #ddd;">
            <p style="color: #888; font-weight: 500;">No sessions have been recorded by this user yet.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Session ID</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Participant</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Test Type</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Date & Time</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Status</th>
                        <th style="padding: 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1rem; font-weight: 600; color: #1e293b;">#SESS-{{ str_pad($session->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 1rem; color: #475569;">{{ $session->participant->name ?? 'Unknown' }}</td>
                            <td style="padding: 1rem; color: #475569;">{{ $session->test->test_type ?? 'Unknown' }}</td>
                            <td style="padding: 1rem; color: #475569;">{{ $session->created_at->format('d/m/Y \a\t h:i A') }}</td>
                            
                            <td style="padding: 1rem;">
                                @if(strtolower($session->status) === 'complete' || strtolower($session->status) === 'completed')
                                    <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Complete</span>
                                @else
                                    <span style="background: #fef9c3; color: #854d0e; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">{{ ucfirst($session->status ?? 'Pending') }}</span>
                                @endif
                            </td>

                            <td style="padding: 1rem;">
                                <a href="{{ url('/test-sessions/' . $session->id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f8f9fa; border: 1px solid #e2e8f0; color: #475569; padding: 0.4rem 0.85rem; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='#f8f9fa'; this.style.borderColor='#e2e8f0'">
                                    View Report
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection