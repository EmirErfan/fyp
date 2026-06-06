@extends('layouts.app')

@section('title', 'Participants Management | Stress System')
@section('page-title', 'Participants')

@section('content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f3f4f6;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin: 0;">Participants Management</h2>
        <a href="/participants/create" style="background: #4f46e5; color: white; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: 0.2s;">
            <i class="fas fa-plus"></i> Add Participant
        </a>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1rem;">
            <input type="text" placeholder="Search by Name or Participant ID (e.g. #P-001)..." style="flex: 1; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; outline: none;">
            <button style="background: #1f2937; color: white; padding: 0 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">Search</button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Participant ID</th>
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Name</th>
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Age</th>
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Gender</th>
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Date Joined</th>
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Researcher</th>
                    <th style="padding: 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $participant)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer;"
                        onmouseover="this.style.background='#f8fafc'"
                        onmouseout="this.style.background='transparent'"
                        onclick="window.location='/participants/{{ $participant->id }}'">

                        <td style="padding: 1rem; font-weight: 700; color: #374151;">
                            #P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td style="padding: 1rem; font-weight: 500; color: #111;">
                            {{ $participant->name }}
                        </td>
                        <td style="padding: 1rem; color: #4b5563;">
                            {{ \Carbon\Carbon::parse($participant->dob)->age }}
                        </td>
                        <td style="padding: 1rem; color: #4b5563;">
                            {{ $participant->gender }}
                        </td>
                        <td style="padding: 1rem; color: #4b5563;">
                            {{ \Carbon\Carbon::parse($participant->date_joined)->format('d/m/Y') }}
                        </td>
                        <td style="padding: 1rem;">
                            @if(isset($participant->researcher))
                                <span style="color: #0284c7; font-weight: 600; font-size: 0.85rem;">{{ $participant->researcher->name }}</span>
                            @else
                                <span style="color: #9ca3af; font-style: italic; font-size: 0.85rem;">Unassigned</span>
                            @endif
                        </td>
                        
                        <td style="padding: 1rem;" onclick="event.stopPropagation();">
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="/participants/{{ $participant->id }}/edit" style="padding: 0.4rem 0.8rem; border: 1px solid #e5e7eb; border-radius: 6px; color: #374151; font-size: 0.8rem; font-weight: 600; text-decoration: none; background: white; transition: 0.2s;" onmouseover="this.style.borderColor='#d1d5db'; this.style.backgroundColor='#f9fafb';" onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='white';">Edit</a>
                                
                                <form action="/participants/{{ $participant->id }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this participant? All their test data will be lost.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 0.4rem 0.8rem; border: 1px solid #fecaca; border-radius: 6px; color: #dc2626; font-size: 0.8rem; font-weight: 600; background: #fef2f2; cursor: pointer; transition: 0.2s;" onmouseover="this.style.backgroundColor='#fee2e2';" onmouseout="this.style.backgroundColor='#fef2f2';">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #6b7280;">No participants found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection