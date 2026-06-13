{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABX SITE — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Force light mode — never inherit OS dark scheme on this page */
        html { color-scheme: light only; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #1a6bff;
            --primary-dark: #1558d6;
            --navy:         #0a1628;
            --navy-mid:     #0d2550;
            --navy-accent:  #0a3d99;
            --text:         #1e293b;
            --muted:        #64748b;
            --border:       #e2e8f0;
            --bg:           #f8faff;
            --white:        #ffffff;
            --danger:       #ef4444;
            --success:      #10b981;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow: hidden;
        }

        /* ── Layout ── */
        .page { display: flex; height: 100vh; }

        /* ── Left panel ── */
        .panel-left {
            flex: 0 0 52%;
            background: linear-gradient(160deg, var(--navy) 0%, var(--navy-mid) 55%, #0d3b8a 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 3rem;
            overflow: hidden;
        }

        /* decorative circles */
        .panel-left::before {
            content: '';
            position: absolute;
            border-radius: 50%;
            width: 520px; height: 520px;
            background: var(--primary);
            bottom: -140px; right: -140px;
            opacity: 0.07;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            width: 280px; height: 280px;
            background: #4d8eff;
            top: -80px; left: -60px;
            opacity: 0.07;
        }

        /* dot grid */
        .dots {
            position: absolute;
            z-index: 0;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 320px; height: 320px;
            background-image: radial-gradient(circle, rgba(255,255,255,0.18) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: 0.45;
        }

        .panel-brand {
            position: relative;
            z-index: 1;
        }

        .panel-brand img {
            height: 56px;
            width: auto;
            border-radius: 10px;
            display: block;
        }

        .panel-tagline {
            position: relative;
            z-index: 1;
            margin-top: auto;
            color: #fff;
        }

        .panel-tagline h2 {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .panel-tagline p {
            font-size: 0.975rem;
            color: rgba(255,255,255,0.72);
            max-width: 400px;
            line-height: 1.7;
        }

        .features {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 2rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.18);
            color: rgba(255,255,255,0.88);
            font-size: 0.78rem;
            font-weight: 500;
            padding: 0.32rem 0.8rem;
            border-radius: 100px;
        }

        /* ── Right panel ── */
        .panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: var(--white);
            overflow-y: auto;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
        }

        /* ── Role tabs ── */
        .role-tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 12px;
            padding: 4px;
            gap: 4px;
            margin-bottom: 2rem;
        }

        .role-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.55rem 1rem;
            border: none;
            border-radius: 9px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--muted);
            background: transparent;
            cursor: pointer;
            transition: all 0.18s;
        }

        .role-tab.active {
            background: var(--white);
            color: var(--primary);
            font-weight: 600;
            box-shadow: 0 1px 6px rgba(0,0,0,0.1), 0 0 0 1px rgba(26,107,255,0.12);
        }

        .role-tab:hover:not(.active) {
            color: var(--text);
            background: rgba(255,255,255,0.7);
        }

        /* ── Heading ── */
        .form-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.3rem;
            letter-spacing: -0.3px;
        }

        .form-subtitle {
            font-size: 0.875rem;
            color: var(--muted);
            margin-bottom: 1.75rem;
        }

        /* ── Alert ── */
        .alert-ok {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        .alert-close { cursor: pointer; opacity: 0.55; font-size: 1.1rem; }
        .alert-close:hover { opacity: 1; }

        /* ── Fields ── */
        .field { margin-bottom: 1.2rem; }

        .field label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        /* Hard-override so OS dark mode never darkens these inputs */
        .field input[type="email"],
        .field input[type="password"] {
            display: block;
            width: 100%;
            padding: 0.7rem 0.95rem;
            font-family: inherit;
            font-size: 0.9375rem;
            color: #1e293b !important;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            outline: none;
            -webkit-text-fill-color: #1e293b !important;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
            -webkit-appearance: none;
        }

        .field input[type="email"]:focus,
        .field input[type="password"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,107,255,0.12);
        }

        .field input::placeholder { color: #a0aec0 !important; opacity: 1; }

        .field .err {
            font-size: 0.8rem;
            color: var(--danger);
            margin-top: 0.3rem;
        }

        .field input.has-error { border-color: var(--danger) !important; }

        /* ── Remember ── */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--primary);
            cursor: pointer;
            background: #fff !important;
        }

        .remember-row label {
            font-size: 0.8125rem;
            color: var(--muted);
            cursor: pointer;
        }

        /* ── Submit ── */
        .btn-login {
            display: block;
            width: 100%;
            padding: 0.8rem 1rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--navy-accent) 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(26,107,255,0.32);
            transition: transform 0.15s, box-shadow 0.15s;
            margin-bottom: 1.5rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #082e77 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,107,255,0.4);
        }

        .btn-login:active { transform: translateY(0); }

        /* ── Alt link ── */
        .alt-wrap { text-align: center; }

        .alt-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--primary);
            text-decoration: none;
            padding: 0.45rem 1.1rem;
            border: 1.5px solid var(--primary);
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
        }

        .alt-link:hover { background: var(--primary); color: #fff; }

        /* ── Responsive ── */
        @media (max-width: 820px) {
            html, body { overflow: auto; }
            .page { flex-direction: column; height: auto; min-height: 100vh; }
            .panel-left { flex: none; min-height: 260px; padding: 2rem; }
            .panel-left::before { width: 300px; height: 300px; bottom: -80px; right: -80px; }
            .panel-tagline h2 { font-size: 1.6rem; }
            .dots { display: none; }
            .panel-right { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Left panel --}}
    <div class="panel-left">
        <div class="dots"></div>
        <div class="panel-brand">
            <img src="{{ asset('Apx.jpeg') }}" alt="ABX SITE">
        </div>
        <div class="panel-tagline">
            <h2>Welcome back to<br>ABX SITE</h2>
            <p>Manage courses, track student progress, and handle registrations — all from one powerful dashboard.</p>
            <div class="features">
                <span class="pill">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/></svg>
                    Student Management
                </span>
                <span class="pill">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/></svg>
                    Program Tracking
                </span>
                <span class="pill">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/></svg>
                    Secure &amp; Fast
                </span>
            </div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="panel-right">
        <div class="form-box">

            <div class="role-tabs">
                <button type="button" class="role-tab active" id="btnAdmin" onclick="switchRole('admin')">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4 8c0 1-1 1-1 1H5s-1 0-1-1 1-4 4-4 4 3 4 4z"/></svg>
                    Admin
                </button>
                <button type="button" class="role-tab" id="btnStudent" onclick="switchRole('student')">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5z"/></svg>
                    Student
                </button>
            </div>

            <div class="form-title" id="formTitle">Sign in as Admin</div>
            <div class="form-subtitle" id="formSubtitle">Enter your credentials to access the dashboard</div>

            @if (session('status'))
            <div class="alert-ok">
                <span>{{ session('status') }}</span>
                <span class="alert-close" onclick="this.parentElement.remove()">&times;</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           autocomplete="username"
                           class="{{ $errors->has('email') ? 'has-error' : '' }}"
                           required autofocus>
                    @error('email')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           class="{{ $errors->has('password') ? 'has-error' : '' }}"
                           required>
                    @error('password')<div class="err">{{ $message }}</div>@enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">Keep me signed in</label>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">Sign in as Admin</button>
            </form>

            <div class="alt-wrap">
                <a href="{{ route('student.login.view') }}" class="alt-link" id="altLink">
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5z"/></svg>
                    Sign in as Student instead
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const ROUTES = { admin: "{{ route('login') }}", student: "{{ route('student.login') }}" };

    function switchRole(role) {
        const form    = document.getElementById('loginForm');
        const title   = document.getElementById('formTitle');
        const sub     = document.getElementById('formSubtitle');
        const btn     = document.getElementById('submitBtn');
        const altLink = document.getElementById('altLink');
        const tabA    = document.getElementById('btnAdmin');
        const tabS    = document.getElementById('btnStudent');

        form.action = ROUTES[role];

        if (role === 'admin') {
            tabA.classList.add('active');    tabS.classList.remove('active');
            title.textContent = 'Sign in as Admin';
            sub.textContent   = 'Enter your credentials to access the dashboard';
            btn.textContent   = 'Sign in as Admin';
            altLink.href      = ROUTES.student;
            altLink.innerHTML = `<svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5z"/></svg> Sign in as Student instead`;
        } else {
            tabS.classList.add('active');    tabA.classList.remove('active');
            title.textContent = 'Sign in as Student';
            sub.textContent   = 'Enter your student credentials to access your portal';
            btn.textContent   = 'Sign in as Student';
            altLink.href      = ROUTES.admin;
            altLink.innerHTML = `<svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4 8c0 1-1 1-1 1H5s-1 0-1-1 1-4 4-4 4 3 4 4z"/></svg> Sign in as Admin instead`;
        }
    }
</script>
</body>
</html>