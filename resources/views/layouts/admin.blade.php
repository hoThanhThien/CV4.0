<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'vi' ? 'vi' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — CV Studio 4.0</title>

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- FOUC Prevention: synchronize Pearl Light/Dark mode with public site --}}
    <script>
        if (localStorage.getItem('thien_theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    {{-- Non-blocking FontAwesome fallback for legacy child CRUD icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"></noscript>

    {{-- Self-hosted bundled Admin Lucide Icons --}}
    <script src="{{ asset('js/lucide-admin.js') }}?v={{ @filemtime(public_path('js/lucide-admin.js')) ?: 1 }}" defer></script>

    @php
        $fontsCssPath = resource_path('css/fonts.css');
        $inlineFontsCss = is_file($fontsCssPath) ? file_get_contents($fontsCssPath) : '';
    @endphp

    <style>
        {!! $inlineFontsCss !!}

        :root {
            --bg: #f8fafc;
            --bg-grid: rgba(15, 23, 42, 0.045);
            --bg-sidebar: rgba(255, 255, 255, 0.94);
            --bg-card: #ffffff;
            --bg-subtle: #f1f5f9;
            --bg-hover: #f8fafc;
            --topbar-bg: rgba(255, 255, 255, 0.86);
            --input-bg: #f8fafc;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --text: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --accent: #0369a1;
            --primary: #0369a1;
            --accent-light: #0284c7;
            --accent-soft: #e0f2fe;
            --accent-tint: rgba(2, 132, 199, 0.08);
            --gradient: linear-gradient(135deg, #0ea5e9, #4f46e5);
            --sidebar-width: 262px;
            --danger: #dc2626;
            --success: #15803d;
            --warning: #b45309;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-sm: 0 2px 10px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 14px 34px rgba(15, 23, 42, 0.1);
            color-scheme: light;
        }

        html.dark {
            --bg: #020617;
            --bg-grid: rgba(148, 163, 184, 0.075);
            --bg-sidebar: rgba(15, 23, 42, 0.95);
            --bg-card: #0f172a;
            --bg-subtle: #1e293b;
            --bg-hover: rgba(30, 41, 59, 0.72);
            --topbar-bg: rgba(2, 6, 23, 0.85);
            --input-bg: #0b1120;
            --border: #1e293b;
            --border-strong: #334155;
            --text: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --primary: #38bdf8;
            --accent-light: #7dd3fc;
            --accent-soft: rgba(56, 189, 248, 0.14);
            --accent-tint: rgba(56, 189, 248, 0.12);
            --gradient: linear-gradient(135deg, #38bdf8, #818cf8);
            --danger: #f87171;
            --success: #4ade80;
            --warning: #fbbf24;
            --shadow-sm: 0 4px 14px rgba(2, 6, 23, 0.45);
            --shadow-md: 0 18px 42px rgba(2, 6, 23, 0.72);
            color-scheme: dark;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Inter Fallback', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg);
            background-image:
                radial-gradient(circle at 12% 8%, rgba(56, 189, 248, 0.12), transparent 36%),
                radial-gradient(circle at 88% 22%, rgba(99, 102, 241, 0.1), transparent 38%),
                radial-gradient(var(--bg-grid) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 24px 24px;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        h1, h2, h3, .font-display {
            font-family: 'Space Grotesk', 'Space Grotesk Fallback', 'Inter', sans-serif;
        }
        code, pre, .font-mono {
            font-family: 'JetBrains Mono', 'JetBrains Mono Fallback', ui-monospace, monospace;
        }

        svg.lucide { flex-shrink: 0; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.25s var(--ease-out), background-color 0.2s ease, border-color 0.2s ease;
        }

        .sidebar-logo {
            padding: 1.15rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text);
        }
        .sidebar-brand-mark {
            width: 36px; height: 36px;
            border-radius: 10px;
            overflow: hidden;
            background: var(--gradient);
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.22);
        }
        .sidebar-brand-mark img { width: 36px; height: 36px; object-fit: cover; display: block; }
        .sidebar-logo-text { min-width: 0; }
        .sidebar-logo-title {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-size: 1rem; font-weight: 700;
            letter-spacing: -0.02em; color: var(--text);
            display: flex; align-items: center; gap: 0.4rem;
        }
        .sidebar-live-dot {
            width: 7px; height: 7px; border-radius: 999px;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        .sidebar-logo small {
            display: block; font-size: 0.72rem; font-weight: 500;
            color: var(--text-muted); margin-top: 0.1rem;
            font-family: 'JetBrains Mono', monospace;
        }

        .sidebar-nav { flex: 1; padding: 0.9rem 0.75rem; overflow-y: auto; }

        .nav-section-title {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.69rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--text-muted);
            padding: 0.55rem 0.75rem 0.3rem; margin-bottom: 0.15rem;
        }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 0.68rem;
            padding: 0.56rem 0.75rem; border-radius: 9px;
            color: var(--text-secondary); text-decoration: none;
            font-size: 0.86rem; font-weight: 500;
            transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
            margin-bottom: 0.18rem;
            border: 1px solid transparent;
        }

        .sidebar-nav a:hover {
            background: var(--bg-subtle); color: var(--text);
        }
        .sidebar-nav a.active {
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 600;
            border-color: rgba(14, 165, 233, 0.24);
        }
        .sidebar-nav a svg.lucide,
        .sidebar-nav a i {
            width: 17px; height: 17px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }
        .sidebar-nav a.active svg.lucide,
        .sidebar-nav a.active i { color: var(--accent); }

        .sidebar-footer {
            padding: 0.85rem 0.75rem; border-top: 1px solid var(--border);
        }
        .sidebar-footer form button {
            width: 100%; display: flex; align-items: center; gap: 0.68rem;
            padding: 0.58rem 0.75rem; border-radius: 9px;
            background: none; border: none; color: var(--text-secondary);
            font-family: inherit; font-size: 0.86rem; font-weight: 600; cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-footer form button svg.lucide { width: 17px; height: 17px; }
        .sidebar-footer form button:hover { background: rgba(220, 38, 38, 0.1); color: var(--danger); }

        /* Main content */
        .main {
            margin-left: var(--sidebar-width);
            flex: 1; min-width: 0; max-width: 100%; overflow-x: hidden;
            display: flex; flex-direction: column; min-height: 100vh;
        }

        /* Top bar */
        .topbar {
            background: var(--topbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
            gap: 0.75rem;
        }

        .topbar-title { font-size: 1rem; font-weight: 700; color: var(--text); min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-title span { color: var(--text-muted); font-weight: 500; font-size: 0.85rem; }

        .topbar-right { display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0; }

        .admin-theme-btn {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--bg-card); border: 1px solid var(--border);
            color: var(--text-secondary);
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer; transition: border-color 0.16s ease, color 0.16s ease, background-color 0.16s ease;
        }
        .admin-theme-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--bg-subtle); }
        .admin-theme-btn svg.lucide { width: 17px; height: 17px; }
        html:not(.dark) #adminThemeIconLight { display: none; }
        html.dark #adminThemeIconDark { display: none; }

        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--text); color: var(--bg-card);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.86rem; text-decoration: none;
            border: 1px solid var(--border-strong);
            transition: background-color 0.16s ease, border-color 0.16s ease, transform 0.15s ease, box-shadow 0.16s ease;
        }
        .topbar-avatar:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Content area */
        .content { padding: 1.75rem 1.5rem; flex: 1; max-width: 1440px; width: 100%; margin: 0 auto; }

        :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

        /* Page header */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.65rem; flex-wrap: wrap; gap: 1rem;
        }
        .page-header > div { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
        .page-header h1 {
            font-size: 1.48rem; font-weight: 700; color: var(--text);
            letter-spacing: -0.025em;
            display: inline-flex; align-items: center; gap: 0.55rem;
        }
        .page-header h1 svg.lucide { width: 22px; height: 22px; color: var(--accent); }

        /* Cards/Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(175px, 1fr)); gap: 1rem; margin-bottom: 1.65rem; }
        .stat-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 14px; padding: 1.2rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s var(--ease-out), border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-2px); border-color: var(--border-strong); box-shadow: var(--shadow-md); }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 500; }
        .stat-card .stat-value { font-family: 'Space Grotesk', sans-serif; font-size: 1.85rem; font-weight: 700; color: var(--text); line-height: 1.15; }
        .stat-card .stat-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem; font-family: 'JetBrains Mono', monospace; }
        .stat-card .stat-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.7rem; font-size: 0.95rem;
            background: var(--accent-soft); color: var(--accent);
            border: 1px solid var(--border);
        }
        .stat-card .stat-icon svg.lucide { width: 18px; height: 18px; }
        .stat-purple .stat-icon { background: rgba(99, 102, 241, 0.12); color: #6366f1; }
        html.dark .stat-purple .stat-icon { color: #a5b4fc; }
        .stat-cyan .stat-icon { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
        html.dark .stat-cyan .stat-icon { color: #38bdf8; }
        .stat-green .stat-icon { background: rgba(21, 128, 61, 0.12); color: var(--success); }
        .stat-pink .stat-icon { background: rgba(220, 38, 38, 0.12); color: var(--danger); }

        /* Analytics & Progress helpers */
        .analytics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 1.25rem; margin-bottom: 1.85rem; }
        .panel-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 14px; padding: 1.25rem 1.35rem;
            box-shadow: var(--shadow-sm);
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            gap: 0.75rem; margin-bottom: 1rem;
        }
        .panel-title {
            font-weight: 700; font-size: 0.96rem; color: var(--text);
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .panel-title svg.lucide { width: 17px; height: 17px; color: var(--accent); }
        .metric-bar-track {
            width: 100%; height: 8px; border-radius: 999px;
            background: var(--bg-subtle); overflow: hidden; margin: 0.45rem 0 0.75rem;
        }
        .metric-bar-fill {
            height: 100%; border-radius: 999px;
            background: var(--gradient); transition: width 0.35s var(--ease-out);
        }

        /* Table */
        .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; max-width: 100%; box-shadow: var(--shadow-sm); }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 0.85rem 1.15rem; text-align: left;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.74rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;
            color: var(--text-muted);
            background: var(--bg-subtle); border-bottom: 1px solid var(--border);
        }
        tbody td { padding: 0.9rem 1.15rem; border-bottom: 1px solid var(--border); font-size: 0.88rem; vertical-align: middle; color: var(--text); transition: background-color 0.14s ease; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--bg-hover); }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 0.32rem; padding: 0.22rem 0.62rem; border-radius: 6px; font-size: 0.74rem; font-weight: 600; white-space: nowrap; }
        .badge svg.lucide { width: 13px; height: 13px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-purple { background: #eff6ff; color: #1e40af; }
        .badge-secondary { background: #eef1f5; color: #434d5e; }
        .badge-info { background: #e0f2fe; color: #075985; }

        html.dark .badge-success { background: rgba(34, 197, 94, 0.16); color: #86efac; }
        html.dark .badge-danger { background: rgba(239, 68, 68, 0.16); color: #fca5a5; }
        html.dark .badge-warning { background: rgba(245, 158, 11, 0.16); color: #fcd34d; }
        html.dark .badge-purple { background: rgba(99, 102, 241, 0.18); color: #c7d2fe; }
        html.dark .badge-secondary { background: rgba(148, 163, 184, 0.16); color: #cbd5e1; }
        html.dark .badge-info { background: rgba(56, 189, 248, 0.18); color: #bae6fd; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 0.42rem; padding: 0.54rem 1rem; border-radius: 9px; font-family: inherit; font-size: 0.84rem; font-weight: 600; text-decoration: none; border: 1px solid transparent; cursor: pointer; transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease, transform 0.14s ease, box-shadow 0.16s ease; white-space: nowrap; }
        .btn svg.lucide { width: 15px; height: 15px; }
        .btn:active { transform: translateY(1px); }
        .btn-primary { background: var(--text); border-color: var(--text); color: var(--bg-card); }
        .btn-primary:hover { background: var(--accent); border-color: var(--accent); color: #ffffff; }
        html.dark .btn-primary { background: #0284c7; border-color: #0284c7; color: #ffffff; }
        html.dark .btn-primary:hover { background: #0ea5e9; border-color: #0ea5e9; }
        .btn-secondary { background: var(--bg-card); border-color: var(--border); color: var(--text); }
        .btn-secondary:hover { border-color: var(--border-strong); background: var(--bg-subtle); }
        .btn-danger { background: rgba(220, 38, 38, 0.08); border-color: rgba(220, 38, 38, 0.25); color: var(--danger); }
        .btn-danger:hover { background: rgba(220, 38, 38, 0.16); border-color: rgba(220, 38, 38, 0.4); }
        .btn-sm { padding: 0.36rem 0.74rem; font-size: 0.79rem; }

        /* Forms */
        .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 1.75rem; box-shadow: var(--shadow-sm); }
        .form-group { margin-bottom: 1.35rem; }
        .form-label { display: block; font-size: 0.84rem; font-weight: 600; margin-bottom: 0.45rem; color: var(--text); }
        .form-control {
            width: 100%; padding: 0.65rem 0.9rem; border-radius: 9px;
            background: var(--input-bg); border: 1px solid var(--border);
            color: var(--text); font-family: inherit; font-size: 0.9rem;
            transition: border-color 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
        }
        .form-control:focus { outline: none; border-color: var(--accent); background: var(--bg-card); box-shadow: 0 0 0 3px var(--accent-tint); }
        textarea.form-control { min-height: 150px; resize: vertical; }
        .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-check input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--accent); cursor: pointer; }
        .form-check label { font-size: 0.88rem; cursor: pointer; color: var(--text); }
        select.form-control option { background: var(--bg-card); color: var(--text); }

        /* Dark-mode safety overrides for child forms with legacy inline light surfaces */
        html.dark .admin-article-preview,
        html.dark [style*="background:#ffffff"],
        html.dark [style*="background: #ffffff"],
        html.dark [style*="background:#fff"],
        html.dark [style*="background: #fff"],
        html.dark [style*="background:#f7f8fa"],
        html.dark [style*="background: #f7f8fa"],
        html.dark [style*="background:#eef1f5"],
        html.dark [style*="background: #eef1f5"] {
            background: var(--bg-subtle) !important;
            color: var(--text) !important;
            border-color: var(--border-strong) !important;
        }
        html.dark [style*="background:#eff6ff"],
        html.dark [style*="background: #eff6ff"] {
            background: var(--accent-soft) !important;
            color: var(--text) !important;
            border-color: rgba(56, 189, 248, 0.35) !important;
        }

        /* Alerts */
        .alert { padding: 0.85rem 1.05rem; border-radius: 10px; margin-bottom: 1.35rem; font-size: 0.88rem; display: flex; align-items: flex-start; gap: 0.6rem; }
        .alert svg.lucide { width: 18px; height: 18px; margin-top: 0.1rem; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        html.dark .alert-success { background: rgba(34, 197, 94, 0.12); border-color: rgba(34, 197, 94, 0.3); color: #86efac; }
        html.dark .alert-danger { background: rgba(239, 68, 68, 0.12); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }

        .admin-hamburger {
            display: none; background: var(--bg-card); border: 1px solid var(--border);
            color: var(--text); width: 38px; height: 38px; border-radius: 9px;
            align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer;
            margin-right: 0.7rem; transition: border-color 0.16s ease, background-color 0.16s ease;
        }
        .admin-hamburger:hover { border-color: var(--accent); background: var(--bg-subtle); }
        .admin-hamburger svg.lucide { width: 19px; height: 19px; }

        .admin-backdrop {
            position: fixed; inset: 0; background: rgba(2, 6, 23, 0.52);
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
            .topbar { padding: 0.8rem 1rem; }
            .content { padding: 1.25rem 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            table { min-width: 600px; }
        }

        @media (max-width: 640px) {
            .page-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .admin-article-preview { padding: 1.45rem 1.1rem !important; border-radius: 12px !important; }
            .topbar-view-label { display: none; }
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
            position: fixed; inset: 0; z-index: 9998;
            background: rgba(2, 6, 23, 0.58);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center;
            padding: 1.25rem; opacity: 0; visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .admin-popup-overlay.active { opacity: 1; visibility: visible; }
        .admin-popup-dialog {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 16px; width: 100%; max-width: 420px;
            padding: 1.75rem 1.5rem 1.5rem; text-align: center;
            box-shadow: var(--shadow-md);
            transform: scale(0.95) translateY(8px);
            transition: transform 0.22s ease;
            position: relative;
        }
        .admin-popup-overlay.active .admin-popup-dialog { transform: scale(1) translateY(0); }
        .admin-popup-icon {
            width: 54px; height: 54px; border-radius: 12px;
            margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;
            border: 1px solid transparent;
        }
        .admin-popup-icon svg.lucide { width: 24px; height: 24px; }
        .admin-popup-icon.type-ai { background: var(--accent-soft); border-color: rgba(14, 165, 233, 0.35); color: var(--accent); }
        .admin-popup-icon.type-danger { background: rgba(220, 38, 38, 0.12); border-color: rgba(220, 38, 38, 0.3); color: var(--danger); }
        .admin-popup-icon.type-publish { background: rgba(21, 128, 61, 0.12); border-color: rgba(21, 128, 61, 0.3); color: var(--success); }
        .admin-popup-title { font-size: 1.12rem; font-weight: 700; color: var(--text); margin-bottom: 0.45rem; letter-spacing: -0.01em; }
        .admin-popup-desc { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.55; margin-bottom: 1.35rem; }
        .admin-popup-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; }
        .admin-popup-actions .btn { justify-content: center; padding: 0.65rem 1rem; border-radius: 9px; font-size: 0.88rem; font-weight: 600; }
        .admin-popup-btn-danger { background: #dc2626; border-color: #dc2626; color: #ffffff; }
        .admin-popup-btn-danger:hover { background: #b91c1c; border-color: #b91c1c; }
        .admin-popup-btn-publish { background: #15803d; border-color: #15803d; color: #ffffff; }
        .admin-popup-btn-publish:hover { background: #166534; border-color: #166534; }
        .admin-popup-btn-ai { background: var(--text); border-color: var(--text); color: var(--bg-card); }
        .admin-popup-btn-ai:hover { background: var(--accent); border-color: var(--accent); color: #ffffff; }
        html.dark .admin-popup-btn-ai { background: #0284c7; border-color: #0284c7; color: #ffffff; }

        /* AI Spinner inside Loading State */
        .admin-ai-loader {
            width: 48px; height: 48px; border-radius: 50%;
            border: 3px solid var(--border); border-top-color: var(--accent);
            animation: adminSpin 0.8s linear infinite; margin: 0 auto 1rem;
        }
        @keyframes adminSpin { to { transform: rotate(360deg); } }
        .admin-popup-timer {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; font-weight: 600;
            color: var(--accent); background: var(--accent-soft); border: 1px solid rgba(14, 165, 233, 0.3);
            padding: 0.32rem 0.75rem; border-radius: 8px;
        }
        .admin-popup-timer svg.lucide { width: 14px; height: 14px; }

        /* Floating Toast Stack */
        .admin-toast-stack {
            position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999;
            display: flex; flex-direction: column; gap: 0.7rem;
            max-width: min(400px, calc(100vw - 2rem)); pointer-events: none;
        }
        .admin-toast {
            pointer-events: auto; background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 12px; padding: 0.9rem 1.05rem; box-shadow: var(--shadow-md);
            display: flex; align-items: flex-start; gap: 0.8rem;
            position: relative; overflow: hidden;
            transform: translateX(120%); opacity: 0;
            transition: transform 0.3s var(--ease-out), opacity 0.2s ease;
        }
        .admin-toast.show { transform: translateX(0); opacity: 1; }
        .admin-toast-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .admin-toast-icon svg.lucide { width: 18px; height: 18px; }
        .admin-toast.toast-success .admin-toast-icon { background: rgba(21, 128, 61, 0.14); color: var(--success); }
        .admin-toast.toast-error .admin-toast-icon { background: rgba(220, 38, 38, 0.14); color: var(--danger); }
        .admin-toast-body { flex: 1; min-width: 0; }
        .admin-toast-title { font-size: 0.86rem; font-weight: 700; color: var(--text); margin-bottom: 0.15rem; }
        .admin-toast-msg { font-size: 0.82rem; color: var(--text-secondary); line-height: 1.45; word-break: break-word; }
        .admin-toast-close {
            background: transparent; border: none; color: var(--text-muted);
            cursor: pointer; font-size: 0.95rem; padding: 0.15rem; line-height: 1;
        }
        .admin-toast-close:hover { color: var(--text); }
        .admin-toast-close svg.lucide { width: 15px; height: 15px; }
        .admin-toast-bar {
            position: absolute; left: 0; bottom: 0; height: 3px; width: 100%;
            transform-origin: left; animation: toastProgress 5.5s linear forwards;
        }
        .admin-toast.toast-success .admin-toast-bar { background: var(--success); }
        .admin-toast.toast-error .admin-toast-bar { background: var(--danger); }
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
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <span class="sidebar-brand-mark">
                <img src="{{ asset('images/avatar-sm.webp') }}" width="36" height="36" alt="Ho Thanh Thien Admin Logo">
            </span>
            <span class="sidebar-logo-text">
                <span class="sidebar-logo-title">
                    CV Studio
                    <span class="sidebar-live-dot" title="System Active"></span>
                </span>
                <small>Pearl White 4.0</small>
            </span>
        </a>

        <nav class="sidebar-nav" aria-label="Admin navigation">
            <div class="nav-section-title">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="gauge"></i> Dashboard
            </a>
            <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i data-lucide="shield-check"></i> Admin Profile
            </a>

            <div class="nav-section-title" style="margin-top:0.9rem">Content</div>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i data-lucide="folder-code"></i> Projects
            </a>
            <a href="{{ route('admin.blog.index') }}" class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i data-lucide="newspaper"></i> Blog Posts
            </a>
            <a href="{{ route('admin.skills.index') }}" class="{{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                <i data-lucide="sparkles"></i> Skills
            </a>
            <a href="{{ route('admin.experiences.index') }}" class="{{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                <i data-lucide="briefcase"></i> Experiences
            </a>
            <a href="{{ route('admin.certificates.index') }}" class="{{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <i data-lucide="award"></i> Brochure &amp; Giấy khen
            </a>

            <div class="nav-section-title" style="margin-top:0.9rem; display:flex; align-items:center; justify-content:space-between">
                <span>AI Blog Studio</span>
                <span class="badge badge-info" style="font-size:0.65rem; padding:0.1rem 0.42rem">RAG + AI</span>
            </div>
            <a href="{{ route('admin.ai.dashboard') }}" class="{{ request()->routeIs('admin.ai.dashboard') ? 'active' : '' }}">
                <i data-lucide="bar-chart-3"></i> AI Dashboard
            </a>
            <a href="{{ route('admin.ai.posts.index') }}" class="{{ request()->routeIs('admin.ai.posts.*') ? 'active' : '' }}">
                <i data-lucide="bot"></i> AI Blog Posts
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
                <i data-lucide="clock"></i> Automations
            </a>
            <a href="{{ route('admin.ai.configs.index') }}" class="{{ request()->routeIs('admin.ai.configs.*') ? 'active' : '' }}">
                <i data-lucide="sliders-horizontal"></i> Content Configs
            </a>
            <a href="{{ route('admin.ai.providers.index') }}" class="{{ request()->routeIs('admin.ai.providers.*') ? 'active' : '' }}">
                <i data-lucide="cpu"></i> AI Providers
            </a>
            <a href="{{ route('admin.ai.logs.index') }}" class="{{ request()->routeIs('admin.ai.logs.*') ? 'active' : '' }}">
                <i data-lucide="history"></i> Automation Logs
            </a>

            <div class="nav-section-title" style="margin-top:0.9rem">Inbox</div>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i data-lucide="mail"></i> Messages
                @php
                    try {
                        $sidebarUnread = \App\Models\ContactMessage::where('is_read', false)->count();
                    } catch (\Throwable $e) {
                        $sidebarUnread = 0;
                    }
                @endphp
                @if($sidebarUnread > 0)
                    <span class="badge badge-danger" style="margin-left:auto; font-size:0.7rem; padding:0.15rem 0.45rem">{{ $sidebarUnread }}</span>
                @endif
            </a>

            <div class="nav-section-title" style="margin-top:0.9rem">View Site</div>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">
                <i data-lucide="external-link"></i> Visit Portfolio
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">
                    <i data-lucide="log-out"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main">
        <header class="topbar">
            <div style="display: flex; align-items: center; min-width: 0;">
                <button type="button" class="admin-hamburger" id="admin-hamburger" aria-label="Toggle sidebar" aria-expanded="false" aria-controls="sidebar">
                    <i data-lucide="menu"></i>
                </button>
                <div class="topbar-title">
                    @yield('title', 'Dashboard')
                    <span>/ Pearl Admin</span>
                </div>
            </div>
            <div class="topbar-right">
                <button type="button" class="admin-theme-btn" id="adminThemeToggle" onclick="window.toggleAdminTheme()" aria-label="Toggle dark mode" title="Chuyển chế độ Sáng / Tối (Pearl White / Dark)">
                    <i data-lucide="sun" id="adminThemeIconLight"></i>
                    <i data-lucide="moon" id="adminThemeIconDark"></i>
                </button>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
                    <i data-lucide="external-link"></i> <span class="topbar-view-label">View Site</span>
                </a>
                <a href="{{ route('admin.profile.index') }}" class="topbar-avatar" title="Admin Profile ({{ Auth::user()->email ?? 'Admin' }})">
                    {{ strtoupper(mb_substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </a>
            </div>
        </header>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i data-lucide="check-circle-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i data-lucide="alert-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i data-lucide="alert-triangle"></i>
                    <div>
                        <strong>Please correct the following errors:</strong>
                        <ul style="margin: 0.45rem 0 0 1.2rem; font-size: 0.85rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
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
                    <i data-lucide="zap" id="adminPopupIconGlyph"></i>
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
                    Hệ thống đang kết nối tới AI Provider để biên soạn bài viết chuẩn SEO. Vui lòng giữ nguyên trang trong giây lát...
                </div>
                <div class="admin-popup-timer">
                    <i data-lucide="clock"></i> <span id="adminPopupElapsed">00:00s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Pop-up Toast Container -->
    <div class="admin-toast-stack" id="adminToastStack"></div>

    <script>
        window.toggleAdminTheme = function() {
            const isDark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('thien_theme', isDark ? 'dark' : 'light');
            const btn = document.getElementById('adminThemeToggle');
            if (btn) btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        };

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

        function mapLegacyIconToLucide(rawIcon) {
            const map = {
                'fa-bolt': 'zap',
                'fa-trash': 'trash-2',
                'fa-trash-alt': 'trash-2',
                'fa-globe': 'globe',
                'fa-robot': 'bot',
                'fa-magic': 'sparkles',
                'fa-clock': 'clock',
                'fa-check': 'check',
                'fa-check-circle': 'check-circle-2',
                'fa-exclamation-circle': 'alert-circle',
                'fa-exclamation-triangle': 'alert-triangle',
                'fa-sync-alt': 'rotate-ccw'
            };
            const clean = String(rawIcon || '').replace(/^(fas|far|fab)\s+/i, '').trim();
            return map[clean] || clean || 'zap';
        }

        // Floating Toast Pop-up Helper
        window.showAdminToast = function(message, type = 'success', title = null) {
            const stack = document.getElementById('adminToastStack');
            if (!stack || !message) return;

            const isError = (type === 'error' || type === 'danger');
            const toast = document.createElement('div');
            toast.className = 'admin-toast ' + (isError ? 'toast-error' : 'toast-success');
            const heading = title || (isError ? 'Thông báo lỗi' : 'Thao tác thành công');
            const iconName = isError ? 'alert-circle' : 'check-circle-2';

            toast.innerHTML = `
                <div class="admin-toast-icon"><i data-lucide="${iconName}"></i></div>
                <div class="admin-toast-body">
                    <div class="admin-toast-title"></div>
                    <div class="admin-toast-msg"></div>
                </div>
                <button type="button" class="admin-toast-close" aria-label="Đóng"><i data-lucide="x"></i></button>
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
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons({ root: toast });
            }
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(dismiss, 5500);
        };

        // Custom Pop-up Confirmation & AI Loading Modal
        (function() {
            const overlay = document.getElementById('adminPopupOverlay');
            const confirmView = document.getElementById('adminPopupConfirmView');
            const loadingView = document.getElementById('adminPopupLoadingView');
            const iconBox = document.getElementById('adminPopupIcon');
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
                    danger: '<i data-lucide="trash-2"></i> Xóa ngay',
                    publish: '<i data-lucide="globe"></i> Xuất bản ngay',
                    ai: '<i data-lucide="zap"></i> Chạy ngay'
                };
                const icons = {
                    danger: 'trash-2',
                    publish: 'globe',
                    ai: 'zap'
                };

                confirmView.style.display = 'block';
                loadingView.style.display = 'none';

                const lucideIcon = mapLegacyIconToLucide(form.getAttribute('data-confirm-icon') || icons[type] || 'zap');
                iconBox.className = 'admin-popup-icon type-' + (icons[type] ? type : 'ai');
                iconBox.innerHTML = `<i data-lucide="${lucideIcon}" id="adminPopupIconGlyph"></i>`;
                titleEl.textContent = form.getAttribute('data-confirm-title') || defaultTitles[type] || 'Xác nhận thao tác';
                descEl.textContent = msg;

                confirmBtn.className = 'btn admin-popup-btn-' + (icons[type] ? type : 'ai');
                const customBtn = form.getAttribute('data-confirm-btn');
                if (customBtn) {
                    confirmBtn.innerHTML = customBtn.replace(/<i[^>]*class=['"](?:fas|far|fab)\s+([^'"]+)['"][^>]*><\/i>/gi, (_, cls) => {
                        return `<i data-lucide="${mapLegacyIconToLucide(cls)}"></i>`;
                    });
                } else {
                    confirmBtn.innerHTML = defaultBtnLabels[type] || 'Xác nhận';
                }

                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons({ root: overlay });
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
                const themeBtn = document.getElementById('adminThemeToggle');
                if (themeBtn) {
                    themeBtn.setAttribute('aria-pressed', document.documentElement.classList.contains('dark') ? 'true' : 'false');
                }

                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }

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
