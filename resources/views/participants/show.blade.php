@extends('layouts.app')

@section('title', 'Participant Profile | Stress System')
@section('page-title', 'Participant Profile')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="/participants" style="color: #6b7280; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
        <i class="fas fa-arrow-left"></i> Back to Participants
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    
    <!-- LEFT COLUMN: Profile Details -->
    <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f3f4f6;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1.5rem;">
            <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700; color: #475569;">
                {{ strtoupper(substr($participant->name, 0, 1)) }}
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #111; margin: 0;">{{ $participant->name }}</h2>
                <span style="font-size: 0.85rem; color: #6b7280; font-weight: 600;">#P-{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 0.75rem; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Demographics</h3>
            <p style="font-size: 0.9rem; color: #374151; margin-bottom: 0.4rem;"><strong>Age:</strong> {{ \Carbon\Carbon::parse($participant->dob)->age }} yrs</p>
            <p style="font-size: 0.9rem; color: #374151; margin-bottom: 0.4rem;"><strong>Gender:</strong> {{ $participant->gender }}</p>
            <p style="font-size: 0.9rem; color: #374151; margin-bottom: 0.4rem;"><strong>Occupation:</strong> {{ $participant->occupation ?? 'N/A' }}</p>
            <p style="font-size: 0.9rem; color: #374151; margin-bottom: 0.4rem;"><strong>Education:</strong> {{ $participant->education_level ?? 'N/A' }}</p>
            <p style="font-size: 0.9rem; color: #374151; margin-bottom: 0.4rem;"><strong>Email:</strong> {{ $participant->email ?? 'N/A' }}</p>
            <p style="font-size: 0.9rem; color: #374151; margin-bottom: 0.4rem;"><strong>Phone:</strong> {{ $participant->phone ?? 'N/A' }}</p>
        </div>

        <div style="margin-bottom: 1.5rem; background: #fefce8; padding: 1rem; border-radius: 8px; border: 1px solid #fef08a;">
            <h3 style="font-size: 0.75rem; text-transform: uppercase; color: #854d0e; letter-spacing: 0.05em; margin-bottom: 0.75rem;"><i class="fas fa-notes-medical"></i> Medical Baseline</h3>
            <p style="font-size: 0.9rem; color: #854d0e; margin-bottom: 0.4rem;"><strong>Color Blindness:</strong> {{ $participant->color_blindness ?? 'None' }}</p>
            <p style="font-size: 0.9rem; color: #854d0e; margin-bottom: 0;"><strong>Conditions:</strong> {{ $participant->existing_condition ?? 'Normal' }}</p>
        </div>

        <a href="/participants/{{ $participant->id }}/edit" style="display: block; width: 100%; text-align: center; background: #f3f4f6; color: #374151; padding: 0.6rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: 0.2s;" onmouseover="this.style.backgroundColor='#e5e7eb';" onmouseout="this.style.backgroundColor='#f3f4f6';">
            Edit Profile
        </a>
    </div>

    <!-- RIGHT COLUMN: Test Sessions History -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f3f4f6;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #111; margin-bottom: 1.5rem;"><i class="fas fa-clipboard-list" style="color: #4f46e5;"></i> Test Sessions History</h3>
            
            @if($allTests->count() > 0)
                
                <!-- Table Headers matching Schedule Details -->
                <div style="display: grid; grid-template-columns: 2fr 2fr 2fr; gap: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f1f5f9; margin-bottom: 0.5rem;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Session</div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Test Protocol</div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Status</div>
                </div>

                <div style="display: flex; flex-direction: column;">
                    @foreach($allTests as $test)
                        
                        <!-- Pure HTML Translator for Test Protocols -->
                        @php
                            $protocolNames = [
                                1 => 'Stroop Test',
                                2 => 'MIST',
                                3 => 'TSST'
                            ];
                            $testName = $protocolNames[$test->test_id] ?? 'Standard Test';
                            
                            // Safe check for completion (adjust based on how Schedule Details checks it)
                            $isComplete = isset($test->result) || isset($test->score) || isset($test->is_completed) && $test->is_completed;
                        @endphp

                        <!-- Grid Row -->
                        <div style="display: grid; grid-template-columns: 2fr 2fr 2fr; gap: 1rem; align-items: center; padding: 1.25rem 0; border-bottom: 1px solid #f3f4f6;">
                            
                            <!-- Session Info -->
                            <div>
                                <strong style="color: #111; font-size: 0.95rem; display: block; margin-bottom: 0.2rem;">Session #{{ str_pad($test->id, 3, '0', STR_PAD_LEFT) }}</strong>
                                <span style="font-size: 0.8rem; color: #6b7280;">{{ $test->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            <!-- Test Protocol Badge (Gray) -->
                            <div>
                                <span style="background: #f1f5f9; color: #475569; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                    {{ $testName }}
                                </span>
                            </div>
                            
                            <!-- Status Pill Indicator -->
                            <div>
                                @if($isComplete)
                                    <!-- Complete (Green) -->
                                    <span style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 0.3rem 0.8rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span> Complete
                                    </span>
                                @else
                                    <!-- In Progress (Yellow) -->
                                    <span style="background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; padding: 0.3rem 0.8rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #ca8a04;"></span> In Progress
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; font-size: 0.9rem; margin: 0; background: #f9fafb; padding: 1.5rem; border-radius: 8px; border: 1px dashed #d1d5db; text-align: center;">
                    No test sessions have been assigned to this participant yet.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection