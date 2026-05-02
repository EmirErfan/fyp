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
        .stat-grid { display: flex; gap: 20px; text-align: center; }
        .stat-box { flex: 1; background: #f8f9fa; padding: 25px 15px; border-radius: 8px; border: 1px solid #e9ecef; box-shadow: 0 2px 5px rgba(0,0,0,0.02);}
        .stat-number { font-size: 38px; font-weight: 900; margin-bottom: 5px; }
        .stat-label { font-size: 14px; font-weight: bold; color: #666; text-transform: uppercase; letter-spacing: 1px;}

        .text-primary { color: #0d6efd; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #fd7e14; }

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
            @else
                <div class="stat-box">
                    <div class="stat-number text-primary">
                        {{ number_format($session->result->accuracy_rate ?? 0, 1) }}%
                    </div>
                    <div class="stat-label">{{ $accuracyLabel }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-danger">
                        {{ $session->result->total_error ?? 0 }}
                    </div>
                    <div class="stat-label">Total Errors Made</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number text-warning">
                        {{ number_format($session->result->average_reaction_time ?? 0, 0) }} ms
                    </div>
                    <div class="stat-label">Avg Reaction Time</div>
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
        <div class="card-title">Psychological Impact (DESS Scale)</div>
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

</body>
</html>