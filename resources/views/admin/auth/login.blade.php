<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — CV Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg: #f8fafc; --bg-card: #ffffff; --border: #e2e8f0;
            --text: #0f172a; --text-muted: #64748b;
            --accent: #6366f1; --accent-light: #8b5cf6;
            --gradient: linear-gradient(135deg, #6366f1, #06b6d4);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background-image: radial-gradient(ellipse 80% 80% at 50% -20%, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
        }
        .login-wrap { width: 100%; max-width: 420px; padding: 1.5rem; }
        .login-logo {
            text-align: center; margin-bottom: 2rem;
            font-size: 2rem; font-weight: 900;
            background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .login-logo small { display: block; font-size: 0.9rem; -webkit-text-fill-color: var(--text-muted); font-weight: 400; margin-top: 0.25rem; }
        .login-card {
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            border: 1px solid var(--border); border-radius: 20px; padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 20px 40px -10px rgba(0,0,0,0.1);
        }
        .login-card h2 { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.25rem; }
        .login-card p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.75rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.83rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem; }
        .form-control {
            width: 100%; padding: 0.75rem 1rem; border-radius: 10px;
            background: #ffffff; border: 1px solid var(--border);
            color: var(--text); font-family: inherit; font-size: 0.9rem; transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--accent); }
        .form-control.is-invalid { border-color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem; }
        .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-check input { accent-color: var(--accent); width: 16px; height: 16px; }
        .form-check label { font-size: 0.88rem; color: var(--text-muted); cursor: pointer; }
        .btn-submit {
            width: 100%; padding: 0.8rem;
            background: var(--gradient); color: white; border: none;
            border-radius: 10px; font-size: 0.95rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s; margin-top: 1.25rem;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 30px rgba(124,58,237,0.4); }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.88rem; margin-bottom: 1.25rem; }
        .back-link { text-align: center; margin-top: 1.25rem; }
        .back-link a { color: var(--text-muted); font-size: 0.85rem; text-decoration: none; }
        .back-link a:hover { color: var(--accent-light); }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-logo">
        &lt;CV Admin /&gt;
        <small>Portfolio Management System</small>
    </div>

    <div class="login-card">
        <h2>Welcome back</h2>
        <p>Sign in to manage your portfolio</p>

        @if($errors->has('email'))
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="••••••••" required>
            </div>
            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
    </div>

    <div class="back-link">
        <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Back to portfolio</a>
    </div>
</div>
</body>
</html>
