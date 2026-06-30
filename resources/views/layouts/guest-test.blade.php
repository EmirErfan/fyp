<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stress Test — StressLab</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Sora', sans-serif; 
            background: #f8fafc; 
            margin: 0; 
            padding: 0; 
        }
        .test-nav { 
            padding: 1rem 2rem; 
            background: white; 
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .test-logo { 
            font-weight: 800; 
            font-size: 1.25rem; 
            color: #9b1c4a; 
            display: flex; 
            align-items: center; 
            gap: 0.5rem; 
        }
        .session-badge {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            font-family: monospace;
            letter-spacing: 1px;
        }
    </style>
    @yield('styles')
</head>
<body>
    
    <div class="test-nav">
        <div class="test-logo">
            <i class="fas fa-brain"></i> StressLab
        </div>
        <div class="session-badge">
            Secure Test Environment
        </div>
    </div>

    @yield('content')

</body>
</html>