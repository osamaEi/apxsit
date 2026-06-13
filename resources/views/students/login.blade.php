{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABX SITE - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        :root {
            --primary: #FF4A60;
            --primary-hover: #E0394D;
            --secondary: #1a6bff;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
            --primary: #1a6bff;
            --primary-hover: #1558d6;
            --light: #f4f7ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            height: 100vh;
            display: flex;
            overflow: hidden;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            height: 100%;
        }
        
        .login-image {
            width: 55%;
            background: linear-gradient(145deg, #0a1628 0%, #0d2550 50%, #0a3d99 100%);
            
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }
        
        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 100%);
        }
        
        .image-content {
            position: relative;
            color: white;
            max-width: 500px;
        }
        
        .image-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .image-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .brand {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .brand-logo {
            width: 180px;
            height: auto;
            margin-bottom: 0.5rem;
        }
        
        .login-form {
            width: 45%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background-color: #f4f7ff;
        }
        
        .form-container {
            max-width: 420px;
            margin: 0 auto;
            width: 100%;
        }
        
        .form-header {
            margin-bottom: 2.5rem;
        }
        
        .form-header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--dark);
            background: linear-gradient(to right, #1a6bff, #1a6bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
        
        .form-header p {
            color: var(--gray);
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #1a6bff;
            box-shadow: 0 0 0 3px rgba(255, 74, 96, 0.15), 0 0 0 1px rgba(26,107,255,0.2);
        }
        
        .input-error {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox {
            width: 1rem;
            height: 1rem;
            cursor: pointer;
            border-radius: 0.25rem;
            border: 1px solid var(--light-gray);
            accent-color: #1a6bff;
        }
        
        .remember-text {
            font-size: 0.875rem;
            color: var(--gray);
        }
        
        .forgot-link {
            font-size: 0.875rem;
            color: #1a6bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        .login-button {
            width: 100%;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #1a6bff 0%, #1a6bff 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(26,107,255,0.2);
        }
        
        .login-button:hover {
            background: linear-gradient(135deg, #1a6bff 20%, #1a6bff 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(26,107,255,0.3);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--light-gray);
        }
        
        .divider::before {
            margin-right: 1rem;
        }
        
        .divider::after {
            margin-left: 1rem;
        }
        
        .social-login {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .social-button {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border: 1px solid var(--light-gray);
            border-radius: 0.5rem;
            background-color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .social-button:hover {
            background-color: var(--light);
        }
        
        .social-icon {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        .register-link-container {
            text-align: center;
            font-size: 0.875rem;
            color: var(--gray);
        }
        
        .register-link {
            color: #1a6bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .register-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        .session-status {
            background-color: var(--success);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .status-close {
            cursor: pointer;
            font-size: 1.25rem;
            font-weight: bold;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .small-logo {
            width: 120px;
            height: auto;
            margin: 0 auto;
        }
        
        @media (max-width: 1024px) {
            .login-image {
                width: 45%;
                padding: 2rem;
            }
            
            .login-form {
                width: 55%;
                padding: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            body {
                overflow-y: auto;
            }
            
            .login-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            .login-image {
                width: 100%;
                height: 35vh;
                min-height: 300px;
                padding: 1.5rem;
            }
            
            .login-form {
                width: 100%;
                padding: 2rem 1.5rem;
            }
            
            .form-container {
                max-width: 100%;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body:not(.light-mode) {
                --light: #0f172a;
                --dark: #f8fafc;
                --light-gray: #334155;
                background-color: var(--light);
                color: var(--dark);
            }
            
            body:not(.light-mode) .input-field {
                background-color: #1e293b;
                color: var(--dark);
            }
            
            body:not(.light-mode) .social-button {
                background-color: #1e293b;
                color: var(--dark);
            }
            
            body:not(.light-mode) .social-button:hover {
                background-color: #334155;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <div class="brand">
                <img src="{{ asset('Apx.jpeg') }}" alt="ABX SITE" class="brand-logo">
            </div>
            <div class="image-content">
                <h2>Welcome to ABX SITE</h2>
                <p>Access your dashboard to manage courses, track student progress, monitor registrations, and accomplish your daily tasks with our streamlined platform.</p>
            </div>
        </div>
        
        <div class="login-form">
            <div class="form-container">
                <div class="logo-container">
                    <img src="{{ asset('Apx.jpeg') }}" alt="ABX SITE" style="height:70px; width:auto; border-radius:8px;">
                </div>
                
                <div class="form-header">
                    <h1>Student Sign in</h1>
                    <p>Enter your credentials to access your student portal</p>
                </div>
                
                @if (session('status'))
                    <div class="session-status">
                        {{ session('status') }}
                        <span class="status-close">&times;</span>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('student.login') }}">
                    @csrf
                    
                    <div class="input-group">
                        <label for="email" class="input-label">Email address</label>
                        <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                        @error('email')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <label for="password" class="input-label">Password</label>
                        <input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" />
                        @error('password')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input id="remember_me" type="checkbox" class="checkbox" name="remember">
                            <label for="remember_me" class="remember-text">Remember me</label>
                        </div>
                        
                        {{-- @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif --}}
                    </div>
                    
                    <button type="submit" class="login-button">
                        Sign in as Student
                    </button>
                </form>

                <div style="text-align:center; margin-top:1.25rem;">
                    <a href="{{ url('/') }}" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.875rem; color:#1a6bff; text-decoration:none; font-weight:500; padding:0.5rem 1.25rem; border:1.5px solid #1a6bff; border-radius:8px; transition:all 0.2s;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/></svg>
                        Back to Main Login
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Close status message
        document.addEventListener('DOMContentLoaded', function() {
            const statusClose = document.querySelector('.status-close');
            if (statusClose) {
                statusClose.addEventListener('click', function() {
                    this.parentElement.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>