<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Student Portal — DEVA Education</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Inter',sans-serif;overflow:hidden}

body{
    background:#060f1e;
    color:#fff;
    display:flex;
    align-items:stretch;
}

/* ── LEFT PANEL ── */
.lp-left{
    width:55%;
    position:relative;
    background:linear-gradient(160deg,#060f1e 0%,#0a1e3d 45%,#060f1e 100%);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:40px 48px;
    overflow:hidden;
}
.lp-left::before{
    content:'';
    position:absolute;
    inset:0;
    background:
        radial-gradient(circle at 15% 20%,rgba(26,107,255,.18) 0%,transparent 50%),
        radial-gradient(circle at 85% 75%,rgba(26,107,255,.12) 0%,transparent 45%);
    pointer-events:none;
}
.lp-dots{
    position:absolute;
    inset:0;
    background-image:radial-gradient(circle,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size:28px 28px;
    pointer-events:none;
}
/* floating decorative circles */
.lp-circle{position:absolute;border-radius:50%;border:1px solid rgba(26,107,255,.15);pointer-events:none}
.lp-c1{width:380px;height:380px;right:-80px;top:-80px;}
.lp-c2{width:240px;height:240px;right:30px;top:30px;border-color:rgba(26,107,255,.1);}
.lp-c3{width:180px;height:180px;left:-50px;bottom:120px;border-color:rgba(26,107,255,.08);}

.lp-brand{position:relative;z-index:2;display:flex;align-items:center;gap:14px}
.lp-brand img{height:44px;width:auto;object-fit:contain;border-radius:8px}
.lp-brand-name{font-size:16px;font-weight:800;background:linear-gradient(135deg,#fff,#93c5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.lp-brand-sub{font-size:11px;color:rgba(255,255,255,.45);font-weight:400;-webkit-text-fill-color:rgba(255,255,255,.45);display:block;margin-top:-1px}

.lp-hero{position:relative;z-index:2;margin-top:auto;padding-bottom:12px}
.lp-hero-tag{display:inline-flex;align-items:center;gap:6px;background:rgba(26,107,255,.2);border:1px solid rgba(26,107,255,.35);border-radius:20px;padding:5px 14px;font-size:12px;color:#93c5fd;font-weight:500;margin-bottom:24px}
.lp-hero-tag span{width:6px;height:6px;border-radius:50%;background:#60a5fa;display:inline-block;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.lp-hero h2{font-size:2.6rem;font-weight:800;line-height:1.15;margin-bottom:16px;background:linear-gradient(135deg,#fff 30%,#93c5fd 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.lp-hero p{font-size:14px;color:rgba(255,255,255,.5);line-height:1.7;max-width:420px;margin-bottom:32px}

.lp-stats{display:flex;gap:14px}
.lp-stat{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:14px 20px;flex:1}
.lp-stat-val{font-size:22px;font-weight:700;color:#fff;line-height:1}
.lp-stat-lbl{font-size:11px;color:rgba(255,255,255,.4);margin-top:3px}

/* ── RIGHT PANEL ── */
.lp-right{
    width:45%;
    background:#f0f4f8;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 32px;
    position:relative;
}
.lp-right::before{
    content:'';
    position:absolute;
    inset:0;
    background:radial-gradient(circle at 80% 15%,rgba(26,107,255,.06) 0%,transparent 50%);
    pointer-events:none;
}
.lp-form-box{width:100%;max-width:400px;position:relative;z-index:1}

.lp-form-logo{display:flex;align-items:center;justify-content:center;margin-bottom:32px}
.lp-form-logo img{height:52px;width:auto;object-fit:contain;border-radius:10px;box-shadow:0 4px 20px rgba(26,107,255,.25)}

.lp-form-hd{margin-bottom:28px}
.lp-form-hd h1{font-size:1.75rem;font-weight:800;color:#0f172a;margin-bottom:6px}
.lp-form-hd h1 span{background:linear-gradient(135deg,#1a6bff,#0a3d99);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.lp-form-hd p{font-size:13px;color:#64748b}

.lp-field{margin-bottom:18px}
.lp-label{display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:7px;text-transform:uppercase;letter-spacing:.04em}
.lp-input-wrap{position:relative}
.lp-input-ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;pointer-events:none}
.lp-input{
    width:100%;padding:11px 13px 11px 38px;
    border:1.5px solid #e2e8f0;
    border-radius:11px;
    font-size:13px;
    font-family:'Inter',sans-serif;
    color:#0f172a;
    background:#fff;
    outline:none;
    transition:border-color .2s,box-shadow .2s;
}
.lp-input:focus{border-color:#1a6bff;box-shadow:0 0 0 3px rgba(26,107,255,.12)}
.lp-input::placeholder{color:#94a3b8}
.lp-error{color:#dc2626;font-size:12px;margin-top:5px;display:flex;align-items:center;gap:4px}

.lp-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.lp-check-wrap{display:flex;align-items:center;gap:7px;cursor:pointer}
.lp-check{width:15px;height:15px;border-radius:4px;border:1.5px solid #cbd5e1;accent-color:#1a6bff;cursor:pointer}
.lp-check-lbl{font-size:13px;color:#64748b;cursor:pointer}

.lp-btn{
    width:100%;padding:12px;
    background:linear-gradient(135deg,#1a6bff,#0a3d99);
    color:#fff;border:none;
    border-radius:11px;
    font-size:14px;font-weight:600;
    cursor:pointer;font-family:'Inter',sans-serif;
    box-shadow:0 4px 18px rgba(26,107,255,.35);
    transition:all .2s;
    display:flex;align-items:center;justify-content:center;gap:8px;
    margin-bottom:20px;
}
.lp-btn:hover{opacity:.92;transform:translateY(-1px);box-shadow:0 6px 24px rgba(26,107,255,.45)}
.lp-btn:active{transform:translateY(0)}

.lp-back{
    display:flex;align-items:center;justify-content:center;gap:6px;
    font-size:12px;color:#64748b;text-decoration:none;
    border:1.5px solid #e2e8f0;border-radius:9px;padding:9px 18px;
    background:#fff;transition:all .2s;font-weight:500;
}
.lp-back:hover{border-color:#1a6bff;color:#1a6bff;background:#f0f5ff}

.lp-alert-success{
    background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);
    color:#059669;padding:11px 14px;border-radius:10px;
    margin-bottom:18px;font-size:13px;
    display:flex;align-items:center;gap:8px;
}

@media(max-width:900px){
    .lp-left{width:42%;padding:32px 28px}
    .lp-right{width:58%;padding:32px 24px}
    .lp-hero h2{font-size:2rem}
    .lp-stats{flex-direction:column;gap:8px}
}
@media(max-width:680px){
    body{overflow-y:auto;flex-direction:column}
    .lp-left{width:100%;min-height:42vh;padding:28px 24px}
    .lp-right{width:100%;padding:28px 20px}
    .lp-hero h2{font-size:1.7rem}
}
</style>
</head>
<body>

{{-- LEFT --}}
<div class="lp-left">
    <div class="lp-dots"></div>
    <div class="lp-circle lp-c1"></div>
    <div class="lp-circle lp-c2"></div>
    <div class="lp-circle lp-c3"></div>

    <div class="lp-brand">
        <img src="{{ asset('Apx.jpeg') }}" alt="APX">
        <div>
            <div class="lp-brand-name">DEVA Education</div>
            <span class="lp-brand-sub">Student Portal</span>
        </div>
    </div>

    <div class="lp-hero">
        <div class="lp-hero-tag">
            <span></span> Student Access Portal
        </div>
        <h2>Track your<br>academic journey</h2>
        <p>Access your applications, upload documents, and stay in touch with your advisor — all from one place.</p>
        <div class="lp-stats">
            <div class="lp-stat">
                <div class="lp-stat-val">100+</div>
                <div class="lp-stat-lbl">Universities</div>
            </div>
            <div class="lp-stat">
                <div class="lp-stat-val">24/7</div>
                <div class="lp-stat-lbl">Support</div>
            </div>
            <div class="lp-stat">
                <div class="lp-stat-val">Fast</div>
                <div class="lp-stat-lbl">Processing</div>
            </div>
        </div>
    </div>
</div>

{{-- RIGHT --}}
<div class="lp-right">
    <div class="lp-form-box">
        <div class="lp-form-logo">
            <img src="{{ asset('Apx.jpeg') }}" alt="APX Logo">
        </div>

        <div class="lp-form-hd">
            <h1>Welcome back, <span>Student</span></h1>
            <p>Sign in to access your student portal</p>
        </div>

        @if(session('status'))
        <div class="lp-alert-success"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('student.login') }}">
            @csrf
            <div class="lp-field">
                <label class="lp-label" for="email">Email Address</label>
                <div class="lp-input-wrap">
                    <i class="fas fa-envelope lp-input-ico"></i>
                    <input id="email" class="lp-input" type="email" name="email"
                        value="{{ old('email') }}" required autofocus autocomplete="username"
                        placeholder="you@example.com">
                </div>
                @error('email')<div class="lp-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="lp-field">
                <label class="lp-label" for="password">Password</label>
                <div class="lp-input-wrap">
                    <i class="fas fa-lock lp-input-ico"></i>
                    <input id="password" class="lp-input" type="password" name="password"
                        required autocomplete="current-password" placeholder="••••••••">
                </div>
                @error('password')<div class="lp-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="lp-row">
                <label class="lp-check-wrap">
                    <input id="remember_me" type="checkbox" class="lp-check" name="remember">
                    <span class="lp-check-lbl">Remember me</span>
                </label>
            </div>

            <button type="submit" class="lp-btn">
                <i class="fas fa-sign-in-alt"></i> Sign in to Portal
            </button>
        </form>

        <a href="{{ url('/') }}" class="lp-back">
            <i class="fas fa-arrow-left"></i> Back to Main Login
        </a>
    </div>
</div>

</body>
</html>
