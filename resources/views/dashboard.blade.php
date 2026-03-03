@extends('layouts.app')

@section('title', 'Dashboard - chuaphapvan QC')

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

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: color 0.3s ease;
            margin-left: 1rem;
        }

        .nav-link:hover {
            color: var(--white) !important;
        }

        .nav-link.active {
            color: var(--white) !important;
            border-bottom: 2px solid var(--white);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: 0.5rem;
        }

        .dropdown-item:hover {
            background-color: var(--light-green);
            color: var(--dark-green);
        }

        .container-main {
            padding: 2rem 1rem;
            max-width: 1200px;
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--light-green), rgba(16, 185, 129, 0.1));
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-green);
        }

        .welcome-section h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            margin: 0;
            color: var(--gray);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--white);
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            padding: 1.5rem;
            border: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-text {
            color: var(--gray);
            font-size: 0.95rem;
            margin: 0;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            color: var(--white);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
            font-weight: 600;
        }

        .breadcrumb {
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item {
            color: var(--gray);
        }

        .breadcrumb-item.active {
            color: var(--dark);
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .welcome-section {
                padding: 1.5rem;
            }

            .welcome-section h1 {
                font-size: 1.5rem;
            }

            .navbar-brand {
                font-size: 1.25rem;
                margin-right: 1rem;
            }

            .nav-link {
                margin-left: 0.5rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 576px) {
            .container-main {
                padding: 0.75rem;
            }

            .welcome-section {
                padding: 1rem;
            }

            .welcome-section h1 {
                font-size: 1.25rem;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div style="display: flex; align-items: center;">
                    <span class="navbar-brand">
                        <i class="fas fa-shield-alt me-2"></i>
                        chuaphapvan QC
                    </span>
                </div>

                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--white); margin: 0;">
                                {{ auth()->user()->name }}
                            </div>
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.7); margin: 0;">
                                @if (auth()->user()->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @elseif(auth()->user()->isModerator())
                                    <span class="badge bg-warning">Moderator</span>
                                @else
                                    <span class="badge bg-info">User</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt me-1"></i>
                            Đăng Xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-main">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <i class="fas fa-home me-2"></i>
                    Dashboard
                </li>
            </ol>
        </nav>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>
                <i class="fas fa-wave-hand text-green me-2"></i>
                Chào mừng, {{ auth()->user()->name }}!
            </h1>
            <p>
                Hệ thống quản lý chất lượng chuaphapvan - Đây là trang dashboard của bạn
            </p>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-grid">
            <!-- User Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>
                    Thông Tin Tài Khoản
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Email:</strong><br>
                        {{ auth()->user()->email }}
                    </p>
                    <p class="card-text" style="margin-top: 1rem;">
                        <strong>Trạng thái:</strong><br>
                        @if (auth()->user()->status === 'active')
                            <span class="badge bg-success">Hoạt động</span>
                        @elseif(auth()->user()->status === 'inactive')
                            <span class="badge bg-warning">Chưa kích hoạt</span>
                        @else
                            <span class="badge bg-danger">Bị khóa</span>
                        @endif
                    </p>
                    <p class="card-text" style="margin-top: 1rem;">
                        <strong>Tham gia:</strong><br>
                        {{ auth()->user()->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Activity Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>
                    Hoạt Động Gần Đây
                </div>
                <div class="card-body">
                    @if (auth()->user()->last_login_at)
                        <p class="card-text">
                            <strong>Lần đăng nhập cuối:</strong><br>
                            {{ auth()->user()->last_login_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="card-text" style="margin-top: 1rem;">
                            <strong>Địa chỉ IP:</strong><br>
                            <code style="background: var(--light-gray); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                {{ auth()->user()->last_login_ip }}
                            </code>
                        </p>
                    @else
                        <p class="card-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Đây là lần đăng nhập đầu tiên của bạn
                        </p>
                    @endif
                </div>
            </div>

            <!-- Role Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-key me-2"></i>
                    Vai Trò & Quyền
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Vai trò hiện tại:</strong><br>
                        <span style="display: inline-block; margin-top: 0.5rem;">
                            @if (auth()->user()->isAdmin())
                                <span class="badge bg-danger" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-crown me-1"></i>
                                    {{ auth()->user()->role->display_name }}
                                </span>
                            @elseif(auth()->user()->isModerator())
                                <span class="badge bg-warning" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-user-shield me-1"></i>
                                    {{ auth()->user()->role->display_name }}
                                </span>
                            @else
                                <span class="badge bg-info" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-user me-1"></i>
                                    {{ auth()->user()->role->display_name }}
                                </span>
                            @endif
                        </span>
                    </p>
                    <p class="card-text" style="margin-top: 1rem;">
                        <strong>Mô tả:</strong><br>
                        {{ auth()->user()->role->description ?? 'Không có mô tả' }}
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Add any interactive features here
    </script>
@endsection
