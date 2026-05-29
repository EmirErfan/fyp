<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stress Test System')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --pink:        #c8184a;
            --pink-deep:   #a01039;
            --pink-soft:   #f0d0da;
            --pink-lt:     #fdf0f3;
            --pink-mid:    rgba(200, 24, 74, 0.12);

            --white:       #ffffff;
            --off-white:   #f8f8fa;
            --gray-1:      #f3f3f6;
            --gray-2:      #e6e6ec;
            --gray-3:      #d0d0d8;
            --gray-4:      #9898a8;
            --gray-5:      #606070;

            --text-primary:   #1a1a28;
            --text-secondary: #5a5a70;
            --text-muted:     #9898a8;

            --sans: 'Sora', system-ui, sans-serif;
            --mono: 'DM Mono', monospace;

            --navbar-h: 62px;
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.07);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.09);
            --shadow-pink: 0 4px 16px rgba(200,24,74,0.25);
        }

        html, body { height: 100%; }

        body {
            font-family: var(--sans);
            background: var(--off-white);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--navbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--gray-2);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            z-index: 300;
            box-shadow: var(--shadow-sm);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            margin-right: 2.5rem;
            flex-shrink: 0;
        }
        .brand-icon {
            width: 32px; height: 32px;
            background: var(--pink);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            color: #fff;
            box-shadow: var(--shadow-pink);
            flex-shrink: 0;
        }
        .brand-text {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            white-space: nowrap;
        }
        .brand-text em { font-style: normal; color: var(--pink); }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.1rem;
            flex: 1;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
        }
        .nav-link i {
            font-size: 0.78rem;
            color: var(--text-muted);
            transition: color 0.15s;
            width: 14px; text-align: center;
        }
        .nav-link:hover { background: var(--gray-1); color: var(--text-primary); }
        .nav-link:hover i { color: var(--gray-5); }
        .nav-link.active {
            background: var(--pink-lt);
            color: var(--pink);
            font-weight: 600;
        }
        .nav-link.active i { color: var(--pink); }

        .nav-sep {
            width: 1px; height: 18px;
            background: var(--gray-2);
            margin: 0 0.5rem;
            flex-shrink: 0;
        }

        /* Right side */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: auto;
            flex-shrink: 0;
        }

        .nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.3rem;
            margin-right: 0.25rem;
        }

        /* Profile */
        .profile-menu {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-1);
            border: 1px solid var(--gray-2);
            padding: 5px 12px 5px 5px;
            border-radius: 100px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            user-select: none;
        }
        .profile-dropdown.show { 
            display: block; 
        }
        .profile-avatar {
            background: var(--pink);
            color: white;
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 11px; flex-shrink: 0;
        }
        .profile-name { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
        .profile-chevron {
            font-size: 9px; color: var(--text-muted); margin-left: 2px;
            transition: transform 0.2s;
        }
        .profile-menu:hover .profile-chevron { transform: rotate(180deg); }

        .profile-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px); right: 0;
            background: var(--white);
            border: 1px solid var(--gray-2);
            box-shadow: var(--shadow-md);
            border-radius: 12px;
            min-width: 175px;
            overflow: hidden;
            z-index: 1000;
        }
        .profile-menu:hover .profile-dropdown { display: block; }
        .dropdown-item {
            padding: 10px 14px;
            display: flex; align-items: center; gap: 9px;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.82rem; font-weight: 500;
            transition: background 0.12s, color 0.12s;
            border-bottom: 1px solid var(--gray-1);
            cursor: pointer;
            background: none;
            border-left: none; border-right: none; border-top: none;
            width: 100%; text-align: left;
            font-family: var(--sans);
        }
        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item i { width: 14px; color: var(--text-muted); font-size: 0.8rem; }
        .dropdown-item:hover { background: var(--gray-1); color: var(--text-primary); }
        .dropdown-logout { color: var(--pink); }
        .dropdown-logout i { color: var(--pink) !important; }
        .dropdown-logout:hover { background: var(--pink-lt) !important; color: var(--pink-deep) !important; }

        /* Mobile drawer */
        .mobile-drawer {
            display: none;
            position: fixed;
            top: var(--navbar-h); left: 0; right: 0;
            background: var(--white);
            border-bottom: 1px solid var(--gray-2);
            padding: 1rem 1.5rem 1.25rem;
            flex-direction: column;
            gap: 0.2rem;
            z-index: 200;
            box-shadow: var(--shadow-md);
            animation: slideDown 0.2s ease;
        }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .mobile-drawer.open { display: flex; }
        .mobile-drawer .nav-link { padding: 0.65rem 1rem; font-size: 0.88rem; }
        .mobile-sep { height: 1px; background: var(--gray-2); margin: 0.4rem 0; }

        /* ── TOAST NOTIFICATIONS ── */
        .toast-container {
            position: fixed;
            top: calc(var(--navbar-h) + 1.5rem);
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none; /* Lets clicks pass through the container */
        }
        .toast {
            min-width: 300px;
            background: var(--white);
            border-left: 4px solid var(--pink);
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInRight 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 500;
            pointer-events: auto; /* Re-enables clicks on the toast itself */
        }
        .toast.success { border-left-color: #10b981; }
        .toast.success .toast-icon { color: #10b981; font-size: 1.1rem; }
        
        .toast.error { border-left-color: #ef4444; }
        .toast.error .toast-icon { color: #ef4444; font-size: 1.1rem; }
        
        .toast.hiding { animation: fadeOutRight 0.3s ease forwards; }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }

        /* ── MAIN ── */
        .main-content {
            margin-top: var(--navbar-h);
            flex: 1;
            padding: 2.25rem 2.5rem;
            min-height: calc(100vh - var(--navbar-h));
        }

        @media (max-width: 900px) {
            .nav-links { display: none; }
            .nav-toggle { display: block; }
            .main-content { padding: 1.5rem; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <nav class="navbar">
        <button class="nav-toggle" onclick="toggleMobileNav()"><i class="fas fa-bars"></i></button>

        <a href="/dashboard" class="nav-brand">
            <div class="brand-icon"><i class="fas fa-brain"></i></div>
            <span class="brand-text">Stress<em>Lab</em></span>
        </a>

        <div class="nav-links">
            <a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <div class="nav-sep"></div>
            <a href="/test-sessions" class="nav-link {{ Request::is('test-sessions*') ? 'active' : '' }}">
                <i class="fas fa-vial"></i> Test Sessions
            </a>
            <a href="/schedules" class="nav-link {{ Request::is('schedules*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Schedules
            </a>
            <div class="nav-sep"></div>
            <a href="/participants" class="nav-link {{ Request::is('participants*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Participants
            </a>
            <a href="/export" class="nav-link {{ Request::is('export*') ? 'active' : '' }}">
                <i class="fas fa-file-export"></i> Export
            </a>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="nav-sep"></div>
            <a href="/users" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> Users
            </a>
            @endif
        </div>

        <div class="nav-right">
            <div class="profile-menu" onclick="toggleProfileMenu(event)">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                
                <div class="profile-name">
                    {{ auth()->user()->name ?? 'User' }} 
                    <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px; color: #888;"></i>
                </div>
                
                <div class="profile-dropdown" id="profileDropdown" onclick="event.stopPropagation()">
                    <a href="/profile" class="dropdown-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
                    <a href="/password/change" class="dropdown-item"><i class="fas fa-key"></i> Change Password</a>
                    
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; font-family: inherit;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- MOBILE DRAWER -->
    <div class="mobile-drawer" id="mobileDrawer">
        <a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" onclick="closeMobileNav()"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <div class="mobile-sep"></div>
        <a href="/test-sessions" class="nav-link {{ Request::is('test-sessions*') ? 'active' : '' }}" onclick="closeMobileNav()"><i class="fas fa-vial"></i> Test Sessions</a>
        <a href="/schedules" class="nav-link {{ Request::is('schedules*') ? 'active' : '' }}" onclick="closeMobileNav()"><i class="fas fa-calendar-alt"></i> Schedules</a>
        <div class="mobile-sep"></div>
        <a href="/participants" class="nav-link {{ Request::is('participants*') ? 'active' : '' }}" onclick="closeMobileNav()"><i class="fas fa-users"></i> Participants</a>
        <a href="/export" class="nav-link {{ Request::is('export*') ? 'active' : '' }}" onclick="closeMobileNav()"><i class="fas fa-file-export"></i> Export</a>
        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="mobile-sep"></div>
        <a href="/users" class="nav-link {{ Request::is('users*') ? 'active' : '' }}" onclick="closeMobileNav()"><i class="fas fa-user-shield"></i> User Management</a>
        @endif
        <div class="mobile-sep"></div>
        <a href="/profile" class="nav-link" onclick="closeMobileNav()"><i class="fas fa-user-edit"></i> Edit Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="nav-link dropdown-logout" style="width:100%;background:none;border:none;cursor:pointer;font-family:var(--sans);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <div class="toast-container" id="global-toasts">
        @if(session('success'))
            <div class="toast success">
                <i class="fas fa-check-circle toast-icon"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error') || $errors->any())
            <div class="toast error">
                <i class="fas fa-exclamation-circle toast-icon"></i>
                <span>{{ session('error') ?? $errors->first() }}</span>
            </div>
        @endif
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
        function toggleProfileMenu(event) {
            // Prevent the click from triggering the window click listener below
            event.stopPropagation(); 
            // Toggle the 'show' class to hide/reveal the menu
            document.getElementById('profileDropdown').classList.toggle('show');
        }

        // Close the dropdown if the user clicks anywhere else on the screen
        window.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });

        // Auto-dismiss Toasts after 4 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('hiding');
                    // Remove from DOM entirely after the exit animation finishes
                    setTimeout(() => toast.remove(), 300); 
                }, 4000);
            });
        });
    </script>
</body>
</html>