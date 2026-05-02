<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stress Test — Centralized Stress Induction Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --black:  #0d0d0d;
            --gray-1: #1a1a1a;
            --gray-2: #333;
            --gray-3: #555;
            --gray-4: #888;
            --gray-5: #bbb;
            --gray-6: #e2e2e2;
            --gray-7: #f5f5f5;
            --white:  #ffffff;
            --blue:   #1a73e8;
            --blue-lt:#e8f0fe;
            --sans: 'Inter', system-ui, sans-serif;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: var(--sans);
            background: var(--white);
            color: var(--black);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed; top:0; left:0; right:0; z-index:200;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(0,0,0,0.07);
        }
        .nav-logo {
            display: flex; align-items: center; gap: 0.5rem;
            text-decoration: none; color: var(--black);
            font-size: 0.95rem; font-weight: 600; letter-spacing: -0.01em;
        }
        .nav-center {
            display: flex; align-items: center; gap: 0.15rem;
            position: absolute; left: 50%; transform: translateX(-50%);
        }
        .nav-link {
            display: inline-flex; align-items: center; gap: 0.3rem;
            color: var(--gray-2); text-decoration: none;
            font-size: 0.875rem; font-weight: 400;
            padding: 0.4rem 0.85rem; border-radius: 6px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-link:hover { background: var(--gray-7); color: var(--black); }
        .nav-right { display: flex; align-items: center; gap: 0.75rem; }
        .btn-nav-cta {
            display: inline-flex; align-items: center; gap: 0.45rem;
            background: var(--black); color: var(--white); text-decoration: none;
            padding: 0.5rem 1.2rem; border-radius: 100px;
            font-size: 0.875rem; font-weight: 500;
            transition: background 0.2s, transform 0.15s;
            white-space: nowrap;
        }
        .btn-nav-cta:hover { background: var(--gray-1); transform: translateY(-1px); }

        /* ── HERO ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 7rem 2rem 5rem;
            overflow: hidden;
            background: var(--white);
        }
        #particleCanvas {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            pointer-events: none; z-index: 0;
        }
        .hero-inner { position: relative; z-index: 1; max-width: 920px; }

        /* Platform badge above headline */
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.75rem;
        }
        .hero-badge-logo {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.9rem; font-weight: 500; color: var(--gray-3);
        }

        .hero h1 {
            font-size: clamp(3rem, 7vw, 5.75rem);
            font-family: 'Google Sans', 'Product Sans', var(--sans);
            font-weight: 400; line-height: 1.05;
            letter-spacing: -0.05em; color: var(--black);
            margin-bottom: 2.25rem;
        }

        .hero-ctas {
            display: flex; align-items: center; justify-content: center;
            gap: 0.75rem; flex-wrap: wrap;
        }
        .cta-primary {
            display: inline-flex; align-items: center; gap: 0.55rem;
            background: var(--black); color: var(--white); text-decoration: none;
            padding: 0.8rem 1.75rem; border-radius: 100px;
            font-size: 0.95rem; font-weight: 600;
            transition: background 0.2s, transform 0.2s;
            white-space: nowrap;
        }
        .cta-primary:hover { background: var(--gray-1); transform: translateY(-2px); }
        .cta-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--gray-7); color: var(--gray-2); text-decoration: none;
            padding: 0.8rem 1.75rem; border-radius: 100px;
            font-size: 0.95rem; font-weight: 500;
            border: 1px solid var(--gray-6);
            transition: background 0.2s, color 0.2s, transform 0.2s;
            white-space: nowrap;
        }
        .cta-secondary:hover { background: var(--gray-6); color: var(--black); transform: translateY(-2px); }

        /* ── TRUST STRIP ── */
        .trust-strip {
            border-top: 1px solid var(--gray-6); border-bottom: 1px solid var(--gray-6);
            padding: 2rem 3rem; background: var(--white);
        }
        .trust-inner {
            max-width: 1000px; margin: 0 auto;
            display: flex; flex-direction: column; align-items: center; gap: 1.25rem;
        }
        .trust-label { font-size: 0.75rem; font-weight: 500; color: var(--gray-4); letter-spacing: 0.07em; text-transform: uppercase; }
        .trust-logos { display: flex; align-items: center; justify-content: center; gap: 3rem; flex-wrap: wrap; }
        .trust-logo  { font-size: 0.85rem; font-weight: 600; color: var(--gray-5); }

        /* ── SECTION BASE ── */
        section { padding: 6rem 2rem; }
        .section-inner { max-width: 1100px; margin: 0 auto; }
        .eyebrow {
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--blue); margin-bottom: 0.8rem; display: block;
        }
        .sec-title {
            font-size: clamp(1.9rem, 3.8vw, 3rem);
            font-weight: 400; line-height: 1.;
            letter-spacing: -0.03em; color: var(--black); margin-bottom: 1rem;
        }
        .sec-desc { font-size: 1rem; line-height: 1.7; color: var(--gray-3); max-width: 500px; }

        /* ── METHODS ── */
        .methods-top {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 4rem; align-items: end; margin-bottom: 4rem;
        }
        .methods-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; }

        .method-card {
            border: 1px solid var(--gray-6); border-radius: 18px;
            padding: 2rem; background: var(--white);
            transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        }
        .method-card:hover {
            border-color: var(--gray-5);
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            transform: translateY(-4px);
        }
        .mc-num  { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; color: var(--gray-5); text-transform: uppercase; margin-bottom: 1rem; }
        .mc-icon { width: 44px; height: 44px; background: var(--gray-7); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1.2rem; }
        .mc-title { font-size: 1.05rem; font-weight: 700; color: var(--black); margin-bottom: 0.55rem; letter-spacing: -0.015em; }
        .mc-desc  { font-size: 0.875rem; line-height: 1.65; color: var(--gray-3); }
        .mc-tag   {
            display: inline-block; margin-top: 1.2rem;
            font-size: 0.68rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--blue); background: var(--blue-lt);
            padding: 0.22rem 0.6rem; border-radius: 100px;
        }

        /* ── STATS BAR (dark) ── */
        .stats-bar { background: var(--black); padding: 4.5rem 2rem; }
        .stats-inner {
            max-width: 1000px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4,1fr);
            gap: 2rem; text-align: center;
        }
        .s-num { font-size: 3rem; font-weight: 800; color: var(--white); letter-spacing: -0.04em; line-height: 1; margin-bottom: 0.4rem; }
        .s-lbl { font-size: 0.75rem; color: rgba(255,255,255,0.4); letter-spacing: 0.07em; text-transform: uppercase; font-weight: 500; }

        /* ── FEATURES ── */
        .features-bg { background: var(--gray-7); }
        .features-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
        .features-list { display: flex; flex-direction: column; gap: 0.4rem; }
        .fi {
            display: flex; gap: 1rem; align-items: flex-start;
            padding: 1rem; border-radius: 12px; transition: background 0.2s;
        }
        .fi:hover { background: rgba(0,0,0,0.04); }
        .fi-icon {
            width: 36px; height: 36px; min-width: 36px;
            background: var(--white); border: 1px solid var(--gray-6);
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            font-size: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .fi-text h4 { font-size: 0.9rem; font-weight: 600; color: var(--black); margin-bottom: 0.2rem; }
        .fi-text p  { font-size: 0.82rem; line-height: 1.55; color: var(--gray-3); }

        /* Demo panel — light theme */
        .demo-panel {
            background: var(--white); border: 1px solid var(--gray-6);
            border-radius: 18px; padding: 1.5rem;
            box-shadow: 0 4px 40px rgba(0,0,0,0.08);
        }
        .panel-bar { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 1.25rem; }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-r{background:#ff5f57} .dot-y{background:#ffbd2e} .dot-g{background:#28c840}
        .panel-bar-title { flex:1; text-align:center; font-size:0.72rem; color:var(--gray-4); font-weight:500; }
        .panel-body { background: var(--gray-7); border-radius: 10px; padding: 1.5rem; text-align: center; }
        .stroop-word { font-size: 2.75rem; font-weight: 900; line-height: 1; margin-bottom: 0.5rem; letter-spacing: -0.03em; }
        .stroop-inst { font-size: 0.7rem; color: var(--gray-4); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.2rem; font-weight: 500; }
        .stroop-btns { display: flex; gap: 0.45rem; justify-content: center; flex-wrap: wrap; }
        .s-btn {
            padding: 0.38rem 0.9rem; border-radius: 100px; font-size: 0.8rem; font-weight: 500;
            border: 1px solid var(--gray-6); background: var(--white); color: var(--gray-3); cursor: default;
        }
        .s-btn.active { background: var(--black); color: var(--white); border-color: var(--black); }
        .panel-metrics { display: grid; grid-template-columns: repeat(3,1fr); gap: 0.75rem; margin-top: 0.9rem; }
        .m-box { background: var(--white); border: 1px solid var(--gray-6); border-radius: 10px; padding: 0.75rem; text-align: center; }
        .m-val { font-size: 1.25rem; font-weight: 800; color: var(--black); letter-spacing: -0.02em; }
        .m-lbl { font-size: 0.62rem; color: var(--gray-4); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 500; }

        /* ── CONTACT ── */
        .contact-section { background: var(--white); }
        .contact-inner { max-width: 620px; margin: 0 auto; text-align: center; }
        .contact-card {
            background: var(--white); border: 1px solid var(--gray-6);
            border-radius: 20px; padding: 2.75rem;
            margin-top: 2.5rem; text-align: left;
            box-shadow: 0 4px 40px rgba(0,0,0,0.06);
        }
        .form-group { margin-bottom: 1.1rem; }
        .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--black); margin-bottom: 0.4rem; }
        .form-group input, .form-group textarea {
            width: 100%; background: var(--white);
            border: 1px solid var(--gray-6); border-radius: 10px;
            padding: 0.75rem 1rem; color: var(--black);
            font-family: var(--sans); font-size: 0.9rem; outline: none; resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input::placeholder, .form-group textarea::placeholder { color: var(--gray-5); }
        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--black); box-shadow: 0 0 0 3px rgba(0,0,0,0.06);
        }
        .form-group textarea { min-height: 110px; }
        .submit-btn {
            width: 100%; background: var(--black); color: var(--white);
            border: none; border-radius: 100px;
            padding: 0.9rem 2rem; font-family: var(--sans);
            font-size: 0.95rem; font-weight: 600; cursor: pointer;
            transition: all 0.2s; margin-top: 0.5rem;
        }
        .submit-btn:hover { background: var(--gray-1); transform: translateY(-1px); }
        .form-note { display: flex; align-items: center; gap: 0.4rem; font-size: 0.76rem; color: var(--gray-4); margin-top: 1rem; justify-content: center; }

        /* ── FOOTER ── */
        footer { border-top: 1px solid var(--gray-6); padding: 2rem 2.5rem; }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .footer-logo { font-size: 0.9rem; font-weight: 600; color: var(--black); }
        .footer-copy { font-size: 0.79rem; color: var(--gray-4); }
        .footer-links { display: flex; gap: 1.5rem; list-style: none; }
        .footer-links a { font-size: 0.8rem; color: var(--gray-4); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: var(--black); }

        /* ── DIVIDER ── */
        .divider { height: 1px; background: var(--gray-6); max-width: 1100px; margin: 0 auto; }

        /* ── REVEAL ── */
        /* Removed */

        /* ── RESPONSIVE ── */
        @media(max-width:960px) {
            .methods-top     { grid-template-columns:1fr; gap:1.5rem; }
            .methods-grid    { grid-template-columns:1fr; }
            .features-layout { grid-template-columns:1fr; gap:3rem; }
            .stats-inner     { grid-template-columns:repeat(2,1fr); }
            .nav-center      { display:none; }
        }
        @media(max-width:600px) {
            nav { padding:0 1.25rem; }
            .stats-inner  { grid-template-columns:1fr 1fr; }
            .contact-card { padding:1.75rem 1.25rem; }
        }
    </style>
</head>
<body>

<!-- ── NAV ── -->
<nav>
    <a href="#" class="nav-logo">
        <!-- Triangular A mark echoing Antigravity -->
        <svg width="24" height="24" viewBox="0 0 28 28" fill="none">
            <polygon points="14,2 27,24 1,24" fill="none" stroke="#1a73e8" stroke-width="2.2" stroke-linejoin="round"/>
            <polygon points="14,9 21,22 7,22" fill="#1a73e8" opacity="0.2"/>
        </svg>
        CPSIM
    </a>

    <div class="nav-center">
        <a href="#about"    class="nav-link">Methods</a>
        <a href="#features" class="nav-link">Platform</a>
        <a href="#contact"  class="nav-link">Access</a>
    </div>

    <div class="nav-right">
        <a href="/login" class="btn-nav-cta">
            Researcher Login
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
    <canvas id="particleCanvas"></canvas>
    <div class="hero-inner">
        <div class="hero-badge">
            <div class="hero-badge-logo">
                <svg width="16" height="16" viewBox="0 0 28 28" fill="none">
                    <polygon points="14,2 27,24 1,24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linejoin="round"/>
                    <polygon points="14,9 21,22 7,22" fill="#1a73e8" opacity="0.25"/>
                </svg>
                CPSIM
            </div>
        </div>

        <h1>Centralized platform for<br>stress induction methods</h1>

        <div class="hero-ctas">
            <a href="/login" class="cta-primary">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/></svg>
                Go to Dashboard
            </a>
            <a href="#contact" class="cta-secondary">
                Request Access
            </a>
        </div>
    </div>
</section>


<!-- ── METHODS ── -->
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


<!-- ── FEATURES ── -->
<section class="features-bg" id="features">
    <div class="section-inner">
        <div class="features-layout">
            <div>
                <span class="eyebrow reveal">Platform Capabilities</span>
                <h2 class="sec-title reveal">Built for rigorous psychological research</h2>
                <p class="sec-desc reveal" style="margin-bottom:2rem;">Every component is designed with research validity and data integrity at its core.</p>
                <div class="features-list">
                    <div class="fi reveal">
                        <div class="fi-icon">📊</div>
                        <div class="fi-text">
                            <h4>Real-Time Analytics Dashboard</h4>
                            <p>Monitor participant response metrics, reaction times, and accuracy scores as sessions progress with live data visualization.</p>
                        </div>
                    </div>
                    <div class="fi reveal d1">
                        <div class="fi-icon">🔐</div>
                        <div class="fi-text">
                            <h4>Institutional Access Control</h4>
                            <p>Role-based access management for research teams with secure participant data storage compliant with academic ethics requirements.</p>
                        </div>
                    </div>
                    <div class="fi reveal d2">
                        <div class="fi-icon">⚙️</div>
                        <div class="fi-text">
                            <h4>Adaptive Protocol Configuration</h4>
                            <p>Customize difficulty, timing, and feedback conditions to match your specific experimental design requirements.</p>
                        </div>
                    </div>
                    <div class="fi reveal d3">
                        <div class="fi-icon">📁</div>
                        <div class="fi-text">
                            <h4>Export & Integration Ready</h4>
                            <p>Export raw session data in CSV, JSON, or SPSS formats. API endpoints available for integration with existing research pipelines.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reveal d2">
                <div class="demo-panel">
                    <div class="panel-bar">
                        <div class="dot dot-r"></div>
                        <div class="dot dot-y"></div>
                        <div class="dot dot-g"></div>
                        <div class="panel-bar-title">Stroop Test — Trial <span id="trialNum">12</span> of 40</div>
                    </div>
                    <div class="panel-body">
                        <div class="stroop-word" id="stroopWord" style="color:#1a73e8">RED</div>
                        <div class="stroop-inst">Select the ink colour</div>
                        <div class="stroop-btns">
                            <div class="s-btn" style="color:#dc2626">Red</div>
                            <div class="s-btn active">Blue</div>
                            <div class="s-btn" style="color:#16a34a">Green</div>
                            <div class="s-btn" style="color:#ca8a04">Yellow</div>
                        </div>
                    </div>
                    <div class="panel-metrics">
                        <div class="m-box"><div class="m-val" id="rt">342ms</div><div class="m-lbl">Reaction</div></div>
                        <div class="m-box"><div class="m-val" id="acc">91%</div><div class="m-lbl">Accuracy</div></div>
                        <div class="m-box"><div class="m-val" id="rem">28</div><div class="m-lbl">Remaining</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── REQUEST ACCESS ── -->
<section class="contact-section" id="contact">
    <div class="contact-inner">
        <span class="eyebrow reveal">Researcher Access</span>
        <h2 class="sec-title reveal">Join the research network</h2>
        <p class="sec-desc reveal" style="margin:0 auto;text-align:center;">
            Public registration is closed for data security. Submit your institutional request and our team will respond within 48 hours.
        </p>
        <div class="contact-card reveal">
            <form action="#" method="GET" onsubmit="handleSubmit(event)">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Dr. Jane Doe" required>
                </div>
                <div class="form-group">
                    <label for="email">Institution / University Email</label>
                    <input type="email" id="email" name="email" placeholder="jane@university.edu" required>
                </div>
                <div class="form-group">
                    <label for="purpose">Research Purpose</label>
                    <textarea id="purpose" name="purpose" placeholder="Briefly describe your research study, institution, and intended use of the platform..." required></textarea>
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

<!-- ── FOOTER ── -->
<footer>
    <div class="footer-inner">
        <span class="footer-logo">Stress System</span>
        <span class="footer-copy">&copy; {{ date('Y') }} Centralized Stress Induction System. All Rights Reserved.</span>
        <ul class="footer-links">
            <li><a href="#about">Methods</a></li>
            <li><a href="#contact">Access</a></li>
            <li><a href="/login">Login</a></li>
        </ul>
    </div>
</footer>

<script>
/* ── PARTICLE CANVAS (scattered blue dots like Antigravity) ── */
(function () {
    const canvas = document.getElementById('particleCanvas');
    const ctx = canvas.getContext('2d');
    let W, H, pts = [];

    function resize() {
        W = canvas.width  = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function Pt() { this.reset(); }
    Pt.prototype.reset = function () {
        this.x  = Math.random() * W;
        this.y  = Math.random() * H;
        this.r  = Math.random() * 2.2 + 0.4;
        this.vx = (Math.random() - 0.5) * 0.3;
        this.vy = (Math.random() - 0.5) * 0.3;
        const isBlue = Math.random() > 0.2;
        const a = (Math.random() * 0.45 + 0.12).toFixed(2);
        this.color = isBlue ? `rgba(26,115,232,${a})` : `rgba(0,0,0,${(+a * 0.18).toFixed(2)})`;
    };
    Pt.prototype.update = function () {
        this.x += this.vx; this.y += this.vy;
        if (this.x < -8) this.x = W + 8;
        if (this.x > W + 8) this.x = -8;
        if (this.y < -8) this.y = H + 8;
        if (this.y > H + 8) this.y = -8;
    };
    Pt.prototype.draw = function () {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
    };

    function init() {
        resize();
        const n = Math.max(80, Math.min(Math.floor((W * H) / 7000), 220));
        pts = Array.from({ length: n }, () => new Pt());
    }

    function loop() {
        ctx.clearRect(0, 0, W, H);
        pts.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(loop);
    }

    window.addEventListener('resize', resize);
    init(); loop();
})();

/* ── SCROLL REVEAL ── */
// Removed

/* ── STROOP DEMO ── */
const trials = [
    { word:'RED',    color:'#1a73e8', active:1 },
    { word:'GREEN',  color:'#ca8a04', active:3 },
    { word:'BLUE',   color:'#16a34a', active:2 },
    { word:'YELLOW', color:'#dc2626', active:0 },
];
const rts = ['342ms','418ms','289ms','376ms'];
const accs = ['91%','87%','94%','89%'];
let ti = 0;
const wordEl  = document.getElementById('stroopWord');
const btns    = document.querySelectorAll('.s-btn');
const trialEl = document.getElementById('trialNum');
const rtEl    = document.getElementById('rt');
const accEl   = document.getElementById('acc');
const remEl   = document.getElementById('rem');

setInterval(() => {
    ti = (ti + 1) % trials.length;
    const t = trials[ti];
    wordEl.textContent = t.word;
    wordEl.style.color = t.color;
    btns.forEach((b, i) => b.classList.toggle('active', i === t.active));
    trialEl.textContent = 12 + ti * 4;
    rtEl.textContent    = rts[ti];
    accEl.textContent   = accs[ti];
    remEl.textContent   = String(28 - ti * 4);
}, 2600);

/* ── FORM ── */
function handleSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('.submit-btn');
    btn.textContent = '✓ Request submitted!';
    btn.style.background = '#16a34a';
    btn.disabled = true;
}
</script>

</body>
</html>