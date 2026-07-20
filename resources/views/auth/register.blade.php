<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-card {
            width: 420px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            padding: 28px;
            background: #fff;
        }
        .register-title {
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        @media (max-width: 480px) {
            .register-card {
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

    <div class="register-card">
        <h3 class="text-center register-title">Create Account</h3>

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

        <form method="POST" action="{{ url('/register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text"
                       class="form-control"
                       name="name"
                       value="{{ old('name') }}"
                       required>
            </div>

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

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password"
                       class="form-control"
                       name="password_confirmation"
                       required>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>

            <p class="text-center mt-3">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </p>

        </form>
    </div>

</body>
</html>
