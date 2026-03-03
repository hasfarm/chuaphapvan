@extends('layouts.app')

@section('title', 'Đăng Nhập - Chùa Pháp Vân')
@section('description', 'Đăng nhập vào cổng thông tin Chùa Pháp Vân')

@section('styles')
    <style>
        :root {
            --primary-green: #c9a24b;
            --dark-green: #8b5e34;
            --light-green: #f6ead1;
            --primary-orange: #d6b36a;
            --dark-orange: #8b5e34;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: linear-gradient(135deg, #d6b36a 0%, #8b5e34 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-wrapper::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -150px;
            left: -150px;
        }

        .auth-wrapper::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
        }

        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
        }

        .auth-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-orange));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-header p {
            color: var(--gray);
            margin: 0;
        }

        .logo-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1rem;
            pointer-events: none;
        }

        .form-control.with-icon {
            padding-left: 2.75rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--light-gray);
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            accent-color: var(--primary-green);
        }

        .form-check-input:hover {
            border-color: var(--primary-green);
        }

        .form-check-label {
            margin-left: 0.75rem;
            cursor: pointer;
            user-select: none;
            color: var(--dark);
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .auth-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--light-gray);
        }

        .auth-divider span {
            position: relative;
            background: var(--white);
            padding: 0 0.75rem;
            color: var(--gray);
            font-size: 0.875rem;
        }

        .auth-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .auth-links a {
            font-size: 0.875rem;
            color: var(--primary-green);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error-text::before {
            content: '!';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.25rem;
            height: 1.25rem;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.75rem;
        }

        .form-control.is-invalid,
        .form-control.is-invalid:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
        }

        /* Loading state */
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 0.8s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .auth-card {
                padding: 1.5rem;
            }

            .auth-header h1 {
                font-size: 1.75rem;
            }

            .auth-header p {
                font-size: 0.875rem;
            }

            .logo-icon {
                font-size: 2.5rem;
            }

            .form-control {
                font-size: 1rem;
                padding: 0.7rem 0.75rem;
                padding-left: 2.5rem;
            }

            .input-icon {
                left: 0.75rem;
            }

            .btn-login {
                padding: 0.8rem;
                font-size: 0.95rem;
            }

            .auth-links {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <!-- Header -->
                <div class="auth-header">
                    <div class="logo-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h1>Chùa Pháp Vân</h1>
                    <p>Cổng thông tin nội bộ</p>
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="fw-bold mb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Lỗi xác thực
                        </div>
                        @foreach ($errors->all() as $error)
                            <div class="small">• {{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope text-green me-2"></i>
                            Email
                        </label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" class="form-control with-icon @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Nhập email của bạn" value="{{ old('email') }}"
                                required autofocus>
                        </div>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock text-green me-2"></i>
                            Mật khẩu
                        </label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" class="form-control with-icon @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
                        </div>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ghi nhớ tôi
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span id="btnText">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Đăng Nhập
                        </span>
                    </button>

                    <!-- Links -->
                    <div class="auth-links">
                        <a href="{{ route('password.request') }}">
                            <i class="fas fa-key me-1"></i>
                            Quên mật khẩu?
                        </a>
                    </div>
                </form>

                <!-- Footer -->
                <div
                    style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light-gray);">
                    <p style="margin: 0; font-size: 0.875rem; color: var(--gray);">
                        © {{ date('Y') }} Chùa Pháp Vân. Kính chúc thân tâm thường an lạc.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = document.getElementById('btnText');

        loginForm.addEventListener('submit', function(e) {
            // Disable button to prevent double submission
            loginBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span>Đang xử lý...';
        });

        // Validate email format on input
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
@endsection
