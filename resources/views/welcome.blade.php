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

        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }
        .nav-logo {
            display: flex; align-items: center; gap: 0.5rem;
            text-decoration: none; color: var(--ink);
            font-size: 1.1rem; font-weight: 700; letter-spacing: -0.02em;
            font-family: var(--sans);
        }
        .nav-logo-dot { color: var(--pink-bright); }
        .nav-center {
            display: flex; align-items: center; gap: 0.15rem;
            position: absolute; left: 50%; transform: translateX(-50%);
        }
        .nav-link {
            display: inline-flex; align-items: center; gap: 0.3rem;
            color: var(--ink-3); text-decoration: none;
            font-size: 0.875rem; font-weight: 400;
            padding: 0.4rem 0.85rem; border-radius: 6px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-link:hover { background: var(--pink-pale); color: var(--pink-dark); }
        .nav-right { display: flex; align-items: center; gap: 0.75rem; }
        .btn-nav-cta {
            display: inline-flex; align-items: center; gap: 0.45rem;
            background: var(--pink-dark); color: var(--white); text-decoration: none;
            padding: 0.5rem 1.2rem; border-radius: 100px;
            font-size: 0.875rem; font-weight: 500;
            transition: background 0.2s, transform 0.15s;
            white-space: nowrap;
        }
        .btn-nav-cta:hover { background: var(--pink-mid); transform: translateY(-1px); }

        .hero {
            position: relative;
            height: 100vh;
            min-height: 650px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            padding: 6rem 4rem 2rem;
            overflow: hidden;
            background: var(--white);
        }

        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 80% 50%, rgba(196,43,99,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 40% 50% at 20% 80%, rgba(155,28,74,0.05) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 60% 10%, rgba(232,53,110,0.06) 0%, transparent 55%);
            pointer-events: none; z-index: 0;
        }

        .hero-left {
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: flex-start;
        }

        .hero h1 {
            font-family: var(--sans);
            font-size: clamp(2.6rem, 5vw, 4.2rem);
            font-weight: 400; line-height: 1.08;
            letter-spacing: -0.02em; color: var(--ink);
            margin-bottom: 1.25rem;
        }
        .hero h1 em { font-style: italic; color: var(--pink-dark); }

        .hero-sub {
            font-size: 1rem; line-height: 1.7; color: var(--ink-3);
            max-width: 400px; margin-bottom: 2rem;
        }

        .hero-ctas {
            display: block; 
            margin-bottom: 2.5rem;
            margin-top: 1rem;
        }
        
        .hero-right {
            position: relative; z-index: 1;
            display: flex; align-items: center; justify-content: center;
            height: 100%;
        }
        .hero-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            animation: floatImage 6s ease-in-out infinite;
            filter: drop-shadow(0 20px 30px rgba(155, 28, 74, 0.08));
        }
        @keyframes floatImage {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-15px); }
        }

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

        .contact-section { background: var(--white); }
        .contact-inner { max-width: 620px; margin: 0 auto; text-align: center; }
        .contact-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 20px; padding: 2.75rem;
            margin-top: 2.5rem; text-align: left;
            box-shadow: 0 4px 40px rgba(155,28,74,0.08);
        }
        .form-group { margin-bottom: 1.1rem; }
        .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--ink); margin-bottom: 0.4rem; }
        .form-group input, .form-group textarea {
            width: 100%; background: var(--white);
            border: 1px solid var(--border); border-radius: 10px;
            padding: 0.75rem 1rem; color: var(--ink);
            font-family: var(--sans); font-size: 0.9rem; outline: none; resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input::placeholder, .form-group textarea::placeholder { color: var(--ink-5); }
        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--pink-dark); box-shadow: 0 0 0 3px rgba(155,28,74,0.08);
        }
        .form-group textarea { min-height: 110px; }
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
            .hero { grid-template-columns: 1fr; height: auto; min-height: auto; padding: 7rem 2rem 4rem; }
            .hero-right { height: 340px; margin-top: 2rem; }
            .methods-top     { grid-template-columns:1fr; gap:1.5rem; }
            .methods-grid    { grid-template-columns:1fr; }
            .nav-center      { display:none; }
        }
        @media(max-width:600px) {
            nav { padding:0 1.25rem; }
            .contact-card { padding:1.75rem 1.25rem; }
            .hero { padding: 7rem 1.5rem 3rem; }
        }
    </style>
</head>
<body>

<nav>
    <a href="#" class="nav-logo">
        <i class="fas fa-brain" style="color: var(--pink-dark); font-size: 1.25rem;"></i>
        StressLab<span class="nav-logo-dot">·</span>
    </a>

    <div class="nav-center">
        <a href="#about"    class="nav-link">Methods</a>
        <a href="#contact"  class="nav-link">Access</a>
    </div>

    <div class="nav-right">
        <a href="/login" class="btn-nav-cta">
            Researcher Login
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</nav>

<section class="hero">

    <div class="hero-left">
        <h1>Centralized platform for<br><em>stress induction</em><br>methods</h1>
        <p class="hero-sub">
            A rigorous, validated suite of cognitive stress protocols for psychological research — Stroop, MIST, and TSST with real-time analytics.
        </p>

        <div class="hero-ctas">
            @if(session('error'))
                <div style="color: #c53030; background: #fff5f5; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; margin-bottom: 1rem; border: 1px solid #feb2b2; display: inline-block; animation: fadeIn 0.3s ease-in-out;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i> {{ session('error') }}
                </div>
            @endif

            <form action="/join" method="POST" style="display: flex; gap: 0.5rem; max-width: 420px; width: 100%;">
                @csrf
                <input 
                    type="text" 
                    name="access_code" 
                    placeholder="Enter 6-digit PIN..." 
                    required 
                    maxlength="6" 
                    autocomplete="off"
                    style="flex: 1; padding: 0.85rem 1.5rem; font-size: 1.1rem; font-family: var(--mono); letter-spacing: 2px; text-transform: uppercase; color: var(--ink); background: var(--white); border: 2px solid var(--border); border-radius: 50px; outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
                    onfocus="this.style.borderColor='var(--pink-dark)'; this.style.boxShadow='0 0 0 3px var(--shadow-pink)';"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';"
                >
                
                <button 
                    type="submit" 
                    style="background: var(--pink-dark); color: var(--white); border: none; padding: 0.85rem 2rem; border-radius: 50px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: background 0.2s, transform 0.1s; box-shadow: 0 4px 15px var(--shadow-pink); white-space: nowrap;"
                    onmouseover="this.style.background='var(--pink-mid)'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.background='var(--pink-dark)'; this.style.transform='translateY(0)';"
                >
                    Start Test <span style="margin-left: 5px;">&rarr;</span>
                </button>
            </form>

            <p style="font-size: 0.85rem; color: var(--ink-3); margin-top: 1rem; margin-left: 0.5rem;">
                Are you a researcher? <a href="/login" style="color: var(--pink-dark); font-weight: 600; text-decoration: none;">Log in here</a>.
            </p>
        </div>
    </div>
    
    <div class="hero-right">
        <img src="{{ asset('images/hero-image.png') }}" alt="StressLab Illustration" class="hero-image">
    </div>

</section>

<section id="about">
    <div class="section-inner">
        <div class="methods-top">
            <div class="reveal">
                <span class="eyebrow">Induction Methods</span>
                <h2 class="sec-title">Three validated approaches to stress induction</h2>
            </div>
            <p class="sec-desc reveal d1">Each protocol follows established psychological research standards with automated scoring, reaction time logging, and adaptive difficulty calibration.</p>
        </div>
        <div class="methods-grid">
            <div class="method-card reveal">
                <div class="mc-num">01</div>
                <div class="mc-icon">🎨</div>
                <h3 class="mc-title">Stroop Test</h3>
                <p class="mc-desc">Measure cognitive interference and selective attention with automated reaction time tracking and accuracy scoring across congruent and incongruent trials.</p>
                <span class="mc-tag">Cognitive Interference</span>
            </div>
            <div class="method-card reveal d1">
                <div class="mc-num">02</div>
                <div class="mc-icon">🧮</div>
                <h3 class="mc-title">MIST</h3>
                <p class="mc-desc">Induce stress through time-pressured arithmetic tasks with real-time feedback, dynamic difficulty adjustment, and performance comparison feedback.</p>
                <span class="mc-tag">Arithmetic Stress</span>
            </div>
            <div class="method-card reveal d2">
                <div class="mc-num">03</div>
                <div class="mc-icon">🎤</div>
                <h3 class="mc-title">TSST Arithmetic</h3>
                <p class="mc-desc">Standardized serial subtraction tasks designed to trigger acute psychological stress under evaluative observation and time constraint conditions.</p>
                <span class="mc-tag">Social Evaluation</span>
            </div>
        </div>
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
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Dr. Ali" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Institution / University Email</label>
                    <input type="email" id="email" name="email" placeholder="ali@uitm.edu.my" required>
                </div>

                <div class="form-group">
                    <label for="institution">Institution Name</label>
                    <input type="text" id="institution" name="institution" placeholder="Universiti Teknologi MARA" required>
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
            <li><a href="#about">Methods</a></li>
            <li><a href="#contact">Access</a></li>
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