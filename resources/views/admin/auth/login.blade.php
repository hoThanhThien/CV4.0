<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Pearl Portfolio</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">

    <script>
        if (localStorage.getItem('thien_theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <script src="{{ asset('js/lucide-admin.js') }}?v={{ @filemtime(public_path('js/lucide-admin.js')) ?: 1 }}" defer></script>

    @php
        $fontsCssPath = resource_path('css/fonts.css');
        $inlineFontsCss = is_file($fontsCssPath) ? file_get_contents($fontsCssPath) : '';
    @endphp

    <style>
        {!! $inlineFontsCss !!}

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f8fafc;
            --bg-grid: rgba(15, 23, 42, 0.045);
            --bg-card: #ffffff;
            --bg-subtle: #f1f5f9;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --accent: #0369a1;
            --accent-hover: #0284c7;
            --danger: #dc2626;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-card: 0 18px 42px rgba(15, 23, 42, 0.08), 0 2px 8px rgba(15, 23, 42, 0.04);
            color-scheme: light;
        }

        html.dark {
            --bg: #020617;
            --bg-grid: rgba(148, 163, 184, 0.075);
            --bg-card: #0f172a;
            --bg-subtle: #0b1120;
            --border: #1e293b;
            --text: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --accent-hover: #7dd3fc;
            --danger: #f87171;
            --shadow-card: 0 22px 48px rgba(2, 6, 23, 0.72);
            color-scheme: dark;
        }

        body {
            font-family: 'Inter', 'Inter Fallback', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            background-image:
                radial-gradient(circle at 15% 15%, rgba(56, 189, 248, 0.14), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(99, 102, 241, 0.12), transparent 40%),
                radial-gradient(var(--bg-grid) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 24px 24px;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            line-height: 1.6;
        }

        :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

        .login-wrap { width: 100%; max-width: 420px; animation: cardEnter 0.38s var(--ease-out) both; }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.15rem 2rem;
            box-shadow: var(--shadow-card);
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.65rem;
            padding-bottom: 1.35rem;
            border-bottom: 1px solid var(--border);
        }

        .brand-left { display: flex; align-items: center; gap: 0.7rem; }
        .brand-mark {
            width: 40px; height: 40px; border-radius: 11px; overflow: hidden;
            background: linear-gradient(135deg, #0ea5e9, #4f46e5);
            display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .brand-mark img { width: 40px; height: 40px; object-fit: cover; display: block; }
        .brand-logo {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-size: 1.08rem; font-weight: 700; color: var(--text); letter-spacing: -0.02em; line-height: 1.25;
        }
        .brand-sub { font-family: 'JetBrains Mono', monospace; font-size: 0.74rem; color: var(--text-muted); font-weight: 500; }

        .theme-mini-btn {
            width: 34px; height: 34px; border-radius: 9px;
            background: var(--bg-subtle); border: 1px solid var(--border); color: var(--text-secondary);
            display: inline-flex; align-items: center; justify-content: center; cursor: pointer;
        }
        .theme-mini-btn:hover { border-color: var(--accent); color: var(--accent); }
        .theme-mini-btn svg.lucide { width: 16px; height: 16px; }
        html:not(.dark) #loginThemeLight { display: none; }
        html.dark #loginThemeDark { display: none; }

        h1 { font-family: 'Space Grotesk', 'Inter', sans-serif; font-size: 1.32rem; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 0.3rem; color: var(--text); }
        .sub { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.45rem; }
        .form-group { margin-bottom: 1.15rem; }
        label { display: block; font-size: 0.84rem; font-weight: 600; margin-bottom: 0.45rem; color: var(--text); }

        .input-wrap { position: relative; }
        .input-wrap svg.lucide {
            position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: var(--text-muted); pointer-events: none; transition: color 0.18s ease;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%; padding: 0.7rem 0.95rem 0.7rem 2.5rem; border-radius: 10px;
            background: var(--bg-subtle); border: 1px solid var(--border);
            color: var(--text); font-family: inherit; font-size: 0.92rem;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none; background: var(--bg-card); border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        .input-wrap:focus-within svg.lucide { color: var(--accent); }

        .form-check { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.35rem; }
        .form-check input { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
        .form-check label { margin: 0; font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); cursor: pointer; }

        .btn-submit {
            width: 100%; padding: 0.78rem 1rem; border-radius: 10px;
            background: var(--text); border: 1px solid var(--text); color: var(--bg-card);
            font-family: inherit; font-size: 0.94rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.15s ease;
        }
        .btn-submit svg.lucide { width: 17px; height: 17px; }
        .btn-submit:hover:not(:disabled) { background: var(--accent); border-color: var(--accent); color: #ffffff; }
        html.dark .btn-submit { background: #0284c7; border-color: #0284c7; color: #ffffff; }
        html.dark .btn-submit:hover:not(:disabled) { background: #0ea5e9; border-color: #0ea5e9; }
        .btn-submit:active:not(:disabled) { transform: translateY(1px); }
        .btn-submit:disabled { opacity: 0.72; cursor: wait; }

        .alert-danger {
            background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: var(--danger);
            padding: 0.75rem 0.95rem; border-radius: 10px; font-size: 0.84rem;
            margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: 0.5rem; line-height: 1.45;
        }
        .alert-danger svg.lucide { width: 16px; height: 16px; margin-top: 0.15rem; flex-shrink: 0; }

        .back-link {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;
            width: 100%; text-align: center; margin-top: 1.35rem;
            font-size: 0.84rem; font-weight: 500; color: var(--text-muted); text-decoration: none;
            transition: color 0.18s ease;
        }
        .back-link svg.lucide { width: 15px; height: 15px; }
        .back-link:hover { color: var(--accent); }

        @media (max-width: 480px) {
            .login-card { padding: 1.75rem 1.35rem; border-radius: 14px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .login-wrap { animation: none !important; }
            .btn-submit, input, .back-link { transition: none !important; }
        }
    </style>
</head>
<body>
    <main class="login-wrap">
        <div class="login-card">
            <div class="brand">
                <div class="brand-left">
                    <div class="brand-mark" aria-hidden="true">
                        <img src="{{ asset('images/avatar-sm.webp') }}" width="40" height="40" alt="CV Studio Logo">
                    </div>
                    <div>
                        <div class="brand-logo">&lt;Admin /&gt;</div>
                        <div class="brand-sub">Pearl White Studio</div>
                    </div>
                </div>
                <button type="button" class="theme-mini-btn" id="loginThemeToggle" aria-label="Toggle dark mode">
                    <i data-lucide="sun" id="loginThemeLight"></i>
                    <i data-lucide="moon" id="loginThemeDark"></i>
                </button>
            </div>

            <h1>Đăng nhập quản trị</h1>
            <p class="sub">Truy cập hệ thống quản lý dự án, bài viết AI &amp; chứng chỉ</p>

            @if($errors->any())
                <div class="alert-danger" role="alert">
                    <i data-lucide="alert-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" id="adminLoginForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email quản trị</label>
                    <div class="input-wrap">
                        <i data-lucide="mail" aria-hidden="true"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@portfolio.dev" required autofocus autocomplete="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrap">
                        <i data-lucide="lock" aria-hidden="true"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <button type="submit" class="btn-submit" id="adminLoginBtn">
                    <i data-lucide="log-in"></i>
                    <span>Đăng nhập</span>
                </button>
            </form>

            <a href="{{ route('home') }}" class="back-link">
                <i data-lucide="arrow-left"></i>
                <span>Quay lại trang chủ</span>
            </a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        });

        document.getElementById('loginThemeToggle')?.addEventListener('click', function () {
            const isDark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('thien_theme', isDark ? 'dark' : 'light');
        });

        document.getElementById('adminLoginForm')?.addEventListener('submit', function () {
            const btn = document.getElementById('adminLoginBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i data-lucide="clock"></i><span>Đang xác thực...</span>';
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons({ root: btn });
                }
            }
        });
    </script>
</body>
</html>
