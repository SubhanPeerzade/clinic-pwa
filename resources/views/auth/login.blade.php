<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            width: 380px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            padding: 25px;
            background: #fff;
        }
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            width: 380px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            padding: 25px;
            background: #fff;
        }
        .login-title {
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        @media (max-width: 480px) {
            .login-card {
                width: calc(100% - 30px);
                margin: 0 15px;
                padding: 16px;
            }
            
            .btn.w-100 {
                width: 100%;
                box-sizing: border-box;
                padding: 8px 10px !important;
                font-size: 0.9rem;
            }
            
            .form-control {
                padding: 8px 12px;
                font-size: 16px;
                min-height: 40px;
            }
            
            .mb-3 {
                margin-bottom: 0.75rem !important;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3 class="text-center login-title">Login</h3>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FIXED FORM ACTION -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email"
                       class="form-control"
                       name="email"
                       value="{{ old('email') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password"
                       class="form-control"
                       name="password"
                       required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

            <p class="text-center mt-3">
                <a href="{{ route('register') }}">Create an account</a>
            </p>

        </form>
    </div>

</body>
</html>
