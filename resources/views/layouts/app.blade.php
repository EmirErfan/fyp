<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stress Test System')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }

        /* Mobile Header (Hidden on Desktop) */
        .mobile-header { display: none; background: white; padding: 15px 20px; border-bottom: 1px solid #eaeaea; width: 100%; justify-content: space-between; align-items: center; position: fixed; z-index: 1000; top: 0;}
        .mobile-toggle { font-size: 20px; cursor: pointer; color: #333; }

        /* Sidebar Styles */
        .sidebar { width: 260px; background-color: #ffffff; border-right: 1px solid #eaeaea; display: flex; flex-direction: column; padding: 20px 0; transition: transform 0.3s ease; z-index: 999;}
        .sidebar-brand { font-size: 20px; font-weight: bold; color: #333; padding: 0 20px 20px 20px; border-bottom: 1px solid #eaeaea; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .sidebar-brand i { color: #4b6bfb; }
        
        .menu-label { padding: 10px 20px; font-size: 11px; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: 1px; margin-top: 10px;}
        
        /* Navigation Items & Hover/Active States */
        .nav-item { padding: 12px 20px; text-decoration: none; color: #555; font-weight: 500; display: flex; align-items: center; gap: 15px; transition: all 0.2s; border-left: 3px solid transparent;}
        .nav-item i { font-size: 18px; width: 20px; text-align: center; color: #888; transition: 0.2s; }
        
        .nav-item:hover { background-color: #f8f9fa; color: #4b6bfb; }
        .nav-item:hover i { color: #4b6bfb; }
        
        /* The Magic Active Class! */
        .nav-item.active { background-color: #eef2ff; color: #4b6bfb; border-left: 3px solid #4b6bfb; font-weight: 600;}
        .nav-item.active i { color: #4b6bfb; }

        /* Logout Button */
        .logout-btn { margin-top: auto; padding: 0 20px; }
        .logout-btn button { background: none; border: none; color: #dc3545; font-weight: 600; cursor: pointer; text-align: left; padding: 12px 0; width: 100%; font-size: 15px; display: flex; align-items: center; gap: 15px;}
        .logout-btn button:hover { color: #b02a37; }

        /* Main Content */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }

        /* Responsive Media Query for Mobile */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .mobile-header { display: flex; }
            .sidebar { position: absolute; height: 100%; top: 0; left: 0; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 15px rgba(0,0,0,0.1); }
            .main-content { margin-top: 60px; padding: 20px; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="mobile-header">
        <div style="font-weight: bold; color: #4b6bfb;"><i class="fas fa-brain"></i> Stress System</div>
        <i class="fas fa-bars mobile-toggle" onclick="toggleSidebar()"></i>
    </div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand"><i class="fas fa-brain"></i> Stress System</div>
        
        <div class="menu-label">Main</div>
        <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        <div class="menu-label">Research Operations</div>
        <a href="/test-sessions" class="nav-item {{ Request::is('test-sessions*') ? 'active' : '' }}">
            <i class="fas fa-vial"></i> Test Sessions
        </a>
        <a href="/schedules" class="nav-item {{ Request::is('schedules*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Schedules
        </a>

        <div class="menu-label">Management</div>
        <a href="/participants" class="nav-item {{ Request::is('participants*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Participants
        </a>

        <div class="logout-btn">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit"><i class="fas fa-sign-out-alt"></i> Log Out</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>

</body>
</html>