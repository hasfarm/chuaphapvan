@extends('layouts.app')

@section('title', $title ?? 'Admin Panel - chuaphapvan QC')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        :root {
            --primary-green: #10b981;
            --dark-green: #059669;
            --light-green: #d1fae5;
            --primary-orange: #f97316;
            --dark-orange: #ea580c;
            --light-orange: #fed7aa;
            --dark: #1f2937;
            --gray: #6b7280;
            --light-gray: #e5e7eb;
            --white: #ffffff;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
            --control-height: 44px;
            --control-padding-x: 0.75rem;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #f3f4f6;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            color: var(--white);
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
            text-align: center;
        }

        .sidebar-brand h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(0, 0, 0, 0.1);
            color: var(--white);
            border-left-color: var(--light-orange);
        }

        .sidebar-group-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.65);
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
        }

        /* Fix dropdown select color */
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            color: var(--dark) !important;
            background-color: var(--white) !important;
            border: 1px solid var(--light-gray);
            padding: 0.5rem;
            font-size: 1rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        input[type="password"],
        input[type="search"],
        select:not([multiple]):not([size]) {
            height: var(--control-height) !important;
            min-height: var(--control-height) !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: var(--control-padding-x) !important;
            padding-right: var(--control-padding-x) !important;
            line-height: calc(var(--control-height) - 4px) !important;
            box-sizing: border-box;
        }

        .select2-container .select2-selection--single {
            height: var(--control-height) !important;
            min-height: var(--control-height) !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered,
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: calc(var(--control-height) - 4px) !important;
            padding-left: 0.5rem !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow,
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: var(--control-height) !important;
        }

        select option {
            color: var(--dark);
            background-color: var(--white);
            padding: 0.5rem;
        }

        select option:checked {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green)) !important;
            color: var(--white) !important;
        }

        /* Alternative styling for options - browser dependent */
        select optgroup {
            color: var(--dark);
            background-color: var(--white);
        }

        .page-header {
            background: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.75rem;
            color: var(--dark);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-danger {
            background: #ef4444;
            color: var(--white);
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: var(--gray);
            color: var(--white);
        }

        .table-container {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--light-gray);
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        tbody tr:hover {
            background: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-success {
            background: var(--light-green);
            color: var(--dark-green);
        }

        .badge-warning {
            background: var(--light-orange);
            color: var(--dark-orange);
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-buttons a,
        .action-buttons button {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: var(--light-green);
            color: var(--dark-green);
            border-left: 4px solid var(--dark-green);
        }

        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            padding: 1.5rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid var(--light-gray);
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: var(--light-green);
            border-color: var(--primary-green);
        }

        .pagination .active span {
            background: var(--primary-green);
            color: var(--white);
            border-color: var(--primary-green);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            table {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2>
                    <i class="fas fa-sliders-h" style="margin-right: 0.5rem;"></i>
                    Admin Panel
                </h2>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Trang Chủ
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.contacts.index') }}"
                        class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                        <i class="fas fa-praying-hands"></i>
                        Phật tử
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.families.index') }}"
                        class="{{ request()->routeIs('admin.families.*') ? 'active' : '' }}">
                        <i class="fas fa-people-roof"></i>
                        Gia đình
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.events.index') }}"
                        class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-days"></i>
                        Sự kiện
                    </a>
                </li>
                <li class="sidebar-group-title">Cấu hình</li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Người dùng
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}"
                        class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        Vai trò
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.permissions.index') }}"
                        class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="fas fa-lock"></i>
                        Phân quyền
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Alert Messages -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @endif

            @yield('admin-content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Fix dropdown option text color with Select2
        $(document).ready(function() {
            $('select').not('[data-no-select2="1"]').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
            });
        });
    </script>
@endsection
