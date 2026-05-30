<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Login — StressLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --pink-dark:   #9b1c4a;
            --pink-mid:    #c42b63;
            --pink-pale:   #fdf0f5;
            --ink:         #1a0a10;
            --ink-3:       #6b3348;
            --sans: 'Sora', system-ui, sans-serif;
        }

        body {
            font-family: var(--sans);
            background-color: #f8f8fa; /* Soft off-white to make the card pop */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            height: 650px;
            background-color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(155, 28, 74, 0.08); /* Soft pink shadow */
            display: flex;
            overflow: hidden;
            border: 1px solid rgba(155, 28, 74, 0.1);
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            flex: 1;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ink-3); 
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: transform 0.2s, color 0.2s;
        }

        .back-btn svg {
            width: 18px;
            height: 18px;
        }

        .left-panel-inner {
            width: 100%;
            max-width: 340px;
        }

        /* Brand Logo inside form */
        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 2rem;
            letter-spacing: -0.02em;
        }
        .brand-logo i { color: var(--pink-dark); font-size: 1.5rem; }

        .left-panel h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--ink);
            text-align: center;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .subtitle {
            text-align: center;
            font-size: 0.85rem;
            font-weight: 400;
            color: #666;
            margin-bottom: 2.5rem;
            line-height: 1.5;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .form-group input {
            width: 100%;
            padding: 1.1rem 1.25rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-family: var(--sans);
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--ink);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input::placeholder { color: #a0aec0; font-weight: 400; }
        .form-group input:focus { 
            border-color: var(--pink-dark);
            box-shadow: 0 0 0 3px rgba(155, 28, 74, 0.1); 
        }

        .toggle-pw {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--pink-dark); }

        .recovery-link {
            display: block;
            text-align: right;
            font-size: 0.8rem;
            color: var(--ink-3);
            text-decoration: none;
            margin-top: -0.5rem;
            margin-bottom: 2rem;
            font-weight: 600;
            transition: color 0.2s;
        }
        .recovery-link:hover { color: var(--pink-dark); }
        
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 0.85rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1.1rem;
            background-color: var(--pink-dark);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-family: var(--sans);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(155, 28, 74, 0.25);
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
            margin-bottom: 1rem;
        }

        .register-link {
            text-align: center;
            font-size: 0.85rem;
            color: #666;
            margin-top: 1rem;
        }
        .register-link a {
            color: var(--pink-dark);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

     

        /* ── RIGHT PANEL ── */
        .right-panel {
            flex: 1.2;
            background-color: var(--pink-dark);
            background-image: url('{{ asset("images/login-illustration.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            overflow: hidden;
        }

        /* Beautiful gradient overlay to match your landing page */
        .right-panel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(155, 28, 74, 0.85) 0%, rgba(196, 43, 99, 0.9) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
        }

        .right-text h2 {
            color: #FFFFFF;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .right-text p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.6;
            max-width: 400px;
        }

        @media (max-width: 850px) {
            .login-container { flex-direction: column; height: auto; border-radius: 20px; }
            .right-panel { display: none; } /* Hide image on mobile for cleaner login */
            .left-panel { padding: 4rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <div class="left-panel">
        
        <a href="/" class="back-btn">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Home
        </a>

        <div class="left-panel-inner">
            
            <div class="brand-logo">
                <i class="fas fa-brain"></i>
                <span>StressLab</span>
            </div>

            <h1>Welcome Back</h1>
            <p class="subtitle">Please enter your credentials to access the researcher dashboard.</p>
            
            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" placeholder="Institutional Email" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <button type="button" class="toggle-pw" onclick="togglePassword()">
                        <svg id="eyeIcon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>

                <a href="/forgot-password" class="recovery-link">Forgot Password?</a>

                <button type="submit" class="submit-btn">Sign In to Dashboard</button>

                <div class="register-link">
                    No Account Yet? <a href="/#contact">Request Access</a>
                </div>
            </form>
        </div>
    </div>

    <div class="right-panel">
        <div class="right-panel-overlay">
            <div class="right-text">
                <h2>Advancing Cognitive Research</h2>
                <p>Centralized platform for stress induction methods, automated scoring, and real-time behavioral analytics.</p>
            </div>
        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        } else {
            input.type = 'password';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
        }
    }
</script>

</body>
</html>