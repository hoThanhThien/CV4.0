<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — CV Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #f8fafc;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --accent: #6366f1;
            --accent-light: #8b5cf6;
            --gradient: linear-gradient(135deg, #6366f1, #06b6d4);
            --sidebar-width: 260px;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width); background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-logo {
            padding: 1.5rem 1.5rem 1rem;
            font-size: 1.3rem; font-weight: 800;
            background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo small {
            display: block; font-size: 0.7rem; font-weight: 400;
            -webkit-text-fill-color: var(--text-muted); background: none; margin-top: 0.1rem;
        }

        .sidebar-nav { flex: 1; padding: 1.25rem 0.75rem; overflow-y: auto; }

        .nav-section-title {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: var(--text-muted);
            padding: 0.5rem 0.75rem; margin-bottom: 0.25rem;
        }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 0.75rem; border-radius: 10px;
            color: var(--text-muted); text-decoration: none;
            font-size: 0.9rem; font-weight: 500;
            transition: all 0.2s ease; margin-bottom: 0.15rem;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(124,58,237,0.15); color: var(--text);
        }
        .sidebar-nav a.active { border-left: 3px solid var(--accent); }
        .sidebar-nav a i { width: 18px; text-align: center; font-size: 0.95rem; }

        .sidebar-footer {
            padding: 1rem 0.75rem; border-top: 1px solid var(--border);
        }
        .sidebar-footer form button {
            width: 100%; display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 0.75rem; border-radius: 10px;
            background: none; border: none; color: var(--text-muted);
            font-size: 0.9rem; font-weight: 500; cursor: pointer;
            transition: all 0.2s;
        }
        .sidebar-footer form button:hover { background: rgba(239,68,68,0.1); color: var(--danger); }

        /* Main content */
        .main { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* Top bar */
        .topbar {
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title { font-size: 1.1rem; font-weight: 700; }
        .topbar-title span { color: var(--text-muted); font-weight: 400; font-size: 0.9rem; }

        .topbar-right { display: flex; align-items: center; gap: 1rem; }

        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--gradient);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem;
        }

        /* Content area */
        .content { padding: 2rem 1.5rem; flex: 1; }

        /* Page header */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
        }
        .page-header h1 { font-size: 1.6rem; font-weight: 700; }

        /* Cards/Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 12px; padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 500; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; }
        .stat-card .stat-icon {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.75rem; font-size: 1rem;
        }
        .stat-purple .stat-icon { background: rgba(124,58,237,0.2); color: var(--accent-light); }
        .stat-cyan .stat-icon { background: rgba(6,182,212,0.2); color: #06b6d4; }
        .stat-green .stat-icon { background: rgba(16,185,129,0.2); color: var(--success); }
        .stat-pink .stat-icon { background: rgba(236,72,153,0.2); color: #ec4899; }

        /* Table */
        .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 1rem 1.25rem; text-align: left;
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--text-muted);
            background: rgba(0,0,0,0.02); border-bottom: 1px solid var(--border);
        }
        tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid rgba(0,0,0,0.04); font-size: 0.9rem; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(0,0,0,0.02); }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.65rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        .badge-success { background: rgba(16,185,129,0.15); color: var(--success); }
        .badge-danger { background: rgba(239,68,68,0.15); color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,0.15); color: var(--warning); }
        .badge-purple { background: rgba(124,58,237,0.15); color: var(--accent-light); }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: var(--gradient); color: white; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-secondary { background: var(--bg-card); border: 1px solid var(--border); color: var(--text); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent-light); }
        .btn-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: var(--danger); }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }

        /* Forms */
        .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-muted); }
        .form-control {
            width: 100%; padding: 0.7rem 1rem; border-radius: 10px;
            background: #ffffff; border: 1px solid var(--border);
            color: var(--text); font-family: inherit; font-size: 0.9rem;
            transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--accent); background: rgba(124,58,237,0.05); }
        textarea.form-control { min-height: 160px; resize: vertical; }
        .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--accent); cursor: pointer; }
        .form-check label { font-size: 0.9rem; cursor: pointer; }
        select.form-control option { background: var(--bg-sidebar); }

        /* Alerts */
        .alert { padding: 0.9rem 1.1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: var(--success); }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: var(--danger); }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: none; }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            CV Admin
            <small>Portfolio Manager</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section-title" style="margin-top:1rem">Content</div>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i class="fas fa-code"></i> Projects
            </a>
            <a href="{{ route('admin.blog.index') }}" class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> Blog Posts
            </a>
            <a href="{{ route('admin.skills.index') }}" class="{{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Skills
            </a>
            <a href="{{ route('admin.experiences.index') }}" class="{{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Experiences
            </a>

            <div class="nav-section-title" style="margin-top:1rem">View Site</div>
            <a href="{{ route('home') }}" target="_blank">
                <i class="fas fa-external-link-alt"></i> Visit Portfolio
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar-title">
                    @yield('title', 'Dashboard')
                    <span>/ Admin</span>
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
                <div class="topbar-avatar">A</div>
            </div>
        </header>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
