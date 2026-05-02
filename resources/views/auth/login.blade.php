<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff; /* Same as welcome page */
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
            background-color: #F8F9FA;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            flex: 1;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .left-panel-inner {
            width: 100%;
            max-width: 340px;
        }

        .left-panel h1 {
            font-size: 2.2rem;
            font-weight: 600;
            color: #1A1A1A;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .subtitle {
            font-size: 0.75rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.8rem;
            display: block;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .form-group input {
            width: 100%;
            padding: 1.1rem 1.25rem;
            background: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
            outline: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.015);
            transition: box-shadow 0.2s;
        }

        .form-group input::placeholder { color: #B0B0B0; font-weight: 400; }
        .form-group input:focus { box-shadow: 0 0 0 2px rgba(143,100,105,0.2); }

        .toggle-pw {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #A0A0A0;
        }

        .recovery-link {
            display: block;
            text-align: right;
            font-size: 0.75rem;
            color: #A0A0A0;
            text-decoration: none;
            margin-top: -0.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        
        .alert-error {
            background: #ffebe9;
            color: #d1242f;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-bottom: 1.25rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1.1rem;
            background-color: #926A6F; /* Deep mauve */
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(146,106,111,0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 2rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(146,106,111,0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #B0B0B0;
            font-size: 0.8rem;
            font-weight: 400;
            margin-bottom: 1.5rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #EAEAEA;
        }

        .divider::before { margin-right: 1em; }
        .divider::after { margin-left: 1em; }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 1.25rem;
        }

        .social-btn {
            width: 65px;
            height: 45px;
            background: #FFFFFF;
            border: none;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }
        
        .social-btn.active {
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transform: scale(1.02);
        }

        .social-btn svg { width: 22px; height: 22px; }

        /* ── RIGHT PANEL ── */
        .right-panel {
            flex: 1.1;
            background-color: #E2D9D6;
            background-image: url('{{ asset("images/login-illustration.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            overflow: hidden;
        }

        /* Overlay text on image */
        .right-text {
            color: #FFFFFF;
            font-size: 1.1rem;
            font-weight: 300;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            letter-spacing: 0.02em;
        }

        .carousel-controls {
            display: flex;
            gap: 0.75rem;
        }

        .carousel-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.7);
            background: transparent;
            color: #FFF;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .carousel-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        .carousel-btn svg { width: 16px; height: 16px; }

        @media (max-width: 850px) {
            .login-container { flex-direction: column; height: auto; border-radius: 20px; }
            .right-panel { height: 350px; border-radius: 0 0 20px 20px; }
            .left-panel { padding: 3rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- LEFT PANEL -->
    <div class="left-panel">
        <div class="left-panel-inner">
            <h1>Hello!</h1>
            
            @if($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <button type="button" class="toggle-pw" onclick="togglePassword()">
                        <!-- Eye crossed icon SVG -->
                        <svg id="eyeIcon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>

                <a href="#" class="recovery-link">Recovery Password</a>

                <button type="submit" class="submit-btn">Sign In</button>
            </form>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        
        
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