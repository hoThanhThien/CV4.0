<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Full-Stack Developer Portfolio - Building modern web applications')">
    <title>@yield('title', __('Hồ Thành Thiện') . ' | Developer') </title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent: #6366f1;
            --accent-light: #8b5cf6;
            --accent-glow: rgba(99, 102, 241, 0.2);
            --cyan: #06b6d4;
            --green: #10b981;
            --pink: #ec4899;
            --gradient: linear-gradient(135deg, #6366f1, #06b6d4);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Navigation */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.75rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .nav-logo {
            font-size: 1.4rem; font-weight: 800;
            color: var(--accent);
            text-decoration: none;
        }

        .nav-links { display: flex; gap: 0.25rem; list-style: none; }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none; padding: 0.5rem 1rem;
            border-radius: 8px; font-size: 0.9rem; font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-links a:not(.btn):hover, .nav-links a:not(.btn).active {
            color: var(--text-primary);
            background: var(--bg-card);
        }

        .nav-cta {
            background: var(--gradient); color: white !important;
            padding: 0.5rem 1.25rem !important; border-radius: 8px !important;
        }

        .nav-cta:hover { opacity: 0.9; transform: translateY(-1px); }

        .hamburger { display: none; background: none; border: none; color: var(--text-primary); font-size: 1.5rem; cursor: pointer; }

        /* Page content offset for fixed navbar */
        .page-content { padding-top: 70px; }

        /* Footer */
        .footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            padding: 3rem 2rem 2rem;
            text-align: center;
        }

        .footer-logo {
            font-size: 1.5rem; font-weight: 800;
            color: var(--accent);
            margin-bottom: 1rem; display: block;
        }

        .footer-links { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .footer-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
        .footer-links a:hover { color: var(--accent-light); }

        .footer-socials { display: flex; justify-content: center; gap: 1rem; margin-bottom: 1.5rem; }
        .footer-socials a {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--bg-card); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); text-decoration: none;
            transition: all 0.2s ease;
        }
        .footer-socials a:hover { color: var(--accent-light); border-color: var(--accent); transform: translateY(-2px); }

        .footer-copy { color: var(--text-secondary); font-size: 0.85rem; }

        /* Shared utilities */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .section { padding: 6rem 0; }
        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 800;
            text-align: center; margin-bottom: 0.75rem;
        }
        .section-subtitle {
            color: var(--text-secondary); text-align: center;
            font-size: 1.05rem; margin-bottom: 3.5rem;
        }
        .gradient-text {
            color: var(--accent);
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1.25rem; border-radius: 8px;
            font-size: 0.9rem; font-weight: 600; text-decoration: none;
            border: none; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-primary { background: var(--gradient); background-size: 200% auto; color: white; transition: all 0.4s ease; }
        .btn-primary:hover { background-position: right center; opacity: 1; transform: translateY(-2px); box-shadow: 0 8px 30px var(--accent-glow); }
        .btn-outline { background: transparent; color: var(--text-primary); border: 1px solid var(--border); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent-light); }

        .tag {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.75rem; border-radius: 6px;
            font-size: 0.78rem; font-weight: 600;
            background: rgba(124, 58, 237, 0.15); color: var(--accent-light);
            border: 1px solid rgba(124, 58, 237, 0.3);
        }

        /* Alerts */
        .alert { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #10b981; }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; }

        /* Pagination */
        .pagination-wrap { display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem; flex-wrap: wrap; }
        .pagination-wrap .page-link {
            padding: 0.5rem 0.9rem; border-radius: 8px; text-decoration: none;
            background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary);
            transition: all 0.2s;
        }
        .pagination-wrap .page-link:hover, .pagination-wrap .page-link.active {
            background: var(--accent); border-color: var(--accent); color: white;
        }

        /* Mobile menu */
        @media (max-width: 768px) {
            .hamburger { display: block; }
            .nav-links {
                display: none; flex-direction: column;
                position: absolute; top: 100%; left: 0; right: 0;
                background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);
                padding: 1rem; border-bottom: 1px solid var(--border);
            }
            .nav-links.open { display: flex; }
            .nav-links a { padding: 0.75rem 1rem; }
        }

        /* Back to top */
        .back-to-top {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 999;
            width: 45px; height: 45px; border-radius: 50%;
            background: var(--accent); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; cursor: pointer; border: none;
            box-shadow: 0 4px 12px var(--accent-glow);
            opacity: 0; pointer-events: none; transform: translateY(20px);
            transition: all 0.3s ease;
        }
        .back-to-top.visible {
            opacity: 1; pointer-events: auto; transform: translateY(0);
        }
        .back-to-top:hover {
            background: var(--accent-light); transform: translateY(-3px); box-shadow: 0 6px 16px var(--accent-glow);
        }

        /* Noise overlay */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: -1;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="nav-logo">&lt;{{ __('Thiện') }} /&gt;</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-links" id="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a></li>
            <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">{{ __('Projects') }}</a></li>
            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('About') }}</a></li>
            <li><a href="mailto:hothanhthien119@gmail.com" class="btn btn-primary nav-cta">{{ __('Hire Me') }}</a></li>
            <li style="display:flex; align-items:center; margin-left: 1rem; gap: 0.5rem">
                <a href="{{ route('lang.switch', 'vi') }}" style="padding: 0.2rem 0.5rem; {{ session('locale') == 'vi' ? 'font-weight: bold; color: var(--accent);' : '' }}">VI</a> |
                <a href="{{ route('lang.switch', 'en') }}" style="padding: 0.2rem 0.5rem; {{ session('locale') != 'vi' ? 'font-weight: bold; color: var(--accent);' : '' }}">EN</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <main class="page-content">
        @if(session('success'))
            <div class="container" style="padding-top:1rem">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <span class="footer-logo">&lt;{{ __('Thiện') }} /&gt;</span>
        <nav class="footer-links">
            <a href="{{ route('home') }}">{{ __('Home') }}</a>
            <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
            <a href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
            <a href="{{ route('about') }}">{{ __('About') }}</a>
        </nav>
        <div class="footer-socials">
            <a href="https://github.com/hoThanhThien" aria-label="GitHub"><i class="fab fa-github"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="mailto:hothanhthien119@gmail.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
        </div>
        <p class="footer-copy">&copy; {{ date('Y') }} {{ __('Hồ Thành Thiện') }}. {{ __('Built with Laravel & ♥') }}</p>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Mobile menu
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });

        // Back to top
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    @yield('scripts')
</body>
</html>
