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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #f7f8fa;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --border: #dce1e9;
            --text: #12161f;
            --text-muted: #687385;
            --accent: #1e40af;
            --primary: #1e40af;
            --accent-light: #1d4ed8;
            --gradient: linear-gradient(135deg, #12161f, #1e40af);
            --sidebar-width: 256px;
            --danger: #dc2626;
            --success: #15803d;
            --warning: #b45309;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-sm: 0 2px 8px rgba(18, 22, 31, 0.05);
            --shadow-md: 0 10px 28px rgba(18, 22, 31, 0.09);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width); background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
            transition: transform 0.25s ease;
        }

        .sidebar-logo {
            padding: 1.35rem 1.35rem 1rem;
            font-size: 1.15rem; font-weight: 700;
            color: var(--text); letter-spacing: -0.02em;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo small {
            display: block; font-size: 0.74rem; font-weight: 500;
            color: var(--text-muted); margin-top: 0.15rem;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }

        .nav-section-title {
            font-size: 0.76rem; font-weight: 600;
            color: var(--text-muted);
            padding: 0.5rem 0.75rem 0.3rem; margin-bottom: 0.15rem;
        }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.58rem 0.75rem; border-radius: 7px;
            color: #434d5e; text-decoration: none;
            font-size: 0.88rem; font-weight: 500;
            transition: background 0.15s ease, color 0.15s ease; margin-bottom: 0.15rem;
        }

        .sidebar-nav a:hover {
            background: #f1f3f7; color: var(--text);
        }
        .sidebar-nav a.active {
            background: #eef1f5; color: var(--text); font-weight: 600;
            border-left: 3px solid var(--accent);
        }
        .sidebar-nav a.active i { color: var(--accent); }
        .sidebar-nav a i { width: 18px; text-align: center; font-size: 0.9rem; color: var(--text-muted); }

        .sidebar-footer {
            padding: 0.9rem 0.75rem; border-top: 1px solid var(--border);
        }
        .sidebar-footer form button {
            width: 100%; display: flex; align-items: center; gap: 0.7rem;
            padding: 0.6rem 0.75rem; border-radius: 7px;
            background: none; border: none; color: #434d5e;
            font-family: inherit; font-size: 0.88rem; font-weight: 500; cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-footer form button:hover { background: #fef2f2; color: var(--danger); }

        /* Main content */
        .main { margin-left: var(--sidebar-width); flex: 1; min-width: 0; max-width: 100%; overflow-x: hidden; display: flex; flex-direction: column; min-height: 100vh; }

        /* Top bar */
        .topbar {
            background: rgba(255, 255, 255, 0.94); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0.9rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title { font-size: 1.04rem; font-weight: 700; color: var(--text); }
        .topbar-title span { color: var(--text-muted); font-weight: 400; font-size: 0.88rem; }

        .topbar-right { display: flex; align-items: center; gap: 0.85rem; }

        .topbar-avatar {
            width: 34px; height: 34px; border-radius: 7px;
            background: var(--text); color: #ffffff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.86rem; text-decoration: none;
            border: 1px solid var(--text);
            transition: background-color 0.16s ease, border-color 0.16s ease, transform 0.15s ease, box-shadow 0.16s ease;
        }
        .topbar-avatar:hover {
            background: var(--accent);
            border-color: var(--accent);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Content area */
        .content { padding: 1.75rem 1.5rem; flex: 1; }

        :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

        /* Page header */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.65rem; flex-wrap: wrap; gap: 1rem;
        }
        .page-header > div { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
        .page-header h1 { font-size: 1.45rem; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }

        /* Cards/Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.75rem; }
        .stat-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 9px; padding: 1.2rem;
            transition: transform 0.2s var(--ease-out), border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-1px); border-color: #cbd5e1; box-shadow: var(--shadow-sm); }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.45rem; font-weight: 500; }
        .stat-card .stat-value { font-size: 1.85rem; font-weight: 700; color: var(--text); }
        .stat-card .stat-icon {
            width: 34px; height: 34px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.65rem; font-size: 0.95rem;
            background: #eef1f5; color: var(--accent);
            border: 1px solid var(--border);
        }
        .stat-purple .stat-icon { background: #eef1f5; color: var(--accent); }
        .stat-cyan .stat-icon { background: #f0f9ff; color: #0369a1; }
        .stat-green .stat-icon { background: #f0fdf4; color: var(--success); }
        .stat-pink .stat-icon { background: #fef2f2; color: #b91c1c; }

        /* Table */
        .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 9px; overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; max-width: 100%; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 0.85rem 1.15rem; text-align: left;
            font-size: 0.8rem; font-weight: 600;
            color: var(--text-muted);
            background: #f7f8fa; border-bottom: 1px solid var(--border);
        }
        tbody td { padding: 0.9rem 1.15rem; border-bottom: 1px solid #eef1f5; font-size: 0.88rem; vertical-align: middle; color: var(--text); transition: background-color 0.14s ease; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f9fafb; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 5px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-purple { background: #eff6ff; color: #1e40af; }
        .badge-secondary { background: #eef1f5; color: #434d5e; }
        .badge-info { background: #e0f2fe; color: #075985; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.52rem 1rem; border-radius: 7px; font-family: inherit; font-size: 0.84rem; font-weight: 600; text-decoration: none; border: 1px solid transparent; cursor: pointer; transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease, transform 0.14s ease, box-shadow 0.16s ease; white-space: nowrap; }
        .btn:active { transform: translateY(1px); }
        .btn-primary { background: var(--text); border-color: var(--text); color: #ffffff; }
        .btn-primary:hover { background: var(--accent); border-color: var(--accent); }
        .btn-secondary { background: var(--bg-card); border-color: var(--border); color: var(--text); }
        .btn-secondary:hover { border-color: var(--text); background: #f7f8fa; }
        .btn-danger { background: #fef2f2; border-color: #fecaca; color: var(--danger); }
        .btn-danger:hover { background: #fee2e2; border-color: #fca5a5; }
        .btn-sm { padding: 0.35rem 0.72rem; font-size: 0.79rem; }

        /* Forms */
        .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1.75rem; }
        .form-group { margin-bottom: 1.35rem; }
        .form-label { display: block; font-size: 0.84rem; font-weight: 600; margin-bottom: 0.45rem; color: var(--text); }
        .form-control {
            width: 100%; padding: 0.65rem 0.9rem; border-radius: 7px;
            background: #f7f8fa; border: 1px solid var(--border);
            color: var(--text); font-family: inherit; font-size: 0.9rem;
            transition: border-color 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
        }
        .form-control:focus { outline: none; border-color: var(--accent); background: #ffffff; box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1); }
        textarea.form-control { min-height: 150px; resize: vertical; }
        .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-check input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--accent); cursor: pointer; }
        .form-check label { font-size: 0.88rem; cursor: pointer; }
        select.form-control option { background: #ffffff; }

        /* Alerts */
        .alert { padding: 0.85rem 1.05rem; border-radius: 8px; margin-bottom: 1.35rem; font-size: 0.88rem; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        .admin-hamburger {
            display: none; background: var(--bg-card); border: 1px solid var(--border);
            color: var(--text); width: 38px; height: 38px; border-radius: 8px;
            align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer;
            margin-right: 0.75rem; transition: border-color 0.16s ease, background-color 0.16s ease;
        }
        .admin-hamburger:hover { border-color: var(--text); background: #f7f8fa; }

        .admin-backdrop {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            z-index: 99; opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity 0.25s var(--ease-out), visibility 0.25s ease;
        }
        .admin-backdrop.open { opacity: 1; visibility: visible; pointer-events: auto; }

        /* Responsive */
        @media (max-width: 960px) {
            div[style*="grid-template-columns: 1fr 1fr"],
            div[style*="grid-template-columns:1fr 1fr"],
            div[style*="grid-template-columns: 2fr 1fr"],
            div[style*="grid-template-columns:2fr 1fr"],
            div[style*="grid-template-columns: 1fr 320px"],
            div[style*="grid-template-columns:1fr 320px"],
            div[style*="grid-template-columns: 1fr 340px"],
            div[style*="grid-template-columns:1fr 340px"],
            div[style*="grid-template-columns: 1fr 350px"],
            div[style*="grid-template-columns:1fr 350px"],
            div[style*="grid-template-columns: 1fr 380px"],
            div[style*="grid-template-columns:1fr 380px"] {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 900px) {
            .admin-hamburger { display: flex; }
            .sidebar { transform: translateX(-100%); box-shadow: var(--shadow-md); }
            .sidebar.open { transform: none; }
            .main { margin-left: 0; }
            .topbar { padding: 0.85rem 1rem; }
            .content { padding: 1.25rem 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            table { min-width: 600px; }
        }

        @media (max-width: 640px) {
            .page-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .admin-article-preview { padding: 1.45rem 1.1rem !important; border-radius: 12px !important; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .form-card { padding: 1.25rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
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
            border: 1px solid var(--border);
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
            padding: 1.75rem 1.5rem 1.5rem;
            text-align: center;
            box-shadow: 0 20px 50px rgba(18, 22, 31, 0.22);
            transform: scale(0.95) translateY(8px);
            transition: transform 0.22s ease;
            position: relative;
        }
        .admin-popup-overlay.active .admin-popup-dialog {
            transform: scale(1) translateY(0);
        }
        .admin-popup-icon {
            width: 54px;
            height: 54px;
            border-radius: 10px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            border: 1px solid transparent;
        }
        .admin-popup-icon.type-ai {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: var(--accent);
        }
        .admin-popup-icon.type-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--danger);
        }
        .admin-popup-icon.type-publish {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: var(--success);
        }
        .admin-popup-title {
            font-size: 1.12rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.45rem;
            letter-spacing: -0.01em;
        }
        .admin-popup-desc {
            font-size: 0.9rem;
            color: #434d5e;
            line-height: 1.55;
            margin-bottom: 1.35rem;
        }
        .admin-popup-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }
        .admin-popup-actions .btn {
            justify-content: center;
            padding: 0.65rem 1rem;
            border-radius: 7px;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .admin-popup-btn-danger {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff;
        }
        .admin-popup-btn-danger:hover { background: #b91c1c; border-color: #b91c1c; }
        .admin-popup-btn-publish {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff;
        }
        .admin-popup-btn-publish:hover { background: #166534; border-color: #166534; }
        .admin-popup-btn-ai {
            background: var(--text);
            border-color: var(--text);
            color: #ffffff;
        }
        .admin-popup-btn-ai:hover { background: var(--accent); border-color: var(--accent); }

        /* AI Spinner inside Loading State */
        .admin-ai-loader {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid #dce1e9;
            border-top-color: var(--accent);
            animation: adminSpin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes adminSpin {
            to { transform: rotate(360deg); }
        }
        .admin-popup-timer {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent);
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 0.3rem 0.75rem;
            border-radius: 6px;
        }

        /* Floating Toast Stack */
        .admin-toast-stack {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
            max-width: min(400px, calc(100vw - 2rem));
            pointer-events: none;
        }
        .admin-toast {
            pointer-events: auto;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.9rem 1.05rem;
            box-shadow: 0 12px 32px rgba(18, 22, 31, 0.14);
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            position: relative;
            overflow: hidden;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease;
        }
        .admin-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .admin-toast-icon {
            width: 34px;
            height: 34px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }
        .admin-toast.toast-success .admin-toast-icon { background: #dcfce7; color: #15803d; }
        .admin-toast.toast-error .admin-toast-icon { background: #fee2e2; color: #dc2626; }
        .admin-toast-body { flex: 1; min-width: 0; }
        .admin-toast-title { font-size: 0.86rem; font-weight: 700; color: var(--text); margin-bottom: 0.15rem; }
        .admin-toast-msg { font-size: 0.82rem; color: #434d5e; line-height: 1.45; word-break: break-word; }
        .admin-toast-close {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.95rem;
            padding: 0.15rem;
            line-height: 1;
        }
        .admin-toast-close:hover { color: var(--text); }
        .admin-toast-bar {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
            animation: toastProgress 5.5s linear forwards;
        }
        .admin-toast.toast-success .admin-toast-bar { background: #15803d; }
        .admin-toast.toast-error .admin-toast-bar { background: #dc2626; }
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
            <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> Admin Profile
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
                <span class="badge" style="background:#eef1f5; color:var(--accent); border:1px solid var(--border); font-size:0.68rem; padding:0.1rem 0.42rem">AI 4.0</span>
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
                <button type="button" class="admin-hamburger" id="admin-hamburger" aria-label="Toggle sidebar" aria-expanded="false" aria-controls="sidebar">
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
                <a href="{{ route('admin.profile.index') }}" class="topbar-avatar" title="Admin Profile ({{ Auth::user()->email ?? 'Admin' }})">
                    {{ strtoupper(mb_substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </a>
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
                adminHamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                document.body.style.overflow = (isOpen && window.innerWidth <= 900) ? 'hidden' : '';
            }

            adminHamburger.addEventListener('click', () => toggleAdminSidebar());
            if (adminBackdrop) adminBackdrop.addEventListener('click', () => toggleAdminSidebar(false));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && adminSidebar.classList.contains('open')) {
                    toggleAdminSidebar(false);
                    adminHamburger.focus();
                }
            });

            adminSidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 900) toggleAdminSidebar(false);
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
