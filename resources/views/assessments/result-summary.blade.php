<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiment Complete - Results</title>
    <style>
        body { background-color: #f4f7f6; margin: 0; font-family: system-ui, -apple-system, sans-serif; }
        .result-container { max-width: 900px; margin: 50px auto; padding: 20px; }
        
        .header-box { text-align: center; margin-bottom: 40px; }
        .header-box h1 { color: #198754; font-size: 36px; margin-bottom: 10px; }
        .header-box p { color: #555; font-size: 18px; }

        .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 40px; margin-bottom: 30px; }
        .card-title { font-size: 22px; font-weight: bold; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px; margin-bottom: 20px; color: #333; }

        /* Grid for Stats */
        .stat-grid { display: flex; gap: 20px; text-align: center; flex-wrap: wrap; justify-content: center; }
        .stat-box { flex: 1; min-width: 140px; background: #f8f9fa; padding: 25px 15px; border-radius: 8px; border: 1px solid #e9ecef; box-shadow: 0 2px 5px rgba(0,0,0,0.02);}
        .stat-number { font-size: 38px; font-weight: 900; margin-bottom: 5px; }
        .stat-label { font-size: 14px; font-weight: bold; color: #666; text-transform: uppercase; letter-spacing: 1px;}

        .text-primary { color: #0d6efd; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #fd7e14; }
        .text-success { color: #198754; }
        .text-info { color: #0dcaf0; }

        /* Comparison Table */
        .compare-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .compare-table th, .compare-table td { padding: 15px; text-align: center; border-bottom: 1px solid #eee; font-size: 16px;}
        .compare-table th { background: #f8f9fa; color: #333; }
        .compare-table td.label { text-align: left; font-weight: bold; color: #555; }
        
        /* Researcher Button */
        .dashboard-btn { display: block; width: 100%; background: #212529; color: white; text-align: center; padding: 18px; text-decoration: none; font-size: 18px; font-weight: bold; border-radius: 8px; transition: 0.2s; margin-top: 40px;}
        .dashboard-btn:hover { background: #000; }
    </style>
</head>
<body>

@php
    // ==========================================
    // 📝 EDIT YOUR QUESTIONS HERE
    // ==========================================
    $distressQuestions = [
        1 => "1. I felt tense or anxious.",
        2 => "2. I felt overwhelmed by the task.",
        3 => "3. I felt mentally exhausted.",
        4 => "4. I felt frustrated or annoyed.",
        5 => "5. I felt under pressure."
    ];

    $eustressQuestions = [
        1 => "1. I felt motivated to succeed.",
        2 => "2. I felt completely focused.",
        3 => "3. I felt energized by the challenge.",
        4 => "4. I felt confident in my abilities."
    ];
    // ==========================================

    // Calculate Pre-Test Totals
    $preDistress = $preTest ? ($preTest->distress_item_01 + $preTest->distress_item_02 + $preTest->distress_item_03 + $preTest->distress_item_04 + $preTest->distress_item_05) : 0;
    $preEustress = $preTest ? ($preTest->eustress_item_01 + $preTest->eustress_item_02 + $preTest->eustress_item_03 + $preTest->eustress_item_04) : 0;

    // Calculate Post-Test Totals
    $postDistress = $postTest ? ($postTest->distress_item_01 + $postTest->distress_item_02 + $postTest->distress_item_03 + $postTest->distress_item_04 + $postTest->distress_item_05) : 0;
    $postEustress = $postTest ? ($postTest->eustress_item_01 + $postTest->eustress_item_02 + $postTest->eustress_item_03 + $postTest->eustress_item_04) : 0;

    // Determine the exact Test Name (TSST, MIST, Stroop)
    $testName = $session->test->test_type ?? 'Task';
    $accuracyLabel = ($testName == 'Stroop Test') ? 'Color Accuracy' : 'Math Accuracy';
@endphp

<div class="result-container">
    
    <div class="header-box">
        <h1>Experiment Complete!</h1>
        <p>Thank you for participating, <strong>{{ $session->participant->name ?? 'Participant' }}</strong>.</p>
    </div>

    <div class="card">
        <div class="card-title">{{ $testName }} Performance Data</div>
        <div class="stat-grid">
            @if($testName == 'Stroop Test')
                <div class="stat-box">
                    <div class="stat-number text-primary">
                        {{ number_format($session->result->accuracy_rate ?? 0, 0) }}
                    </div>
                    <div class="stat-label">Total Answered</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-success">
                        {{ $session->result->total_error ?? 0 }}
                    </div>
                    <div class="stat-label">Right</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-danger">
                        {{ number_format($session->result->average_reaction_time ?? 0, 0) }}
                    </div>
                    <div class="stat-label">Wrong</div>
                </div>
            @elseif($testName == 'MIST')
                <div class="stat-box">
                    <div class="stat-number text-primary">
                        {{ $session->result->total_attempts ?? 0 }}
                    </div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-success">
                        {{ $session->result->correct_answers ?? 0 }}
                    </div>
                    <div class="stat-label">Correct</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-danger">
                        {{ $session->result->total_error ?? 0 }}
                    </div>
                    <div class="stat-label">Incorrect</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-info">
                        {{ number_format($session->result->accuracy_rate ?? 0, 1) }}%
                    </div>
                    <div class="stat-label">{{ $accuracyLabel }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-warning">
                        {{ number_format($session->result->average_reaction_time ?? 0, 2) }} s
                    </div>
                    <div class="stat-label">Avg Reaction Time</div>
                </div>
            @else
                <div class="stat-box">
                    <div class="stat-number text-primary">
                        {{ $session->result->total_attempts ?? 0 }}
                    </div>
                    <div class="stat-label">Total Answered</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-danger">
                        {{ $session->result->total_error ?? 0 }}
                    </div>
                    <div class="stat-label">Total Retries</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-info">
                        {{ number_format($session->result->accuracy_rate ?? 0, 1) }}%
                    </div>
                    <div class="stat-label">{{ $accuracyLabel }}</div>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-title">Dual-Recording Evidence</div>
        
        @if(isset($session->result) && $session->result->face_video_path)
            <div style="background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <video width="100%" controls preload="metadata" style="display: block;">
                    <source src="{{ asset('storage/' . $session->result->face_video_path) }}" type="video/webm">
                    Your browser does not support the video tag.
                </video>
            </div>
            <p style="text-align: center; color: #666; font-size: 14px; margin-top: 15px;">
                Synchronized Participant Reaction and Screen Capture
            </p>
        @else
            <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; text-align: center; color: #6c757d; border: 1px dashed #ccc;">
                <i>No video recording found for this session.</i>
            </div>
        @endif
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 22px; font-weight: bold; color: #333;">
                Psychological Impact (DESS Scale)
            </h3>
            <button onclick="openDessModal()" style="padding: 6px 14px; background-color: #eff6ff; color: #4338ca; border: 1px solid #c7d2fe; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; transition: 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                View Full Answers
            </button>
        </div>

        <table class="compare-table">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Pre-Test (Before)</th>
                    <th>Post-Test (After)</th>
                    <th>Change</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">Total Distress (Negative Stress)</td>
                    <td>{{ $preDistress }} / 25</td>
                    <td style="font-weight: bold; color: {{ $postDistress > $preDistress ? '#dc3545' : '#198754' }};">{{ $postDistress }} / 25</td>
                    <td>
                        @if($postDistress > $preDistress)
                            <span style="color: #dc3545;">+{{ $postDistress - $preDistress }}</span>
                        @else
                            <span style="color: #198754;">{{ $postDistress - $preDistress }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Total Eustress (Positive Stress)</td>
                    <td>{{ $preEustress }} / 20</td>
                    <td style="font-weight: bold; color: {{ $postEustress > $preEustress ? '#198754' : '#6c757d' }};">{{ $postEustress }} / 20</td>
                    <td>
                        @if($postEustress > $preEustress)
                            <span style="color: #198754;">+{{ $postEustress - $preEustress }}</span>
                        @else
                            <span style="color: #6c757d;">{{ $postEustress - $preEustress }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align: center; color: #888; font-size: 13px; margin-top: 20px;">
            * Distress measures feelings of being overwhelmed, anxious, and strained.<br>
            * Eustress measures feelings of motivation, focus, and positive challenge.
        </p>
    </div>

    <div style="text-align: center; margin-top: 50px;">
        <p style="color: #666; font-style: italic;">Please return the device to the researcher.</p>
        <a href="/dashboard" class="dashboard-btn">Researcher: Return to Dashboard</a>
    </div>

</div>

<div id="dessModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
    
    <div style="background-color: #fff; margin: 5% auto; padding: 25px; border-radius: 12px; width: 90%; max-width: 800px; max-height: 85vh; overflow-y: auto; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #333;">Complete DESS Scale Comparison</h2>
            <span onclick="closeDessModal()" style="color: #aaa; font-size: 32px; font-weight: bold; cursor: pointer; line-height: 1;">&times;</span>
        </div>

        <table class="compare-table" style="margin-top: 0;">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;">Question Item</th>
                    <th style="color: #0284c7;">Pre-Test</th>
                    <th style="color: #b91c1c;">Post-Test</th>
                    <th>Score Change</th>
                </tr>
            </thead>
            <tbody>
                @if($preTest || $postTest)
                    {{-- Loop through Distress Items (1-5) --}}
                    @for($i = 1; $i <= 5; $i++)
                        @php 
                            $column = 'distress_item_0' . $i; 
                            $preScore = $preTest ? (int) $preTest->$column : 0;
                            $postScore = $postTest ? (int) $postTest->$column : 0;
                            $difference = $postScore - $preScore;
                        @endphp
                        <tr>
                            <td class="label" style="font-weight: bold; color: #dc3545; font-size: 14px;">
                                {{ $distressQuestions[$i] ?? "Distress Item 0" . $i }}
                            </td>
                            <td style="background-color: #f0f9ff;">{{ $preScore > 0 ? $preScore : '-' }}</td>
                            <td style="background-color: #fef2f2;">{{ $postScore > 0 ? $postScore : '-' }}</td>
                            <td>
                                @if($difference > 0)
                                    <span style="color: #b91c1c; font-weight: bold; background: #fee2e2; padding: 4px 8px; border-radius: 4px;">+{{ $difference }}</span>
                                @elseif($difference < 0)
                                    <span style="color: #15803d; font-weight: bold; background: #dcfce7; padding: 4px 8px; border-radius: 4px;">{{ $difference }}</span>
                                @else
                                    <span style="color: #6b7280; font-style: italic;">No Change</span>
                                @endif
                            </td>
                        </tr>
                    @endfor

                    {{-- Loop through Eustress Items (1-4) --}}
                    @for($i = 1; $i <= 4; $i++)
                        @php 
                            $column = 'eustress_item_0' . $i; 
                            $preScore = $preTest ? (int) $preTest->$column : 0;
                            $postScore = $postTest ? (int) $postTest->$column : 0;
                            $difference = $postScore - $preScore;
                        @endphp
                        <tr>
                            <td class="label" style="font-weight: bold; color: #198754; font-size: 14px;">
                                {{ $eustressQuestions[$i] ?? "Eustress Item 0" . $i }}
                            </td>
                            <td style="background-color: #f0f9ff;">{{ $preScore > 0 ? $preScore : '-' }}</td>
                            <td style="background-color: #fef2f2;">{{ $postScore > 0 ? $postScore : '-' }}</td>
                            <td>
                                @if($difference > 0)
                                    <span style="color: #15803d; font-weight: bold; background: #dcfce7; padding: 4px 8px; border-radius: 4px;">+{{ $difference }}</span>
                                @elseif($difference < 0)
                                    <span style="color: #b91c1c; font-weight: bold; background: #fee2e2; padding: 4px 8px; border-radius: 4px;">{{ $difference }}</span>
                                @else
                                    <span style="color: #6b7280; font-style: italic;">No Change</span>
                                @endif
                            </td>
                        </tr>
                    @endfor
                @else
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px; color: #666;">
                            Incomplete data. Both Pre and Post assessments must be completed.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div style="margin-top: 25px; text-align: right;">
            <button onclick="closeDessModal()" style="padding: 10px 20px; background-color: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 15px;">Close Comparison</button>
        </div>
    </div>
</div>

<script>
    var modal = document.getElementById("dessModal");

    function openDessModal() {
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; // Prevent scrolling behind modal
    }

    function closeDessModal() {
        modal.style.display = "none";
        document.body.style.overflow = "auto"; // Re-enable scrolling
    }

    // Close modal if user clicks outside the white box
    window.onclick = function(event) {
        if (event.target == modal) {
            closeDessModal();
        }
    }
</script>

</body>
</html>