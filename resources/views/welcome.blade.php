<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stress Test — Centralized Stress Induction Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

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

        /* ── NAV ── */
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
            font-size: 1rem; font-weight: 700; letter-spacing: -0.02em;
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

        /* ── HERO ── */
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

        /* Mesh background */
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 80% 50%, rgba(196,43,99,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 40% 50% at 20% 80%, rgba(155,28,74,0.05) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 60% 10%, rgba(232,53,110,0.06) 0%, transparent 55%);
            pointer-events: none; z-index: 0;
        }

        #particleCanvas {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            pointer-events: none; z-index: 0;
        }

        .hero-left {
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: flex-start;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--pink-pale); border: 1px solid var(--border);
            border-radius: 100px; padding: 0.3rem 1rem 0.3rem 0.5rem;
            margin-bottom: 1.5rem;
        }
        .hero-badge-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--pink-bright); animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.6; transform: scale(1.3); }
        }
        .hero-badge span { font-size: 0.75rem; font-weight: 500; color: var(--pink-dark); }

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
            display: flex; align-items: center;
            gap: 0.75rem; flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }
        .cta-primary {
            display: inline-flex; align-items: center; gap: 0.55rem;
            background: var(--pink-dark); color: var(--white); text-decoration: none;
            padding: 0.85rem 1.8rem; border-radius: 100px;
            font-size: 0.95rem; font-weight: 600;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
            white-space: nowrap;
            box-shadow: 0 4px 24px rgba(155,28,74,0.3);
        }
        .cta-primary:hover {
            background: var(--pink-mid);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(155,28,74,0.4);
        }
        .cta-play {
            display: inline-flex; align-items: center; justify-content: center;
            width: 48px; height: 48px; border-radius: 50%;
            background: var(--white); border: 1.5px solid var(--border);
            color: var(--pink-dark);
            box-shadow: 0 2px 12px var(--shadow-pink);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .cta-play:hover { transform: scale(1.1); box-shadow: 0 4px 20px var(--shadow-pink); }

        .hero-stats {
            display: flex; align-items: center; gap: 2rem;
        }
        .h-stat { display: flex; flex-direction: column; gap: 0.1rem; }
        .h-stat-num { font-family: var(--sans); font-size: 1.5rem; font-weight: 600; color: var(--ink); letter-spacing: -0.02em; }
        .h-stat-lbl { font-size: 0.7rem; font-weight: 500; color: var(--ink-4); text-transform: uppercase; letter-spacing: 0.07em; }
        .h-stat-divider { width: 1px; height: 36px; background: var(--border); }

        /* ── HERO CARDS (right side) ── */
        .hero-right {
            position: relative; z-index: 1;
            display: flex; align-items: center; justify-content: center;
            height: 480px;
        }

        .cards-stack {
            position: relative;
            width: 340px; height: 420px;
        }

        .hero-card {
            position: absolute;
            width: 100%; border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(155,28,74,0.18);
            transition: transform 0.6s cubic-bezier(0.34,1.56,0.64,1);
            cursor: default;
        }
        .hero-card:hover { transform: translateY(-8px) rotate(0deg) !important; }

        .card-1 {
            background: linear-gradient(135deg, #9b1c4a 0%, #c42b63 50%, #e8356e 100%);
            top: 0; left: 0; z-index: 3;
            transform: rotate(-1.5deg);
            animation: float1 6s ease-in-out infinite;
        }
        .card-2 {
            background: linear-gradient(135deg, #f7c5d8 0%, #f0a0c0 50%, #e07090 100%);
            top: 40px; left: 20px; z-index: 2;
            transform: rotate(1.2deg);
            animation: float2 7s ease-in-out infinite;
        }
        .card-3 {
            background: linear-gradient(135deg, #fff5f8 0%, #fde8f0 50%, #f9cfe0 100%);
            top: 80px; left: 40px; z-index: 1;
            transform: rotate(3deg);
            animation: float3 8s ease-in-out infinite;
        }

        @keyframes float1 {
            0%,100% { transform: rotate(-1.5deg) translateY(0); }
            50%      { transform: rotate(-1.5deg) translateY(-8px); }
        }
        @keyframes float2 {
            0%,100% { transform: rotate(1.2deg) translateY(0); }
            50%      { transform: rotate(1.2deg) translateY(-5px); }
        }
        @keyframes float3 {
            0%,100% { transform: rotate(3deg) translateY(0); }
            50%      { transform: rotate(3deg) translateY(-3px); }
        }

        .card-label {
            font-family: var(--sans); font-size: 1.6rem; line-height: 1.2;
            margin-bottom: 1.5rem; letter-spacing: -0.01em;
        }
        .card-1 .card-label { color: var(--white); }
        .card-2 .card-label { color: var(--ink-2); }
        .card-3 .card-label { color: var(--ink-3); }

        .card-tag {
            display: inline-block;
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;
            padding: 0.25rem 0.7rem; border-radius: 100px;
        }
        .card-1 .card-tag { background: rgba(255,255,255,0.2); color: rgba(255,255,255,0.9); }
        .card-2 .card-tag { background: rgba(155,28,74,0.12); color: var(--pink-dark); }
        .card-3 .card-tag { background: var(--pink-pale); color: var(--pink-dark); border: 1px solid var(--border); }

        .card-bottom {
            position: absolute; bottom: 1.5rem; left: 2rem; right: 2rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-bottom-text { font-size: 0.75rem; font-weight: 500; }
        .card-1 .card-bottom-text { color: rgba(255,255,255,0.7); }
        .card-2 .card-bottom-text { color: var(--ink-3); }
        .card-3 .card-bottom-text { color: var(--ink-4); }

        /* ── EXPLORE BADGE ── */
        .explore-badge {
            position: absolute; left: -30px; bottom: 80px; z-index: 10;
            width: 68px; height: 68px;
        }
        .explore-badge svg { width: 100%; height: 100%; animation: spin 12s linear infinite; }
        .explore-badge-arrow {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: var(--pink-dark);
        }

        /* ── TRUST STRIP ── */
        .trust-strip {
            border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            padding: 2rem 3rem; background: var(--pink-ultra);
        }
        .trust-inner {
            max-width: 1000px; margin: 0 auto;
            display: flex; flex-direction: column; align-items: center; gap: 1.25rem;
        }
        .trust-label { font-size: 0.72rem; font-weight: 500; color: var(--ink-4); letter-spacing: 0.09em; text-transform: uppercase; }
        .trust-logos { display: flex; align-items: center; justify-content: center; gap: 3rem; flex-wrap: wrap; }
        .trust-logo  { font-size: 0.85rem; font-weight: 600; color: var(--ink-5); }

        /* ── SECTION BASE ── */
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

        /* ── METHODS ── */
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

        /* ── STATS BAR ── */
        .stats-bar { background: var(--pink-dark); padding: 4.5rem 2rem; }
        .stats-inner {
            max-width: 1000px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4,1fr);
            gap: 2rem; text-align: center;
        }
        .s-num { font-family: var(--sans); font-size: 3rem; font-weight: 600; color: var(--white); letter-spacing: -0.04em; line-height: 1; margin-bottom: 0.4rem; }
        .s-lbl { font-size: 0.75rem; color: rgba(255,255,255,0.5); letter-spacing: 0.07em; text-transform: uppercase; font-weight: 500; }

        /* ── FEATURES ── */
        .features-bg { background: var(--pink-ultra); }
        .features-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
        .features-list { display: flex; flex-direction: column; gap: 0.4rem; }
        .fi {
            display: flex; gap: 1rem; align-items: flex-start;
            padding: 1rem; border-radius: 12px; transition: background 0.2s;
        }
        .fi:hover { background: rgba(155,28,74,0.04); }
        .fi-icon {
            width: 36px; height: 36px; min-width: 36px;
            background: var(--white); border: 1px solid var(--border);
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            font-size: 1rem; box-shadow: 0 1px 4px var(--shadow-pink);
        }
        .fi-text h4 { font-size: 0.9rem; font-weight: 600; color: var(--ink); margin-bottom: 0.2rem; }
        .fi-text p  { font-size: 0.82rem; line-height: 1.55; color: var(--ink-3); }

        /* Demo panel */
        .demo-panel {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 20px; padding: 1.5rem;
            box-shadow: 0 4px 40px rgba(155,28,74,0.1);
        }
        .panel-bar { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 1.25rem; }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-r{background:#ff5f57} .dot-y{background:#ffbd2e} .dot-g{background:#28c840}
        .panel-bar-title { flex:1; text-align:center; font-size:0.72rem; color:var(--ink-4); font-weight:500; }
        .panel-body { background: var(--pink-pale); border-radius: 10px; padding: 1.5rem; text-align: center; }
        .stroop-word { font-family: var(--sans); font-size: 2.75rem; font-weight: 700; line-height: 1; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
        .stroop-inst { font-size: 0.7rem; color: var(--ink-4); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.2rem; font-weight: 500; }
        .stroop-btns { display: flex; gap: 0.45rem; justify-content: center; flex-wrap: wrap; }
        .s-btn {
            padding: 0.38rem 0.9rem; border-radius: 100px; font-size: 0.8rem; font-weight: 500;
            border: 1px solid var(--border); background: var(--white); color: var(--ink-3); cursor: default;
        }
        .s-btn.active { background: var(--pink-dark); color: var(--white); border-color: var(--pink-dark); }
        .panel-metrics { display: grid; grid-template-columns: repeat(3,1fr); gap: 0.75rem; margin-top: 0.9rem; }
        .m-box { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem; text-align: center; }
        .m-val { font-family: var(--sans); font-size: 1.25rem; font-weight: 600; color: var(--ink); letter-spacing: -0.02em; }
        .m-lbl { font-size: 0.62rem; color: var(--ink-4); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 500; }

        /* ── CONTACT ── */
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

        /* ── FOOTER ── */
        footer { border-top: 1px solid var(--border); padding: 2rem 2.5rem; }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .footer-logo { font-family: var(--sans); font-size: 1rem; font-weight: 600; color: var(--ink); }
        .footer-copy { font-size: 0.79rem; color: var(--ink-4); }
        .footer-links { display: flex; gap: 1.5rem; list-style: none; }
        .footer-links a { font-size: 0.8rem; color: var(--ink-4); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: var(--pink-dark); }

        /* ── DIVIDER ── */
        .divider { height: 1px; background: var(--border); max-width: 1100px; margin: 0 auto; }

        /* ── RESPONSIVE ── */
        @media(max-width:960px) {
            .hero { grid-template-columns: 1fr; height: auto; min-height: auto; padding: 7rem 2rem 4rem; }
            .hero-right { height: 340px; margin-top: 3rem; }
            .cards-stack { width: 280px; height: 340px; }
            .methods-top     { grid-template-columns:1fr; gap:1.5rem; }
            .methods-grid    { grid-template-columns:1fr; }
            .features-layout { grid-template-columns:1fr; gap:3rem; }
            .stats-inner     { grid-template-columns:repeat(2,1fr); }
            .nav-center      { display:none; }
            .explore-badge   { display: none; }
        }
        @media(max-width:600px) {
            nav { padding:0 1.25rem; }
            .stats-inner  { grid-template-columns:1fr 1fr; }
            .contact-card { padding:1.75rem 1.25rem; }
            .hero { padding: 7rem 1.5rem 3rem; }
        }
    </style>
</head>
<body>

<!-- ── NAV ── -->
<nav>
    <a href="#" class="nav-logo">
        <svg width="22" height="22" viewBox="0 0 28 28" fill="none">
            <polygon points="14,2 27,24 1,24" fill="none" stroke="#9b1c4a" stroke-width="2.2" stroke-linejoin="round"/>
            <polygon points="14,9 21,22 7,22" fill="#9b1c4a" opacity="0.25"/>
        </svg>
        CPSIM<span class="nav-logo-dot">·</span>
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

    <!-- LEFT -->
    <div class="hero-left">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            <span>Stress Induction Platform · Est. 2026</span>
        </div>

        <h1>Centralized platform for<br><em>stress induction</em><br>methods</h1>

        <p class="hero-sub">
            A rigorous, validated suite of cognitive stress protocols for psychological research — Stroop, MIST, and TSST with real-time analytics.
        </p>

        <div class="hero-ctas">
            <a href="/login" class="cta-primary">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/></svg>
                Go to Dashboard
            </a>
            <a href="#contact" class="cta-play" title="Request Access">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="hero-stats">
            <div class="h-stat">
                <span class="h-stat-num">3</span>
                <span class="h-stat-lbl">Protocols</span>
            </div>
            <div class="h-stat-divider"></div>
            <div class="h-stat">
                <span class="h-stat-num">12M+</span>
                <span class="h-stat-lbl">Trials logged</span>
            </div>
            <div class="h-stat-divider"></div>
            <div class="h-stat">
                <span class="h-stat-num">94%</span>
                <span class="h-stat-lbl">Validity rate</span>
            </div>
        </div>
    </div>

    <!-- RIGHT: stacked cards -->
    <div class="hero-right">
        <div class="cards-stack">
            <!-- Bottom card -->
            <div class="hero-card card-3">
                <div class="card-label">to the<br>Sequence</div>
                <div class="card-tag">TSST Arithmetic</div>
                <div class="card-bottom">
                    <span class="card-bottom-text">Learn More →</span>
                </div>
            </div>
            <!-- Middle card -->
            <div class="hero-card card-2">
                <div class="card-label">Feature<br>Overview</div>
                <div class="card-tag">Platform</div>
                <div class="card-bottom">
                    <span class="card-bottom-text">Learn More →</span>
                </div>
            </div>
            <!-- Top card -->
            <div class="hero-card card-1">
                <div class="card-label">Welcome<br>to the<br>Sequence</div>
                <div class="card-tag">Stroop · MIST</div>
                <div class="card-bottom">
                    <span class="card-bottom-text">Learn More →</span>
                </div>
            </div>

            <!-- Explore spinning badge -->
            <div class="explore-badge">
                <svg viewBox="0 0 100 100">
                    <defs>
                        <path id="circle-text" d="M 50,50 m -35,0 a 35,35 0 1,1 70,0 a 35,35 0 1,1 -70,0"/>
                    </defs>
                    <circle cx="50" cy="50" r="48" fill="#1a0a10" stroke="rgba(155,28,74,0.3)" stroke-width="1"/>
                    <text font-size="10.5" font-family="Sora, sans-serif" font-weight="500" fill="#f7c5d8" letter-spacing="3">
                        <textPath href="#circle-text">EXPLORE MORE · EXPLORE MORE ·</textPath>
                    </text>
                </svg>
                <div class="explore-badge-arrow">▼</div>
            </div>
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


<!-- ── STATS BAR ── -->
<div class="stats-bar">
    <div class="stats-inner">
        <div><div class="s-num">3</div><div class="s-lbl">Stress Protocols</div></div>
        <div><div class="s-num">48h</div><div class="s-lbl">Access Response</div></div>
        <div><div class="s-num">100%</div><div class="s-lbl">Ethics Compliant</div></div>
        <div><div class="s-num">∞</div><div class="s-lbl">Session Capacity</div></div>
    </div>
</div>


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
                        <div class="stroop-word" id="stroopWord" style="color:#9b1c4a">RED</div>
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
            
            <!-- SUCCESS NOTIFICATION -->
            @if(session('success'))
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: left; font-weight: 500; font-size: 0.9rem;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- ERROR NOTIFICATION -->
            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: left; font-weight: 500; font-size: 0.9rem;">
                    ⚠ {{ $errors->first() }}
                </div>
            @endif

            <!-- THE FORM -->
            <form action="/request-access" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <!-- Note the name attribute is "name", not "full_name" -->
                    <input type="text" id="name" name="name" placeholder="Dr. Jane Doe" required>
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
/* ── PARTICLE CANVAS (soft pink dots) ── */
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
        const isPink = Math.random() > 0.2;
        const a = (Math.random() * 0.35 + 0.08).toFixed(2);
        this.color = isPink
            ? `rgba(196,43,99,${a})`
            : `rgba(155,28,74,${(+a * 0.4).toFixed(2)})`;
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

/* ── STROOP DEMO ── */
const trials = [
    { word:'RED',    color:'#9b1c4a', active:1 },
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
</script>

</body>
</html>