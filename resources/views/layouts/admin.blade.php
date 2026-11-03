<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — CV Admin Panel</title>

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

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
        .badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.65rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
        .badge-success { background: rgba(16,185,129,0.15); color: var(--success); }
        .badge-danger { background: rgba(239,68,68,0.15); color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,0.15); color: var(--warning); }
        .badge-purple { background: rgba(124,58,237,0.15); color: var(--accent-light); }
        .badge-secondary { background: rgba(100,116,139,0.14); color: var(--text-muted); }
        .badge-info { background: rgba(6,182,212,0.15); color: #0891b2; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
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

        .admin-hamburger {
            display: none; background: var(--bg-card); border: 1px solid var(--border);
            color: var(--text); width: 38px; height: 38px; border-radius: 8px;
            align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer;
            margin-right: 0.75rem;
        }

        .admin-backdrop {
            display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            z-index: 99; opacity: 0; transition: opacity 0.3s ease;
        }
        .admin-backdrop.open { display: block; opacity: 1; }

        /* Responsive */
        @media (max-width: 900px) {
            .admin-hamburger { display: flex; }
            .sidebar { transform: translateX(-100%); box-shadow: 0 0 30px rgba(0,0,0,0.15); }
            .sidebar.open { transform: none; }
            .main { margin-left: 0; }
            .topbar { padding: 0.85rem 1rem; }
            .content { padding: 1.25rem 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            table { min-width: 600px; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .form-card { padding: 1.25rem; }
        }

        /* ===== CUSTOM POP-UP MODAL & TOAST SYSTEM ===== */
        .admin-popup-overlay {
            position: fixed;
            inset: 0;
            z-index: 9998;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .admin-popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .admin-popup-dialog {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 22px;
            width: 100%;
            max-width: 430px;
            padding: 2rem 1.75rem 1.65rem;
            text-align: center;
            box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.28), 0 0 0 1px rgba(99, 102, 241, 0.08);
            transform: scale(0.92) translateY(14px);
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .admin-popup-overlay.active .admin-popup-dialog {
            transform: scale(1) translateY(0);
        }
        .admin-popup-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            margin: 0 auto 1.15rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
        }
        .admin-popup-icon.type-ai {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.14), rgba(6, 182, 212, 0.14));
            color: #4f46e5;
            box-shadow: 0 8px 24px -6px rgba(99, 102, 241, 0.35);
        }
        .admin-popup-icon.type-danger {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            box-shadow: 0 8px 24px -6px rgba(239, 68, 68, 0.3);
        }
        .admin-popup-icon.type-publish {
            background: rgba(16, 185, 129, 0.14);
            color: #10b981;
            box-shadow: 0 8px 24px -6px rgba(16, 185, 129, 0.3);
        }
        .admin-popup-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.55rem;
            letter-spacing: -0.01em;
        }
        .admin-popup-desc {
            font-size: 0.92rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .admin-popup-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .admin-popup-actions .btn {
            justify-content: center;
            padding: 0.7rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
        }
        .admin-popup-btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            box-shadow: 0 6px 18px -4px rgba(239, 68, 68, 0.45);
        }
        .admin-popup-btn-danger:hover { opacity: 0.92; transform: translateY(-1px); }
        .admin-popup-btn-publish {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            box-shadow: 0 6px 18px -4px rgba(16, 185, 129, 0.45);
        }
        .admin-popup-btn-publish:hover { opacity: 0.92; transform: translateY(-1px); }
        .admin-popup-btn-ai {
            background: var(--gradient);
            color: #ffffff;
            box-shadow: 0 6px 18px -4px rgba(99, 102, 241, 0.45);
        }
        .admin-popup-btn-ai:hover { opacity: 0.92; transform: translateY(-1px); }

        /* AI Spinner inside Loading State */
        .admin-ai-loader {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 4px solid rgba(99, 102, 241, 0.15);
            border-top-color: #6366f1;
            border-right-color: #06b6d4;
            animation: adminSpin 0.85s linear infinite;
            margin: 0 auto 1.15rem;
        }
        @keyframes adminSpin {
            to { transform: rotate(360deg); }
        }
        .admin-popup-timer {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-family: monospace;
            font-size: 0.82rem;
            font-weight: 700;
            color: #4f46e5;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.2);
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
        }

        /* Floating Toast Stack */
        .admin-toast-stack {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: min(420px, calc(100vw - 2rem));
            pointer-events: none;
        }
        .admin-toast {
            pointer-events: auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 1.15rem;
            box-shadow: 0 18px 40px -10px rgba(15, 23, 42, 0.22), 0 0 0 1px rgba(226, 232, 240, 0.9);
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            position: relative;
            overflow: hidden;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
        }
        .admin-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .admin-toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.05rem;
        }
        .admin-toast.toast-success .admin-toast-icon { background: rgba(16, 185, 129, 0.14); color: #10b981; }
        .admin-toast.toast-error .admin-toast-icon { background: rgba(239, 68, 68, 0.14); color: #ef4444; }
        .admin-toast-body { flex: 1; min-width: 0; }
        .admin-toast-title { font-size: 0.88rem; font-weight: 800; color: #0f172a; margin-bottom: 0.15rem; }
        .admin-toast-msg { font-size: 0.84rem; color: #475569; line-height: 1.45; word-break: break-word; }
        .admin-toast-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.15rem;
            line-height: 1;
        }
        .admin-toast-close:hover { color: #0f172a; }
        .admin-toast-bar {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
            animation: toastProgress 5.5s linear forwards;
        }
        .admin-toast.toast-success .admin-toast-bar { background: linear-gradient(90deg, #10b981, #06b6d4); }
        .admin-toast.toast-error .admin-toast-bar { background: linear-gradient(90deg, #ef4444, #f97316); }
        @keyframes toastProgress {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="admin-backdrop" id="admin-backdrop"></div>

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
            <a href="{{ route('admin.certificates.index') }}" class="{{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Brochure & Giấy khen
            </a>

            <div class="nav-section-title" style="margin-top:1rem; display:flex; align-items:center; justify-content:space-between">
                <span>AI Blog Studio</span>
                <span class="badge" style="background:rgba(99,102,241,0.2); color:var(--accent); font-size:0.65rem; padding:0.1rem 0.4rem">AI 4.0</span>
            </div>
            <a href="{{ route('admin.ai.dashboard') }}" class="{{ request()->routeIs('admin.ai.dashboard') ? 'active' : '' }}">
                <i class="fas fa-magic"></i> AI Dashboard
            </a>
            <a href="{{ route('admin.ai.posts.index') }}" class="{{ request()->routeIs('admin.ai.posts.*') ? 'active' : '' }}">
                <i class="fas fa-robot"></i> AI Blog Posts
                @php
                    try {
                        $pendingCount = \App\Services\AI\Support\AiSchemaManager::hasBlogStatusColumn()
                            ? \App\Models\BlogPost::where('ai_generated', true)->where('status', 'pending_review')->count()
                            : 0;
                    } catch (\Throwable $e) {
                        $pendingCount = 0;
                    }
                @endphp
                @if($pendingCount > 0)
                    <span class="badge badge-warning" style="margin-left:auto; font-size:0.7rem; padding:0.15rem 0.45rem">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.ai.automations.index') }}" class="{{ request()->routeIs('admin.ai.automations.*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Automations
            </a>
            <a href="{{ route('admin.ai.configs.index') }}" class="{{ request()->routeIs('admin.ai.configs.*') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> Content Configs
            </a>
            <a href="{{ route('admin.ai.providers.index') }}" class="{{ request()->routeIs('admin.ai.providers.*') ? 'active' : '' }}">
                <i class="fas fa-brain"></i> AI Providers
            </a>
            <a href="{{ route('admin.ai.logs.index') }}" class="{{ request()->routeIs('admin.ai.logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Automation Logs
            </a>

            <div class="nav-section-title" style="margin-top:1rem">Inbox</div>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Messages
                @php $sidebarUnread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                @if($sidebarUnread > 0)
                    <span class="badge badge-danger" style="margin-left:auto; font-size:0.7rem; padding:0.15rem 0.45rem">{{ $sidebarUnread }}</span>
                @endif
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
            <div style="display: flex; align-items: center;">
                <button class="admin-hamburger" id="admin-hamburger" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
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
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Please correct the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 1.25rem; font-size: 0.85rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Global Custom Pop-up Confirmation & AI Progress Modal -->
    <div class="admin-popup-overlay" id="adminPopupOverlay" role="dialog" aria-modal="true">
        <div class="admin-popup-dialog">
            <div id="adminPopupConfirmView">
                <div class="admin-popup-icon type-ai" id="adminPopupIcon">
                    <i class="fas fa-bolt" id="adminPopupIconGlyph"></i>
                </div>
                <div class="admin-popup-title" id="adminPopupTitle">Xác nhận thao tác</div>
                <div class="admin-popup-desc" id="adminPopupDesc">Bạn có chắc chắn muốn thực hiện thao tác này?</div>
                <div class="admin-popup-actions">
                    <button type="button" class="btn btn-secondary" id="adminPopupCancelBtn">
                        Hủy bỏ
                    </button>
                    <button type="button" class="btn admin-popup-btn-ai" id="adminPopupConfirmBtn">
                        Xác nhận
                    </button>
                </div>
            </div>

            <div id="adminPopupLoadingView" style="display:none">
                <div class="admin-ai-loader"></div>
                <div class="admin-popup-title" id="adminPopupLoadingTitle">AI đang xử lý...</div>
                <div class="admin-popup-desc" id="adminPopupLoadingDesc" style="margin-bottom:1.1rem">
                    Hệ thống đang kết nối tới Google Gemini AI để biên soạn bài viết chuẩn SEO. Vui lòng giữ nguyên trang trong giây lát...
                </div>
                <div class="admin-popup-timer">
                    <i class="fas fa-clock"></i> <span id="adminPopupElapsed">00:00s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Pop-up Toast Container -->
    <div class="admin-toast-stack" id="adminToastStack"></div>

    <script>
        const adminHamburger = document.getElementById('admin-hamburger');
        const adminSidebar = document.getElementById('sidebar');
        const adminBackdrop = document.getElementById('admin-backdrop');

        if (adminHamburger && adminSidebar) {
            function toggleAdminSidebar(state) {
                const isOpen = typeof state === 'boolean' ? state : !adminSidebar.classList.contains('open');
                adminSidebar.classList.toggle('open', isOpen);
                if (adminBackdrop) adminBackdrop.classList.toggle('open', isOpen);
            }

            adminHamburger.addEventListener('click', () => toggleAdminSidebar());
            if (adminBackdrop) adminBackdrop.addEventListener('click', () => toggleAdminSidebar(false));

            adminSidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) toggleAdminSidebar(false);
                });
            });
        }

        // Floating Toast Pop-up Helper
        window.showAdminToast = function(message, type = 'success', title = null) {
            const stack = document.getElementById('adminToastStack');
            if (!stack || !message) return;

            const isError = (type === 'error' || type === 'danger');
            const toast = document.createElement('div');
            toast.className = 'admin-toast ' + (isError ? 'toast-error' : 'toast-success');
            const heading = title || (isError ? 'Thông báo lỗi' : 'Thao tác thành công');
            const icon = isError ? 'fa-exclamation-circle' : 'fa-check-circle';

            toast.innerHTML = `
                <div class="admin-toast-icon"><i class="fas ${icon}"></i></div>
                <div class="admin-toast-body">
                    <div class="admin-toast-title"></div>
                    <div class="admin-toast-msg"></div>
                </div>
                <button type="button" class="admin-toast-close" aria-label="Đóng">&times;</button>
                <div class="admin-toast-bar"></div>
            `;
            toast.querySelector('.admin-toast-title').textContent = heading;
            toast.querySelector('.admin-toast-msg').textContent = message;

            const dismiss = () => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            };
            toast.querySelector('.admin-toast-close').addEventListener('click', dismiss);

            stack.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(dismiss, 5500);
        };

        // Custom Pop-up Confirmation & AI Loading Modal
        (function() {
            const overlay = document.getElementById('adminPopupOverlay');
            const confirmView = document.getElementById('adminPopupConfirmView');
            const loadingView = document.getElementById('adminPopupLoadingView');
            const iconBox = document.getElementById('adminPopupIcon');
            const iconGlyph = document.getElementById('adminPopupIconGlyph');
            const titleEl = document.getElementById('adminPopupTitle');
            const descEl = document.getElementById('adminPopupDesc');
            const cancelBtn = document.getElementById('adminPopupCancelBtn');
            const confirmBtn = document.getElementById('adminPopupConfirmBtn');
            const loadingTitleEl = document.getElementById('adminPopupLoadingTitle');
            const loadingDescEl = document.getElementById('adminPopupLoadingDesc');
            const elapsedEl = document.getElementById('adminPopupElapsed');

            let activeForm = null;
            let timerInterval = null;
            let isSubmitting = false;

            function closePopup() {
                if (isSubmitting) return;
                overlay.classList.remove('active');
                activeForm = null;
            }

            window.openAdminConfirmForm = function(form) {
                activeForm = form;
                isSubmitting = false;
                if (timerInterval) clearInterval(timerInterval);

                const msg = form.getAttribute('data-confirm') || 'Bạn có chắc chắn muốn thực hiện thao tác này?';
                const type = form.getAttribute('data-confirm-type') || (form.querySelector('input[name="_method"][value="DELETE"]') ? 'danger' : 'ai');

                const defaultTitles = {
                    danger: 'Xác nhận xóa dữ liệu',
                    publish: 'Xuất bản bài viết ngay?',
                    ai: 'Kích hoạt tác vụ AI?'
                };
                const defaultBtnLabels = {
                    danger: '<i class="fas fa-trash-alt"></i> Xóa ngay',
                    publish: '<i class="fas fa-globe"></i> Xuất bản ngay',
                    ai: '<i class="fas fa-bolt"></i> Chạy ngay'
                };
                const icons = {
                    danger: 'fa-trash-alt',
                    publish: 'fa-globe',
                    ai: 'fa-bolt'
                };

                confirmView.style.display = 'block';
                loadingView.style.display = 'none';

                iconBox.className = 'admin-popup-icon type-' + (icons[type] ? type : 'ai');
                iconGlyph.className = 'fas ' + (form.getAttribute('data-confirm-icon') || icons[type] || 'fa-bolt');
                titleEl.textContent = form.getAttribute('data-confirm-title') || defaultTitles[type] || 'Xác nhận thao tác';
                descEl.textContent = msg;

                confirmBtn.className = 'btn admin-popup-btn-' + (icons[type] ? type : 'ai');
                const customBtn = form.getAttribute('data-confirm-btn');
                if (customBtn) {
                    confirmBtn.innerHTML = customBtn;
                } else {
                    confirmBtn.innerHTML = defaultBtnLabels[type] || 'Xác nhận';
                }

                overlay.classList.add('active');
            };

            cancelBtn.addEventListener('click', closePopup);
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closePopup();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && overlay.classList.contains('active')) closePopup();
            });

            confirmBtn.addEventListener('click', () => {
                if (!activeForm || isSubmitting) return;
                isSubmitting = true;

                const formToSubmit = activeForm;
                const loadingTitle = formToSubmit.getAttribute('data-loading-title');
                const submitBtn = formToSubmit.querySelector('button[type="submit"]');

                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                if (loadingTitle) {
                    confirmView.style.display = 'none';
                    loadingView.style.display = 'block';
                    loadingTitleEl.textContent = loadingTitle;
                    loadingDescEl.textContent = formToSubmit.getAttribute('data-loading-desc') || 'Hệ thống đang kết nối tới AI và xử lý yêu cầu (mất khoảng 10–30 giây). Vui lòng giữ nguyên cửa sổ này...';
                    let sec = 0;
                    elapsedEl.textContent = '00:00s';
                    timerInterval = setInterval(() => {
                        sec++;
                        const mm = String(Math.floor(sec / 60)).padStart(2, '0');
                        const ss = String(sec % 60).padStart(2, '0');
                        elapsedEl.textContent = `${mm}:${ss}s`;
                    }, 1000);
                } else {
                    overlay.classList.remove('active');
                }

                formToSubmit.dataset.confirmed = '1';
                formToSubmit.submit();
            });

            document.addEventListener('DOMContentLoaded', () => {
                // Upgrade any legacy inline onsubmit="...confirm('...')..." forms automatically
                document.querySelectorAll('form[onsubmit]').forEach(form => {
                    const raw = form.getAttribute('onsubmit') || '';
                    const match = raw.match(/confirm\(\s*(['"])([\s\S]*?)\1\s*\)/);
                    if (match) {
                        form.removeAttribute('onsubmit');
                        if (!form.hasAttribute('data-confirm')) {
                            form.setAttribute('data-confirm', match[2]);
                        }
                        if (/10[–-]30\s*giây|AI/i.test(match[2]) && !form.hasAttribute('data-loading-title')) {
                            form.setAttribute('data-confirm-type', 'ai');
                            form.setAttribute('data-loading-title', 'AI đang biên soạn bài viết...');
                        }
                    }
                });

                document.addEventListener('submit', (e) => {
                    const form = e.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    if (form.hasAttribute('data-confirm') && form.dataset.confirmed !== '1') {
                        e.preventDefault();
                        window.openAdminConfirmForm(form);
                    }
                });

                @if(session('success'))
                    window.showAdminToast(@json(session('success')), 'success');
                @endif
                @if(session('error'))
                    window.showAdminToast(@json(session('error')), 'error');
                @endif
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>
