<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background-color: #9b1c4a;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .details-card {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .detail-row {
            margin-bottom: 10px;
            font-size: 15px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            font-weight: 700;
            color: #1e293b;
        }
        .access-code-box {
            text-align: center;
            background: #f0f7ff;
            border: 2px dashed #bfdbfe;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        .code-label {
            font-size: 14px;
            color: #1d4ed8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .code-value {
            font-size: 36px;
            font-weight: 800;
            color: #1e3a8a;
            letter-spacing: 4px;
            font-family: monospace;
        }
        .action-box {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            background-color: #9b1c4a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            transition: background 0.2s;
        }
        .btn:hover {
            background-color: #7a153e;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #9b1c4a;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>StressLab Test Invitation</h1>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $participant->name }},</div>
            <div class="message">
                You have been invited to participate in a psychological test session for StressLab. Please find your session details and access code below.
            </div>

            <div class="details-card">
                <div class="detail-row">
                    <span class="detail-label">Test Type:</span>
                    <span class="detail-value">{{ $session->test->test_type }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($session->testSchedule->date)->format('F j, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($session->testSchedule->time)->format('h:i A') }}</span>
                </div>
            </div>

            <div class="access-code-box">
                <div class="code-label">Your Unique Access Code</div>
                <div class="code-value">{{ $session->access_code }}</div>
            </div>

            <div class="message" style="text-align: center; font-size: 15px; margin-top: 20px;">
                Please visit the link below and enter your access code to securely join your test environment.
            </div>

            <div class="action-box">
                <a href="{{ $loginUrl }}" class="btn">Go to Test Environment</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} StressLab. All rights reserved.<br>
            If you have any questions, please contact your researcher.
        </div>
    </div>
</body>
</html>
