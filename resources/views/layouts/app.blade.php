<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stress Test System')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --black:   #0d0d0d;
            --gray-1:  #1a1a1a;
            --gray-2:  #333;
            --gray-3:  #555;
            --gray-4:  #888;
            --gray-5:  #bbb;
            --gray-6:  #e2e2e2;
            --gray-7:  #f5f5f5;
            --white:   #ffffff;
            --blue:    #1a73e8;
            --blue-lt: #e8f0fe;
            --blue-md: #4285f4;
            --sans:    'Inter', system-ui, sans-serif;
            --sidebar-w: 256px;
            --sidebar-collapsed-w: 80px;
        }

        html, body { height: 100%; }

        body {
            font-family: var(--sans);
            background: var(--gray-7);
            color: var(--black);
            display: flex;
            overflow: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            height: 100vh;
            background: var(--white);
            border-right: 1px solid var(--gray-6);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            transition: width 0.3s ease, transform 0.3s ease;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.55rem;
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid var(--gray-6);
            text-decoration: none;
            min-height: 60px;
        }
        .brand-text {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--black);
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; }

        .menu-label {
            font-size: 0.67rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gray-4);
            padding: 0.9rem 0.75rem 0.4rem;
            white-space: nowrap;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.85rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-3);
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
            white-space: nowrap;
        }
        .nav-item i {
            width: 18px;
            font-size: 1rem;
            text-align: center;
            color: var(--gray-5);
            transition: color 0.15s;
            flex-shrink: 0;
        }
        .nav-item:hover {
            background: var(--gray-7);
            color: var(--black);
        }
        .nav-item:hover i { color: var(--gray-3); }
        .nav-item.active {
            background: var(--blue-lt);
            color: var(--blue);
            font-weight: 600;
        }
        .nav-item.active i { color: var(--blue); }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--gray-6);
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.85rem;
            width: 100%;
            border: none;
            background: none;
            font-family: var(--sans);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-3);
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
            text-align: left;
            white-space: nowrap;
        }
        .logout-btn i { width: 18px; font-size: 0.95rem; text-align: center; color: var(--gray-5); flex-shrink: 0;}
        .logout-btn:hover { background: #fff0f0; color: #dc2626; }
        .logout-btn:hover i { color: #dc2626; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: 60px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--gray-6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 100;
            transition: left 0.3s ease;
        }
        .topbar-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--black);
            letter-spacing: -0.01em;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.75rem;
            background: var(--gray-7);
            border: 1px solid var(--gray-6);
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray-2);
        }
        .user-avatar {
            width: 24px; height: 24px;
            background: var(--black);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: white;
        }
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--gray-4);
            cursor: pointer;
            padding: 0.25rem;
            display: block;
            transition: color 0.2s;
        }
        .sidebar-toggle:hover { color: var(--black); }

        /* ── MAIN CONTENT ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: margin-left 0.3s ease;
        }
        .main-content {
            margin-top: 60px;
            flex: 1;
            padding: 2.5rem;
            overflow-y: auto;
            height: calc(100vh - 60px);
        }

        /* ── DESKTOP COLLAPSED STATE ── */
        @media (min-width: 769px) {
            body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed-w); min-width: var(--sidebar-collapsed-w); }
            body.sidebar-collapsed .main-wrapper { margin-left: var(--sidebar-collapsed-w); }
            body.sidebar-collapsed .topbar { left: var(--sidebar-collapsed-w); }
            
            body.sidebar-collapsed .brand-text,
            body.sidebar-collapsed .menu-label,
            body.sidebar-collapsed .nav-text,
            body.sidebar-collapsed .logout-text { display: none; }
            
            body.sidebar-collapsed .sidebar-brand { justify-content: center; padding: 1.5rem 0; }
            body.sidebar-collapsed .sidebar-brand::before {
                content: "S";
                font-size: 1.4rem;
                font-weight: 800;
                color: var(--blue);
            }

            body.sidebar-collapsed .nav-item { justify-content: center; padding: 0.8rem 0; }
            body.sidebar-collapsed .nav-item i { margin: 0; font-size: 1.2rem; }
            
            body.sidebar-collapsed .logout-btn { justify-content: center; padding: 0.8rem 0; }
            body.sidebar-collapsed .logout-btn i { margin: 0; font-size: 1.2rem; }
            
            body.sidebar-collapsed .menu-label { padding: 1rem 0 0.5rem; text-align: center; }
            body.sidebar-collapsed .menu-label::after { content: "•••"; letter-spacing: 2px; }
        }

        /* ── RESPONSIVE MOBILE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.1); }
            .main-wrapper { margin-left: 0; }
            .topbar { left: 0; }
            .main-content { padding: 1.5rem; }
        }
    </style>
    @yield('styles')
</head>
<body class="sidebar-collapsed">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <a href="/dashboard" class="sidebar-brand">
            <span class="brand-text">Stress System</span>
        </a>

        <nav class="sidebar-nav">
            <div class="menu-label"><span class="nav-text">Main</span></div>
            <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="fas fa-chart-pie"></i> <span class="nav-text">Dashboard</span>
            </a>

            <div class="menu-label"><span class="nav-text">Research</span></div>
            <a href="/test-sessions" class="nav-item {{ Request::is('test-sessions*') ? 'active' : '' }}" title="Test Sessions">
                <i class="fas fa-vial"></i> <span class="nav-text">Test Sessions</span>
            </a>
            <a href="/schedules" class="nav-item {{ Request::is('schedules*') ? 'active' : '' }}" title="Schedules">
                <i class="fas fa-calendar-alt"></i> <span class="nav-text">Schedules</span>
            </a>

            <div class="menu-label"><span class="nav-text">Management</span></div>
            <a href="/participants" class="nav-item {{ Request::is('participants*') ? 'active' : '' }}" title="Participants">
                <i class="fas fa-users"></i> <span class="nav-text">Participants</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="logout-btn" title="Log Out">
                    <i class="fas fa-sign-out-alt"></i> <span class="logout-text">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        <!-- TOPBAR -->
        <header class="topbar">
            <div style="display:flex;align-items:center;gap:1rem;">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <div class="user-chip">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}
                    </div>
                    {{ auth()->user()->name ?? 'Researcher' }}
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                // Mobile: toggle transform
                document.getElementById('sidebar').classList.toggle('open');
            } else {
                // Desktop: toggle width collapse
                document.body.classList.toggle('sidebar-collapsed');
            }
        }
    </script>

</body>
</html>