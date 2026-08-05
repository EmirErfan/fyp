<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stress Test — Centralized Stress Induction Methods</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --pink-dark:   #9b1c4a;
            --pink-mid:    #c42b63;
            --pink-bright: #e8356e;
            --pink-light:  #f7c5d8;
            --pink-pale:   #fdf0f5;
            --pink-ultra:  #fff5f8;
            --white:       #ffffff;
            --ink:         #1a0a10;
            --ink-2:       #3d1824;
            --ink-3:       #6b3348;
            --ink-4:       #a06070;
            --ink-5:       #d4a0b0;
            --border:      rgba(155,28,74,0.15);
            --shadow-pink: rgba(155,28,74,0.12);
            --sans: 'Sora', system-ui, sans-serif;
            --mono: 'DM Mono', monospace;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: var(--sans);
            background: var(--white);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .top-right-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 10;
            transition: background 0.2s;
        }
        .top-right-btn:hover { background: rgba(255,255,255,0.3); }

        .hero {
            position: relative;
            height: 100vh;
            min-height: 650px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, var(--pink-dark) 0%, var(--pink-mid) 100%);
            text-align: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 120vw;
            height: 120vw;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            top: -40%;
            right: -30%;
            pointer-events: none;
            z-index: 0;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            width: 80vw;
            height: 80vw;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -20%;
            left: -20%;
            pointer-events: none;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 60px;
            width: 100%;
            max-width: 1050px;
            padding: 0 20px;
        }

        .hero-left {
            flex: 1;
            max-width: 380px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-right {
            flex: 1;
            max-width: 500px;
            text-align: left;
            color: white;
        }

        .hero-right h1 {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
            letter-spacing: -1px;
        }

        .hero-right p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
        }
        
        .hero-right-features {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
        }
        .feature-icon {
            background: rgba(255,255,255,0.2);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-logo {
            color: white;
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 35px 30px;
            border-radius: 24px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-box-title {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .hero-box input {
            width: 100%;
            padding: 16px 20px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border: 2px solid transparent;
            border-radius: 50px;
            margin-bottom: 16px;
            outline: none;
            background: white;
            color: var(--ink);
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            font-family: var(--sans);
            letter-spacing: 2px;
        }
        .hero-box input::placeholder {
            color: #999;
            font-weight: 500;
            letter-spacing: 0px;
        }
        .hero-box input:focus { 
            border-color: rgba(255,255,255,0.8); 
            box-shadow: 0 0 0 4px rgba(255,255,255,0.2); 
        }
        
        .hero-box button {
            width: 100%;
            background: var(--ink);
            color: white;
            border: none;
            padding: 16px;
            font-size: 16px;
            font-weight: 800;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: var(--sans);
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(26,10,16,0.3);
        }
        .hero-box button:hover { 
            background: #000; 
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26,10,16,0.4);
        }

        .hero-footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            z-index: 1;
        }
        .hero-footer a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        .hero-footer a:hover { text-decoration: underline; }

        section { padding: 6rem 2rem; }
        .section-inner { max-width: 1100px; margin: 0 auto; }
        .eyebrow {
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--pink-mid); margin-bottom: 0.8rem; display: block;
        }
        .sec-title {
            font-family: var(--sans);
            font-size: clamp(1.9rem, 3.8vw, 3rem);
            font-weight: 400; line-height: 1.1;
            letter-spacing: -0.02em; color: var(--ink); margin-bottom: 1rem;
        }
        .sec-desc { font-size: 1rem; line-height: 1.7; color: var(--ink-3); max-width: 500px; }

        .methods-top {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 4rem; align-items: end; margin-bottom: 4rem;
        }
        .methods-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; }

        .method-card {
            border: 1px solid var(--border); border-radius: 20px;
            padding: 2rem; background: var(--white);
            transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        }
        .method-card:hover {
            border-color: var(--pink-light);
            box-shadow: 0 8px 40px rgba(155,28,74,0.1);
            transform: translateY(-4px);
        }
        .mc-num  { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; color: var(--ink-5); text-transform: uppercase; margin-bottom: 1rem; }
        .mc-icon { width: 44px; height: 44px; background: var(--pink-pale); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1.2rem; }
        .mc-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin-bottom: 0.55rem; letter-spacing: -0.015em; }
        .mc-desc  { font-size: 0.875rem; line-height: 1.65; color: var(--ink-3); }
        .mc-tag   {
            display: inline-block; margin-top: 1.2rem;
            font-size: 0.68rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--pink-dark); background: var(--pink-pale);
            padding: 0.22rem 0.6rem; border-radius: 100px;
        }

        .contact-section { 
            background: var(--white); 
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 4rem 2rem;
        }
        .contact-inner { 
            width: 100%;
            max-width: 800px; 
            margin: 0 auto; 
            text-align: center; 
        }
        .contact-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 20px; padding: 2rem;
            margin-top: 1.5rem; text-align: left;
            box-shadow: 0 4px 40px rgba(155,28,74,0.08);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .form-group { margin-bottom: 0.9rem; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--ink); margin-bottom: 0.3rem; }
        .form-group input, .form-group textarea {
            width: 100%; background: var(--white);
            border: 1px solid var(--border); border-radius: 8px;
            padding: 0.65rem 0.85rem; color: var(--ink);
            font-family: var(--sans); font-size: 0.85rem; outline: none; resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input::placeholder, .form-group textarea::placeholder { color: var(--ink-5); }
        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--pink-dark); box-shadow: 0 0 0 3px rgba(155,28,74,0.08);
        }
        .form-group textarea { min-height: 75px; }
        .submit-btn {
            width: 100%; background: var(--pink-dark); color: var(--white);
            border: none; border-radius: 100px;
            padding: 0.9rem 2rem; font-family: var(--sans);
            font-size: 0.95rem; font-weight: 600; cursor: pointer;
            transition: all 0.2s; margin-top: 0.5rem;
            box-shadow: 0 4px 20px rgba(155,28,74,0.3);
        }
        .submit-btn:hover { background: var(--pink-mid); transform: translateY(-1px); box-shadow: 0 6px 28px rgba(155,28,74,0.4); }
        .form-note { display: flex; align-items: center; gap: 0.4rem; font-size: 0.76rem; color: var(--ink-4); margin-top: 1rem; justify-content: center; }

        .form-note { display: flex; align-items: center; gap: 0.4rem; font-size: 0.76rem; color: var(--ink-4); margin-top: 1rem; justify-content: center; }

        footer { border-top: 1px solid var(--border); padding: 2rem 2.5rem; }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .footer-logo { font-family: var(--sans); font-size: 1rem; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 0.5rem; }
        .footer-copy { font-size: 0.79rem; color: var(--ink-4); }
        .footer-links { display: flex; gap: 1.5rem; list-style: none; }
        .footer-links a { font-size: 0.8rem; color: var(--ink-4); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: var(--pink-dark); }


        /* ── TOAST NOTIFICATION ── */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #ffffff;
            border-left: 5px solid #10b981; /* Emerald green */
            color: var(--ink);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: var(--sans);
            font-size: 0.9rem;
            font-weight: 600;
            z-index: 9999;
            transform: translateX(150%); /* Hidden off-screen by default */
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .toast-notification.show {
            transform: translateX(0); /* Slides in */
        }
        .toast-icon {
            color: #10b981;
            font-size: 1.25rem;
        }

        /* ── RESPONSIVE ── */
        @media(max-width:960px) {
            .hero-content {
                flex-direction: column-reverse;
                gap: 40px;
                text-align: center;
            }
            .hero-right { text-align: center; }
            .hero-right-features { align-items: center; }
            .hero-logo { margin-bottom: 15px; }
            
            .hero { height: auto; min-height: auto; padding: 7rem 2rem 4rem; }
            .methods-top     { grid-template-columns:1fr; gap:1.5rem; }
            .methods-grid    { grid-template-columns:1fr; }
            .nav-center      { display:none; }
        }
        @media(max-width:600px) {
            nav { padding:0 1.25rem; }
            .contact-card { padding:1.5rem 1.25rem; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .hero { padding: 0; }
        }
    </style>
</head>
<body>

<a href="/login" class="top-right-btn">
    <i class="fas fa-globe"></i> Login
</a>

<section class="hero">
    <div class="hero-content">
        <div class="hero-left">
            <div class="hero-logo">
                <i class="fas fa-brain"></i> StressLab
            </div>

            <div class="hero-box">
                @if(session('error'))
                    <div style="color: #c53030; background: #fff5f5; padding: 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: 500; margin-bottom: 15px; border: 1px solid #feb2b2;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="hero-box-title">Join a Session</div>

                <form action="/join" method="POST" style="margin: 0;">
                    @csrf
                    <input 
                        type="text" 
                        name="access_code" 
                        placeholder="Enter Access Code" 
                        required 
                        maxlength="6" 
                        autocomplete="off"
                    >
                    <button type="submit">Enter</button>
                </form>
            </div>
        </div>
        
        <div class="hero-right">
            <h1>Centralized Stress Induction System</h1>
            <p>A rigorous, validated suite of cognitive stress protocols for psychological research. Participate in secure, real-time analytics sessions.</p>
            <div class="hero-right-features">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-stopwatch"></i></div>
                    Standardized Protocols
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    Real-time Data Analytics
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    Secure & Confidential
                </div>
            </div>
        </div>
    </div>

    <div class="hero-footer">
        Are you a researcher? <a href="#contact">Request Access Here</a>
    </div>
</section>



<section class="contact-section" id="contact">
    <div class="contact-inner">
        <span class="eyebrow reveal">Researcher Access</span>
        <h2 class="sec-title reveal">Join the research network</h2>
        <p class="sec-desc reveal" style="margin:0 auto;text-align:center;">
            Public registration is closed for data security. Submit your institutional request and our team will respond within 48 hours.
        </p>
        <div class="contact-card reveal">
            
            @if(session('success'))
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: left; font-weight: 500; font-size: 0.9rem;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: left; font-weight: 500; font-size: 0.9rem;">
                    ⚠ {{ $errors->first() }}
                </div>
            @endif

            <form action="/request-access" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Dr. Ali" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="institution">Institution Name</label>
                        <input type="text" id="institution" name="institution" placeholder="Universiti Teknologi MARA" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Institution / University Email</label>
                        <input type="email" id="email" name="email" placeholder="ali@uitm.edu.my" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="head_researcher_email">Head Researcher Email</label>
                        <input type="email" id="head_researcher_email" name="head_researcher_email" placeholder="head@uitm.edu.my" required value="{{ old('head_researcher_email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="purpose">Research Purpose</label>
                    <textarea id="purpose" name="purpose" placeholder="Briefly describe your research study, intended sample size, and how you plan to use the stress induction protocols..." required></textarea>
                </div>
                <button type="submit" class="submit-btn">Submit Access Request</button>
            </form>
            
            <p class="form-note">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Your information is kept confidential and used only for access verification.
            </p>
        </div>
    </div>
</section>

<footer>
    <div class="footer-inner">
        <span class="footer-logo"><i class="fas fa-brain" style="color: var(--pink-dark);"></i> StressLab</span>
        <span class="footer-copy">&copy; {{ date('Y') }} Centralized Stress Induction System. All Rights Reserved.</span>
        <ul class="footer-links">
            <li><a href="/login">Login</a></li>
        </ul>
    </div>
</footer>
@if(session('success'))
    <div id="successToast" class="toast-notification">
        <i class="fas fa-check-circle toast-icon"></i>
        <span>{{ session('success') }}</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('successToast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('show');
                }, 4500);
            }
        });
    </script>
@endif
</body>
</html>