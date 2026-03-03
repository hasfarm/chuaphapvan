@extends('layouts.app')

@section('title', 'Quên Mật Khẩu - Chùa Pháp Vân')
@section('description', 'Khôi phục mật khẩu tài khoản Chùa Pháp Vân')

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
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .auth-header p {
            color: var(--gray);
            margin: 0;
            font-size: 0.95rem;
        }

        .logo-icon {
            font-size: 3rem;
            color: var(--primary-orange);
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

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--dark-orange), var(--primary-orange));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .auth-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--light-gray);
        }

        .auth-link a {
            color: var(--primary-orange);
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .auth-link a:hover {
            color: var(--dark-orange);
        }

        .info-box {
            background: #fef3c7;
            border-left: 4px solid var(--primary-orange);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #92400e;
        }

        .info-box i {
            margin-right: 0.5rem;
            color: var(--primary-orange);
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

        @media (max-width: 576px) {
            .auth-card {
                padding: 1.5rem;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }

            .auth-header p {
                font-size: 0.85rem;
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

            .btn-submit {
                padding: 0.8rem;
                font-size: 0.95rem;
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
                        <i class="fas fa-key"></i>
                    </div>
                    <h1>Quên Mật Khẩu?</h1>
                    <p>Nhập email để nhận hướng dẫn khôi phục tài khoản Chùa Pháp Vân</p>
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    Chúng tôi sẽ gửi liên kết đặt lại mật khẩu đến email đã đăng ký
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="fw-bold mb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Lỗi
                        </div>
                        @foreach ($errors->all() as $error)
                            <div class="small">• {{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope text-orange me-2"></i>
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

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="btnText">
                            <i class="fas fa-paper-plane me-2"></i>
                            Gửi Liên Kết Khôi Phục
                        </span>
                    </button>

                    <!-- Back to Login -->
                    <div class="auth-link">
                        <a href="{{ route('login') }}">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại đăng nhập
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
        const forgotForm = document.getElementById('forgotForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');

        forgotForm.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span>Đang gửi...';
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
