@extends('layouts.app')

@section('title', 'Hồ Sơ Cá Nhân - chuaphapvan QC')

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
            max-width: 900px;
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

        .profile-card {
            background: var(--white);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 2rem;
            text-align: center;
            color: var(--white);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.3);
            border: 4px solid var(--white);
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .profile-email {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }

        .profile-body {
            padding: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--dark);
            font-weight: 600;
        }

        .profile-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            text-align: center;
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
            color: var(--white);
            text-decoration: none;
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
            color: var(--white);
            text-decoration: none;
        }

        .btn-warning {
            background: var(--primary-orange);
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-warning:hover {
            background: var(--dark-orange);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
            text-decoration: none;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .profile-header {
                padding: 1.5rem 1rem;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .profile-name {
                font-size: 1.5rem;
            }

            .profile-body {
                padding: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                grid-template-columns: 1fr;
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
                <i class="fas fa-user-circle text-green me-2"></i>
                Hồ Sơ Cá Nhân
            </h1>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    @if ($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <h2 class="profile-name">{{ $user->name }}</h2>
                <p class="profile-email">{{ $user->email }}</p>
            </div>

            <div class="profile-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user me-1"></i>
                            Họ và Tên
                        </div>
                        <div class="info-value">{{ $user->fullname ?? $user->name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope me-1"></i>
                            Email
                        </div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-shield-alt me-1"></i>
                            Vai Trò
                        </div>
                        <div class="info-value">
                            @if ($user->role)
                                {{ ucfirst($user->role->name) }}
                            @else
                                Chưa phân quyền
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Ngày Tham Gia
                        </div>
                        <div class="info-value">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('profile.photo.edit') }}" class="btn btn-primary">
                        <i class="fas fa-image"></i>
                        Cập Nhật Ảnh
                    </a>
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-warning">
                        <i class="fas fa-lock"></i>
                        Đổi Mật Khẩu
                    </a>
                    <a href="{{ route('audits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
