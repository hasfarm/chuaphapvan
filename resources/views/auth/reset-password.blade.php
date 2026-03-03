@extends('layouts.app')

@section('title', 'Đặt Lại Mật Khẩu - Chùa Pháp Vân')
@section('description', 'Đặt lại mật khẩu tài khoản Chùa Pháp Vân')

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
            color: var(--primary-green);
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

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray);
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 0;
            font-size: 1rem;
        }

        .password-toggle:hover {
            color: var(--primary-green);
        }

        .btn-submit {
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

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
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
            color: var(--primary-green);
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .auth-link a:hover {
            color: var(--dark-green);
        }

        .password-requirements {
            background: #fff7e8;
            border-left: 4px solid var(--primary-green);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .password-requirements h6 {
            color: var(--dark-green);
            margin-bottom: 0.5rem;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
            color: var(--gray);
        }

        .requirement i {
            width: 1.25rem;
            margin-right: 0.5rem;
            color: var(--primary-green);
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

            .password-requirements {
                font-size: 0.8rem;
                padding: 0.75rem;
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
                        <i class="fas fa-lock"></i>
                    </div>
                    <h1>Đặt Lại Mật Khẩu</h1>
                    <p>Tạo mật khẩu mới để tiếp tục sử dụng cổng thông tin Chùa Pháp Vân</p>
                </div>

                <!-- Password Requirements -->
                <div class="password-requirements">
                    <h6>
                        <i class="fas fa-shield-alt me-1"></i>
                        Yêu cầu mật khẩu an toàn
                    </h6>
                    <div class="requirement">
                        <i class="fas fa-check"></i>
                        Ít nhất 8 ký tự
                    </div>
                    <div class="requirement">
                        <i class="fas fa-check"></i>
                        Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt
                    </div>
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

                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                    @csrf

                    <!-- Hidden Token -->
                    <input type="hidden" name="token" value="{{ $token }}">

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

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock text-green me-2"></i>
                            Mật Khẩu Mới
                        </label>
                        <div class="input-group" style="position: relative;">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" class="form-control with-icon @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Nhập mật khẩu mới" required
                                style="padding-right: 2.75rem;">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock text-green me-2"></i>
                            Xác Nhận Mật Khẩu
                        </label>
                        <div class="input-group" style="position: relative;">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password"
                                class="form-control with-icon @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới"
                                required style="padding-right: 2.75rem;">
                            <button type="button" class="password-toggle"
                                onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="btnText">
                            <i class="fas fa-check me-2"></i>
                            Đặt Lại Mật Khẩu
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
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = event.target.closest('.password-toggle');

            if (field.type === 'password') {
                field.type = 'text';
                button.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                field.type = 'password';
                button.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        const resetForm = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');

        resetForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;

            if (password !== confirmation) {
                e.preventDefault();
                alert('Mật khẩu và xác nhận mật khẩu không khớp');
                return;
            }

            submitBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span>Đang xử lý...';
        });

        // Real-time password validation
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');

        function validatePassword() {
            const password = passwordField.value;
            const minLength = password.length >= 8;

            if (password && !minLength) {
                passwordField.classList.add('is-invalid');
            } else {
                passwordField.classList.remove('is-invalid');
            }

            validateConfirmation();
        }

        function validateConfirmation() {
            const password = passwordField.value;
            const confirmation = confirmField.value;

            if (confirmation && password !== confirmation) {
                confirmField.classList.add('is-invalid');
            } else {
                confirmField.classList.remove('is-invalid');
            }
        }

        passwordField.addEventListener('input', validatePassword);
        confirmField.addEventListener('input', validateConfirmation);
    </script>
@endsection
