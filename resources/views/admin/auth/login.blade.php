<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f7f8fa;
            --bg-card: #ffffff;
            --bg-subtle: #eef1f5;
            --border: #dce1e9;
            --text: #12161f;
            --text-secondary: #434d5e;
            --text-muted: #687385;
            --accent: #1e40af;
            --accent-hover: #1d4ed8;
            --danger: #dc2626;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-card: 0 12px 32px rgba(18, 22, 31, 0.06), 0 2px 6px rgba(18, 22, 31, 0.04);
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            background-image: radial-gradient(rgba(18, 22, 31, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            line-height: 1.6;
        }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        .login-wrap {
            width: 100%;
            max-width: 420px;
            animation: cardEnter 0.38s var(--ease-out) both;
        }

        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 2.25rem 2rem;
            box-shadow: var(--shadow-card);
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            margin-bottom: 1.75rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--text);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .brand-text {
            text-align: left;
        }

        .brand-logo {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .brand-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        h1 {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.3rem;
            color: var(--text);
        }

        .sub {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        label {
            display: block;
            font-size: 0.84rem;
            font-weight: 600;
            margin-bottom: 0.45rem;
            color: var(--text);
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.18s ease;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.7rem 0.95rem 0.7rem 2.5rem;
            border-radius: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: inherit;
            font-size: 0.92rem;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.12);
        }

        .input-wrap:focus-within i {
            color: var(--accent);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.35rem;
        }

        .form-check input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .form-check label {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 0.78rem 1rem;
            border-radius: 8px;
            background: var(--text);
            border: 1px solid var(--text);
            color: #ffffff;
            font-family: inherit;
            font-size: 0.94rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.15s ease, box-shadow 0.18s ease;
        }

        .btn-submit:hover:not(:disabled) {
            background: var(--accent);
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.22);
        }

        .btn-submit:active:not(:disabled) {
            transform: translateY(1px);
        }

        .btn-submit:disabled {
            opacity: 0.72;
            cursor: wait;
        }

        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 0.75rem 0.95rem;
            border-radius: 8px;
            font-size: 0.84rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            line-height: 1.45;
        }

        .alert-danger i {
            margin-top: 0.18rem;
            flex-shrink: 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            text-align: center;
            margin-top: 1.35rem;
            font-size: 0.84rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.18s ease;
        }

        .back-link:hover {
            color: var(--accent);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1.75rem 1.35rem;
                border-radius: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-wrap {
                animation: none !important;
            }
            .btn-submit, input, .back-link {
                transition: none !important;
            }
        }
    </style>
</head>
<body>
    <main class="login-wrap">
        <div class="login-card">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true">
                    <i class="fas fa-terminal"></i>
                </div>
                <div class="brand-text">
                    <div class="brand-logo">&lt;Admin /&gt;</div>
                    <div class="brand-sub">Portfolio Content Manager</div>
                </div>
            </div>

            <h1>Đăng nhập quản trị</h1>
            <p class="sub">Truy cập hệ thống quản lý dự án, bài viết &amp; chứng chỉ</p>

            @if($errors->any())
                <div class="alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" id="adminLoginForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email quản trị</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@portfolio.dev" required autofocus autocomplete="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <button type="submit" class="btn-submit" id="adminLoginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Đăng nhập</span>
                </button>
            </form>

            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại trang chủ</span>
            </a>
        </div>
    </main>

    <script>
        document.getElementById('adminLoginForm')?.addEventListener('submit', function () {
            const btn = document.getElementById('adminLoginBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Đang xác thực...</span>';
            }
        });
    </script>
</body>
</html>
