@extends('layouts.app')

@section('title', 'Đổi Mật Khẩu - chuaphapvan QC')

@section('styles')
    <style>
        .navbar {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            box-shadow: var(--shadow);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white) !important;
            margin-right: 2rem;
        }

        .container-main {
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            margin: 0;
            color: var(--dark);
        }

        .form-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .security-notice {
            padding: 1rem 1.5rem;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: start;
            gap: 1rem;
        }

        .security-notice i {
            color: #f59e0b;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .security-notice-content h4 {
            margin: 0 0 0.5rem 0;
            color: #92400e;
            font-size: 1rem;
        }

        .security-notice-content p {
            margin: 0;
            color: #78350f;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .password-input-wrapper {
            position: relative;
        }

        .form-control {
            border: 2px solid var(--light-gray);
            border-radius: 0.5rem;
            padding: 0.75rem;
            padding-right: 3rem;
            font-size: 1rem;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
            outline: none;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            padding: 0.25rem;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--dark);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 0.5rem;
        }

        .password-requirements {
            background: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .password-requirements h5 {
            font-size: 0.9rem;
            margin: 0 0 0.5rem 0;
            color: var(--dark);
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 1.5rem;
            list-style: none;
        }

        .password-requirements li {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 0.25rem;
            position: relative;
        }

        .password-requirements li:before {
            content: "✓";
            position: absolute;
            left: -1.25rem;
            color: var(--primary-green);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            flex: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--gray);
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-secondary:hover {
            background: var(--dark-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg" style="padding: 0 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <a href="{{ route('audits.index') }}" class="navbar-brand">
                    <i class="fas fa-table me-2"></i>
                    chuaphapvan QC
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-main">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-lock text-green me-2"></i>
                Đổi Mật Khẩu
            </h1>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <!-- Security Notice -->
            <div class="security-notice">
                <i class="fas fa-shield-alt"></i>
                <div class="security-notice-content">
                    <h4>Bảo mật tài khoản</h4>
                    <p>Sử dụng mật khẩu mạnh để bảo vệ tài khoản của bạn. Không chia sẻ mật khẩu với bất kỳ ai.</p>
                </div>
            </div>

            <!-- Change Password Form -->
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf

                <!-- Current Password -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key"></i>
                        Mật Khẩu Hiện Tại
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Mật Khẩu Mới
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-requirements">
                        <h5>Yêu cầu mật khẩu:</h5>
                        <ul>
                            <li>Nên bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                            <li>Không trùng với mật khẩu cũ</li>
                        </ul>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Xác Nhận Mật Khẩu Mới
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="help-text">Nhập lại mật khẩu mới để xác nhận</p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Cập Nhật Mật Khẩu
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
