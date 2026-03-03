@extends('layouts.app')

@section('title', 'Danh Sách Kiểm Soát Chất Lượng - chuaphapvan QC')

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

        /* User Dropdown Style */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-dropdown-btn {
            background: none;
            border: none;
            color: var(--white);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }

        .user-dropdown-btn:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .dropdown-menu-custom {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: var(--white);
            min-width: 250px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 0.5rem;
            z-index: 1000;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-header-custom {
            padding: 1rem;
            background: #f9fafb;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dropdown-user-info {
            flex: 1;
        }

        .dropdown-user-name {
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .dropdown-user-email {
            font-size: 0.85rem;
            color: var(--gray);
            margin: 0.25rem 0 0 0;
        }

        .dropdown-item-custom {
            padding: 1rem;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--dark);
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .dropdown-item-custom:last-child {
            border-bottom: none;
        }

        .dropdown-item-custom:hover {
            background-color: var(--light-gray);
        }

        .dropdown-item-custom i {
            width: 20px;
            text-align: center;
            color: var(--primary-green);
        }

        .dropdown-item-custom.logout {
            color: #dc2626;
        }

        .dropdown-item-custom.logout i {
            color: #dc2626;
        }

        .container-main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            position: sticky;
            top: 75px;
            background: linear-gradient(180deg, #f3f4f6 0%, rgba(243, 244, 246, 0.95) 100%);
            padding: 1rem 0;
            z-index: 90;
        }

        .page-title {
            font-size: 2rem;
            margin: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            border: none;
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
            text-decoration: none;
        }

        .filter-section {
            background: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            transition: padding 0.3s ease;
        }

        .filter-section.collapsed {
            padding: 0.75rem 1.5rem;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
            transition: all 0.3s ease;
        }

        .filter-header.expanded {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .filter-title {
            margin: 0;
            color: var(--dark);
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-collapse-btn {
            background: none;
            border: none;
            color: var(--primary-green);
            font-size: 1rem;
            cursor: pointer;
            padding: 0.25rem;
            transition: transform 0.3s ease, color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-collapse-btn:hover {
            color: var(--dark-green);
        }

        .filter-collapse-btn.collapsed {
            transform: rotate(-90deg);
        }

        .filter-content {
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .filter-content.collapsed {
            max-height: 0;
            opacity: 0;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-row:last-child {
            margin-bottom: 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .form-control,
        .form-select {
            border: 2px solid var(--light-gray);
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
        }

        .filter-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-filter {
            background: var(--primary-green);
            color: var(--white);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background: var(--dark-green);
        }

        .btn-reset {
            background: var(--gray);
            color: var(--white);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: var(--dark-light);
        }

        .table-container {
            background: var(--white);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
        }

        .table th {
            padding: 0.75rem 0.5rem;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            font-size: 0.875rem;
        }

        .table th:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .table td {
            padding: 0.5rem;
            border-bottom: 1px solid var(--light-gray);
            font-size: 0.875rem;
        }

        /* Specific column widths */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 3%;
            text-align: center;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 8%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 8%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 10%;
            white-space: normal;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 7%;
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 12%;
            white-space: normal;
        }

        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 12%;
            white-space: normal;
        }

        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 7%;
        }

        .table th:nth-child(9),
        .table td:nth-child(9) {
            width: 7%;
        }

        .table th:nth-child(10),
        .table td:nth-child(10) {
            width: 6%;
            text-align: center;
        }

        .table th:nth-child(11),
        .table td:nth-child(11) {
            width: 20%;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
            cursor: pointer;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .sort-icon {
            margin-left: 0.5rem;
            font-size: 0.8rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
            border: none;
            border-radius: 0.35rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: var(--primary-green);
            color: var(--white);
        }

        .btn-view:hover {
            background: var(--dark-green);
            text-decoration: none;
            color: var(--white);
        }

        .btn-edit {
            background: var(--primary-orange);
            color: var(--white);
        }

        .btn-edit:hover {
            background: var(--dark-orange);
            text-decoration: none;
            color: var(--white);
        }

        .btn-delete {
            background: #ef4444;
            color: var(--white);
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--light-gray);
            border-radius: 0.35rem;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: var(--light-green);
            border-color: var(--primary-green);
        }

        .pagination .active {
            background: var(--primary-green);
            color: var(--white);
            border-color: var(--primary-green);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-green);
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-green);
        }

        .no-data {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray);
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .table-container {
                display: none;
            }

            .mobile-cards {
                display: block !important;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Mobile Card Styles */
        .mobile-cards {
            display: none !important;
        }

        .mobile-card-wrapper {
            position: relative;
            margin-bottom: 1rem;
            overflow: hidden;
            border-radius: 0.75rem;
        }

        .mobile-card {
            background: var(--white);
            border-radius: 0.75rem;
            box-shadow: var(--shadow);
            position: relative;
            transition: transform 0.3s ease;
            touch-action: pan-y;
            display: flex;
            width: calc(100% + 100px);
        }

        .mobile-card.swiping {
            transition: none;
        }

        .mobile-card-content {
            padding: 1rem;
            position: relative;
            z-index: 2;
            background: var(--white);
            border-radius: 0.75rem;
            flex: 1;
            min-width: 0;
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--light-gray);
        }

        .mobile-card-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark);
            margin: 0 0 0.25rem 0;
        }

        .mobile-card-date {
            font-size: 0.9rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .mobile-card-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--white);
        }

        .mobile-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .mobile-card-item {
            display: flex;
            flex-direction: column;
        }

        .mobile-card-label {
            font-size: 0.75rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .mobile-card-value {
            font-size: 0.95rem;
            color: var(--dark);
            font-weight: 600;
        }

        .mobile-card-actions {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 0;
            flex-shrink: 0;
            width: 100px;
        }

        .mobile-action-btn {
            width: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.15rem;
            border: none;
            color: var(--white);
            font-size: 0.6rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s ease;
            text-decoration: none;
            flex: 1;
        }

        .mobile-action-btn i {
            font-size: 1rem;
        }

        .mobile-action-edit {
            background: var(--primary-orange);
        }

        .mobile-action-delete {
            background: #ef4444;
        }

        .swipe-hint {
            position: absolute;
            right: 110px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 0.75rem;
            opacity: 0.5;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .mobile-card.swiped .swipe-hint {
            opacity: 0;
        }

        @media (max-width: 768px) {
            .mobile-cards {
                display: block !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg" style="padding: 0 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <span class="navbar-brand">
                    <i class="fas fa-table me-2"></i>
                    chuaphapvan QC
                </span>
                <div class="user-dropdown">
                    <button class="user-dropdown-btn" onclick="toggleDropdown()">
                        <div class="user-avatar">
                            @if (auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="userDropdown">
                        <div class="dropdown-header-custom">
                            <div class="user-avatar" style="width: 50px; height: 50px;">
                                @if (auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                        alt="{{ auth()->user()->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="dropdown-user-info">
                                <p class="dropdown-user-name">{{ auth()->user()->name }}</p>
                                <p class="dropdown-user-email">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.photo.edit') }}" class="dropdown-item-custom">
                            <i class="fas fa-image"></i>
                            Update Photo
                        </a>
                        <a href="{{ route('profile.show') }}" class="dropdown-item-custom">
                            <i class="fas fa-user"></i>
                            View Profile
                        </a>
                        <a href="{{ route('profile.password.edit') }}" class="dropdown-item-custom">
                            <i class="fas fa-lock"></i>
                            Change Password
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                            @csrf
                            <button type="submit" class="dropdown-item-custom logout"
                                style="width: 100%; text-align: left;">
                                <i class="fas fa-sign-out-alt"></i>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-main">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-list text-green me-2"></i>
                Lịch Sử Kiểm Soát
            </h1>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" class="btn-primary" onclick="showImportModal()"
                    style="background: linear-gradient(135deg, #0891b2, #0e7490);">
                    <i class="fas fa-file-import"></i>
                    Import Excel
                </button>
                <a href="{{ route('audits.export', request()->query()) }}" class="btn-primary"
                    style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-file-excel"></i>
                    Xuất Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn-primary"
                    style="background: linear-gradient(135deg, #7c3aed, #5b21b6);">
                    <i class="fas fa-chart-line"></i>
                    Báo Cáo
                </a>
                <a href="{{ route('audits.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Thêm mới
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert" style="white-space: pre-wrap;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>{{ session('error') }}</strong>
                @if (session('errors'))
                    <div
                        style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.05); border-radius: 0.5rem; max-height: 300px; overflow-y: auto;">
                        {!! session('errors') !!}
                    </div>
                @endif
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible" role="alert" style="white-space: pre-wrap;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>{{ session('warning') }}</strong>
                @if (session('errors'))
                    <div
                        style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.05); border-radius: 0.5rem; max-height: 300px; overflow-y: auto;">
                        {!! session('errors') !!}
                    </div>
                @endif
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section collapsed" id="filterSection">
            <div class="filter-header" id="filterHeader">
                <h5 class="filter-title">
                    <i class="fas fa-filter"></i>
                    Tìm Kiếm & Lọc
                </h5>
                <button type="button" class="filter-collapse-btn collapsed" onclick="toggleFilterCollapse()">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <div class="filter-content collapsed" id="filterContent">
                <form method="GET" action="{{ route('audits.index') }}">
                    <div class="filter-row">
                        <!-- Search -->
                        <div class="form-group">
                            <label class="form-label" for="search">
                                <i class="fas fa-search me-1"></i>
                                Tìm Kiếm
                            </label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Mã chọn, tên công nhân, giống..." value="{{ request('search') }}">
                        </div>

                        <!-- Date From -->
                        <div class="form-group">
                            <label class="form-label" for="date_from">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Từ Ngày
                            </label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>

                        <!-- Date To -->
                        <div class="form-group">
                            <label class="form-label" for="date_to">
                                <i class="fas fa-calendar-check me-1"></i>
                                Đến Ngày
                            </label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>

                        <!-- Greenhouse -->
                        <div class="form-group">
                            <label class="form-label" for="greenhouse">Nhà Kính</label>
                            <select class="form-select" id="greenhouse" name="greenhouse_id">
                                <option value="">-- Chọn Nhà Kính --</option>
                                @foreach ($greenhouses as $gh)
                                    <option value="{{ $gh }}" @selected(request('greenhouse_id') == $gh)>{{ $gh }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- QC Name -->
                        <div class="form-group">
                            <label class="form-label" for="qc">Tên QC</label>
                            <select class="form-select" id="qc" name="qc_name">
                                <option value="">-- Chọn QC --</option>
                                @foreach ($qcs as $qc)
                                    <option value="{{ $qc }}" @selected(request('qc_name') == $qc)>{{ $qc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="filter-row">
                        <!-- Worker -->
                        <div class="form-group">
                            <label class="form-label" for="worker">Tên Công Nhân</label>
                            <select class="form-select" id="worker" name="worker_name">
                                <option value="">-- Chọn Công Nhân --</option>
                                @foreach ($workers as $w)
                                    <option value="{{ $w }}" @selected(request('worker_name') == $w)>{{ $w }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Variety -->
                        <div class="form-group">
                            <label class="form-label" for="variety">Giống</label>
                            <select class="form-select" id="variety" name="variety_name">
                                <option value="">-- Chọn Giống --</option>
                                @foreach ($varieties as $v)
                                    <option value="{{ $v }}" @selected(request('variety_name') == $v)>{{ $v }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="form-group">
                            <label class="form-label" for="sort">Sắp Xếp</label>
                            <select class="form-select" id="sort" name="sort_by">
                                <option value="date" @selected(request('sort_by') == 'date')>Ngày</option>
                                <option value="qty_quantity" @selected(request('sort_by') == 'qty_quantity')>Số lượng</option>
                                <option value="total_defect" @selected(request('sort_by') == 'total_defect')>Tổng lỗi</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search me-1"></i>
                            Tìm Kiếm
                        </button>
                        <a href="{{ route('audits.index') }}" class="btn-reset">
                            <i class="fas fa-redo me-1"></i>
                            Đặt Lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        @if ($audits->count() > 0)
            <!-- Desktop Table -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ngày</th>
                            <th>NH Kính</th>
                            <th>Tên QC</th>
                            <th>Mã CN</th>
                            <th>Tên CN</th>
                            <th>Giống</th>
                            <th>Plot</th>
                            <th>TL Bịch</th>
                            <th>TĐ</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $index => $audit)
                            <tr onclick="window.location='{{ route('audits.show', $audit) }}'" style="cursor: pointer;">
                                <td>{{ $audits->firstItem() + $index }}</td>
                                <td><strong>{{ $audit->date->format('d/m/Y') }}</strong></td>
                                <td>{{ $audit->greenhouse_id }}</td>
                                <td>{{ $audit->qc_name }}</td>
                                <td><code>{{ $audit->picker_code }}</code></td>
                                <td>{{ $audit->worker_name }}</td>
                                <td>{{ $audit->variety_name }}</td>
                                <td>{{ $audit->plot_code }}</td>
                                <td>{{ number_format($audit->bag_weight, 2) }}</td>
                                <td>
                                    <span class="badge"
                                        style="background: {{ $audit->total_points == 0 ? 'var(--primary-green)' : ($audit->total_points <= 5 ? 'var(--primary-orange)' : '#ef4444') }}; color: white;">
                                        {{ $audit->total_points }}
                                    </span>
                                </td>
                                <td onclick="event.stopPropagation();">
                                    <div class="action-buttons">
                                        <a href="{{ route('audits.edit', $audit) }}" class="btn-sm btn-edit">
                                            <i class="fas fa-edit"></i>
                                            Sửa
                                        </a>
                                        <form method="POST" action="{{ route('audits.destroy', $audit) }}"
                                            style="display: inline;"
                                            onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete">
                                                <i class="fas fa-trash"></i>
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-cards">
                @foreach ($audits as $index => $audit)
                    <div class="mobile-card-wrapper">
                        <div class="mobile-card" data-audit-id="{{ $audit->id }}"
                            onclick="window.location='{{ route('audits.show', $audit) }}'">
                            <div class="mobile-card-content">
                                <div class="mobile-card-header">
                                    <div>
                                        <h3 class="mobile-card-title">{{ $audit->variety_name }}</h3>
                                        <div class="mobile-card-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $audit->date->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="mobile-card-badge"
                                            style="background: {{ $audit->total_points == 0 ? 'var(--primary-green)' : ($audit->total_points <= 5 ? 'var(--primary-orange)' : '#ef4444') }};">
                                            Điểm: {{ $audit->total_points }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mobile-card-grid">
                                    <div class="mobile-card-item">
                                        <div class="mobile-card-label">Nhà Kính</div>
                                        <div class="mobile-card-value">{{ $audit->greenhouse_id }}</div>
                                    </div>
                                    <div class="mobile-card-item">
                                        <div class="mobile-card-label">QC</div>
                                        <div class="mobile-card-value">{{ $audit->qc_name }}</div>
                                    </div>
                                    <div class="mobile-card-item">
                                        <div class="mobile-card-label">Mã Chọn</div>
                                        <div class="mobile-card-value"><code>{{ $audit->picker_code }}</code></div>
                                    </div>
                                    <div class="mobile-card-item">
                                        <div class="mobile-card-label">Công Nhân</div>
                                        <div class="mobile-card-value">{{ $audit->worker_name }}</div>
                                    </div>
                                    <div class="mobile-card-item">
                                        <div class="mobile-card-label">Plot</div>
                                        <div class="mobile-card-value">{{ $audit->plot_code }}</div>
                                    </div>
                                    <div class="mobile-card-item">
                                        <div class="mobile-card-label">Cân Nặng</div>
                                        <div class="mobile-card-value">{{ number_format($audit->bag_weight, 2) }} kg</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mobile-card-actions">
                                <a href="{{ route('audits.edit', $audit) }}" class="mobile-action-btn mobile-action-edit"
                                    onclick="event.stopPropagation();">
                                    <i class="fas fa-edit"></i>
                                    Sửa
                                </a>
                                <form method="POST" action="{{ route('audits.destroy', $audit) }}"
                                    style="display: contents;"
                                    onsubmit="event.stopPropagation(); return confirm('Bạn chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mobile-action-btn mobile-action-delete">
                                        <i class="fas fa-trash"></i>
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Controls -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; gap: 2rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <label for="perPageBtn" style="margin: 0; font-weight: 600; color: var(--dark);">Rows per
                        page:</label>
                    <select id="perPageBtn" class="form-select"
                        style="width: auto; min-width: 80px; padding: 0.5rem 2rem 0.5rem 0.75rem;"
                        onchange="updatePerPage(this.value)">
                        <option value="10" @selected(request('per_page', 20) == 10)>10</option>
                        <option value="25" @selected(request('per_page', 20) == 25)>25</option>
                        <option value="50" @selected(request('per_page', 20) == 50)>50</option>
                        <option value="100" @selected(request('per_page', 20) == 100)>100</option>
                    </select>
                </div>
                <div class="pagination">
                    {{ $audits->links() }}
                </div>
            </div>
        @else
            <div class="no-data">
                <i class="fas fa-inbox"></i>
                <p>Không có bản ghi Kiểm Soát Chất Lượng nào</p>
                <a href="{{ route('audits.create') }}" class="btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i>
                    Thêm Bản Ghi Đầu Tiên
                </a>
            </div>
        @endif
    </main>

    <style>
        .alert {
            position: sticky;
            top: 0;
            z-index: 99;
        }
    </style>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        function toggleFilterCollapse() {
            const filterSection = document.getElementById('filterSection');
            const filterContent = document.getElementById('filterContent');
            const filterHeader = document.getElementById('filterHeader');
            const filterBtn = document.querySelector('.filter-collapse-btn');

            filterSection.classList.toggle('collapsed');
            filterContent.classList.toggle('collapsed');
            filterHeader.classList.toggle('expanded');
            filterBtn.classList.toggle('collapsed');
        }

        function updatePerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const dropdownBtn = event.target.closest('.user-dropdown-btn');

            if (!dropdownBtn && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Mobile Swipe Functionality
        if (window.innerWidth <= 768) {
            const mobileCards = document.querySelectorAll('.mobile-card');

            mobileCards.forEach(card => {
                let startX = 0;
                let currentX = 0;
                let isDragging = false;
                let isSwiped = false;
                const swipeThreshold = 50; // Minimum distance to trigger swipe
                const actionWidth = 100; // Width of action buttons (50px * 2)

                card.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isDragging = true;
                    card.classList.add('swiping');
                }, {
                    passive: true
                });

                card.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;

                    currentX = e.touches[0].clientX;
                    const diff = startX - currentX;

                    // Only allow left swipe
                    if (diff > 0) {
                        const translateX = Math.min(diff, actionWidth);
                        card.style.transform = `translateX(-${translateX}px)`;
                    } else if (isSwiped) {
                        // Allow swiping back to close
                        const translateX = Math.max(actionWidth + diff, 0);
                        card.style.transform = `translateX(-${translateX}px)`;
                    }
                }, {
                    passive: true
                });

                card.addEventListener('touchend', (e) => {
                    if (!isDragging) return;

                    isDragging = false;
                    card.classList.remove('swiping');

                    const diff = startX - currentX;

                    if (isSwiped) {
                        // Card is already swiped
                        if (diff < -swipeThreshold) {
                            // Swipe right to close
                            card.style.transform = 'translateX(0)';
                            card.classList.remove('swiped');
                            isSwiped = false;
                        } else {
                            // Keep it open
                            card.style.transform = `translateX(-${actionWidth}px)`;
                        }
                    } else {
                        // Card is closed
                        if (diff > swipeThreshold) {
                            // Swipe left to open
                            card.style.transform = `translateX(-${actionWidth}px)`;
                            card.classList.add('swiped');
                            isSwiped = true;

                            // Close other cards
                            mobileCards.forEach(otherCard => {
                                if (otherCard !== card) {
                                    otherCard.style.transform = 'translateX(0)';
                                    otherCard.classList.remove('swiped');
                                }
                            });
                        } else {
                            // Snap back
                            card.style.transform = 'translateX(0)';
                        }
                    }
                }, {
                    passive: true
                });

                // Close when clicking outside
                document.addEventListener('touchstart', (e) => {
                    if (!card.contains(e.target) && isSwiped) {
                        card.style.transform = 'translateX(0)';
                        card.classList.remove('swiped');
                        isSwiped = false;
                    }
                }, {
                    passive: true
                });
            });
        }
    </script>

    <!-- Import Modal -->
    <div id="importModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-file-import" style="color: #0891b2;"></i>
                    Import Dữ Liệu Audit
                </h2>
                <button type="button" class="close-btn" onclick="closeImportModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div
                    style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                    <strong
                        style="color: #059669; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-info-circle"></i>
                        Hướng Dẫn Import
                    </strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; color: #374151; font-size: 0.9rem; line-height: 1.6;">
                        <li>Tải file template Excel mẫu bên dưới</li>
                        <li>Điền dữ liệu audit theo đúng định dạng trong template</li>
                        <li>Upload file Excel đã điền dữ liệu</li>
                        <li>File phải có định dạng .xlsx hoặc .xls (không dùng CSV)</li>
                        <li>Kích thước file tối đa: 10MB</li>
                    </ul>
                </div>

                <div
                    style="background: #fff7ed; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                    <strong
                        style="color: #d97706; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Lưu Ý Quan Trọng
                    </strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; color: #374151; font-size: 0.9rem; line-height: 1.6;">
                        <li><strong>Mã Nhà Kính</strong> phải tồn tại trong hệ thống</li>
                        <li><strong>Định dạng ngày:</strong> YYYY-MM-DD, DD/MM/YYYY, hoặc DD-MM-YYYY<br>
                            <span style="font-size: 0.85rem; color: #6b7280;">VD: 2026-01-22 hoặc 22/01/2026 hoặc
                                22-01-2026</span>
                        </li>
                        <li><strong>Mã Lượng:</strong> định dạng WW.YYYY (VD: 01.2026)</li>
                        <li>Các trường bắt buộc không được để trống</li>
                    </ul>
                </div>

                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <a href="{{ route('audits.import-template') }}" class="btn-primary"
                        style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-download"></i>
                        Tải Template Excel Mẫu
                    </a>
                </div>

                <form action="{{ route('audits.import') }}" method="POST" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="importFile"
                            style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Chọn File Excel <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="file" id="importFile" name="file" accept=".xlsx,.xls" required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.95rem;"
                            onchange="updateFileName(this)">
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            Chỉ chấp nhận file .xlsx hoặc .xls
                        </small>
                    </div>

                    <div id="fileInfo"
                        style="display: none; background: #f3f4f6; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                        <i class="fas fa-file-excel" style="color: #059669;"></i>
                        <span id="fileName"></span>
                        <small id="fileSize" style="color: #6b7280; margin-left: 0.5rem;"></small>
                    </div>

                    <div
                        style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                        <button type="button" onclick="closeImportModal()" class="btn-secondary">
                            <i class="fas fa-times"></i>
                            Hủy
                        </button>
                        <button type="submit" class="btn-primary"
                            style="background: linear-gradient(135deg, #0891b2, #0e7490);">
                            <i class="fas fa-upload"></i>
                            Bắt Đầu Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.3s ease;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            color: #9ca3af;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: #374151;
        }

        .btn-secondary {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e5e7eb;
            background: white;
            color: #374151;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <script>
        function showImportModal() {
            document.getElementById('importModal').classList.add('show');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.remove('show');
            document.getElementById('importForm').reset();
            document.getElementById('fileInfo').style.display = 'none';
        }

        function updateFileName(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileInfo = document.getElementById('fileInfo');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');

                fileName.textContent = file.name;
                fileSize.textContent = '(' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                fileInfo.style.display = 'block';
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('importModal');
            if (event.target === modal) {
                closeImportModal();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImportModal();
            }
        });

        // Prevent auto-dismiss for error/warning alerts
        document.addEventListener('DOMContentLoaded', function() {
            const errorAlerts = document.querySelectorAll('.alert-danger, .alert-warning');
            errorAlerts.forEach(alert => {
                // Remove any auto-dismiss behavior
                alert.addEventListener('close.bs.alert', function(e) {
                    // Allow manual close only
                });
            });
        });
    </script>
@endsection
