@extends('layouts.app')

@section('title', 'Báo Cáo Thống Kê - chuaphapvan QC')

@section('styles')
    <style>
        .navbar {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            box-shadow: var(--shadow);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white) !important;
        }

        .container-main {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .breadcrumb {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .breadcrumb a {
            color: #7c3aed;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            margin: 0 0 0.5rem 0;
            color: #7c3aed;
        }

        .filters-section {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            align-items: end;
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

        .form-control {
            border: 2px solid var(--light-gray);
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #7c3aed;
            outline: none;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card-title {
            font-weight: 600;
            color: var(--dark);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .table-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .table-card-header {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .table-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f9fafb;
        }

        .table th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .progress-bar {
            height: 8px;
            background: #f3f4f6;
            border-radius: 1rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
        }

        .comparison-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .comparison-item {
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
        }

        .comparison-label {
            font-size: 0.875rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .comparison-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .comparison-section {
                grid-template-columns: 1fr;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg" style="padding: 0 1rem;">
            <span class="navbar-brand">
                <i class="fas fa-chart-bar me-2"></i>
                Báo Cáo Thống Kê
            </span>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-main">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('audits.index') }}">
                <i class="fas fa-list"></i>
                Danh Sách
            </a>
            <span>/</span>
            <span>Báo Cáo</span>
        </div>

        <!-- Page Header -->
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1>
                    <i class="fas fa-chart-line me-2"></i>
                    Báo Cáo Thống Kê Kiểm Soát Chất Lượng
                </h1>
                <p style="color: var(--gray); margin: 0;">
                    Phân tích và theo dõi hiệu suất công nhân qua các chỉ tiêu chất lượng
                </p>
            </div>
            <a href="{{ route('audits.index') }}" class="btn btn-primary"
                style="background: linear-gradient(135deg, var(--primary-green), var(--dark-green)); padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; color: white; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                <i class="fas fa-list"></i>
                Danh Sách
            </a>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('reports.index') }}">
                <div class="filter-row">
                    <div class="form-group">
                        <label class="form-label" for="date">
                            <i class="fas fa-calendar me-1"></i>
                            Ngày kiểm tra
                        </label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="{{ $selectedDate }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="month">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Tháng thống kê
                        </label>
                        <input type="month" class="form-control" id="month" name="month"
                            value="{{ $selectedMonth }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="qc_name">
                            <i class="fas fa-user-check me-1"></i>
                            Tên QC
                        </label>
                        <select class="form-control" id="qc_name" name="qc_name">
                            <option value="">-- Tất cả QC --</option>
                            @foreach ($qcList as $qc)
                                <option value="{{ $qc }}" {{ $selectedQC == $qc ? 'selected' : '' }}>
                                    {{ $qc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>
                            Lọc dữ liệu
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 1. Thống kê công nhân được kiểm tra 6 lần/ngày -->
        <div class="table-card">
            <div class="table-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="table-card-title">
                    <i class="fas fa-clipboard-check" style="color: #7c3aed;"></i>
                    Công Nhân Được Kiểm Tra ({{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }})
                </h3>
                <a href="{{ route('reports.export-worker-checks', ['date' => $selectedDate]) }}" class="btn btn-primary"
                    style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                    <i class="fas fa-file-excel me-1"></i>
                    Xuất Excel
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã Picker</th>
                            <th>Tên Công Nhân</th>
                            <th style="text-align: center;">Số lần kiểm tra</th>
                            <th style="text-align: center;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($workerCheckCount as $index => $worker)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $worker->picker_code }}</strong></td>
                                <td>{{ $worker->worker_name }}</td>
                                <td style="text-align: center;">
                                    <span
                                        style="font-weight: 600; font-size: 1.1rem; color: {{ $worker->meets_requirement ? '#10b981' : '#ef4444' }};">
                                        {{ $worker->check_count }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    @if ($worker->meets_requirement)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle me-1"></i>Đạt yêu cầu
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Chưa đủ
                                            ({{ 6 - $worker->check_count }} lần)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray);">
                                    <i class="fas fa-inbox"
                                        style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    Không có dữ liệu cho ngày này
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Danh sách công nhân được kiểm tra bởi QC -->
        @if ($selectedQC)
            <div class="table-card">
                <div class="table-card-header">
                    <h3 class="table-card-title">
                        <i class="fas fa-user-friends" style="color: #10b981;"></i>
                        Công Nhân Được Kiểm Tra Bởi QC: {{ $selectedQC }}
                    </h3>
                </div>

                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã Picker</th>
                                <th>Tên Công Nhân</th>
                                <th style="text-align: center;">Số lần kiểm tra</th>
                                <th style="text-align: center;">Điểm TB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workersByQC as $index => $worker)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $worker->picker_code }}</strong></td>
                                    <td>{{ $worker->worker_name }}</td>
                                    <td style="text-align: center;">{{ $worker->check_count }}</td>
                                    <td style="text-align: center;">
                                        <span
                                            style="font-weight: 600; color: {{ $worker->avg_points >= 95 ? '#10b981' : '#ef4444' }};">
                                            {{ number_format($worker->avg_points, 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray);">
                                        Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- 3. Thống kê điểm theo tháng -->
        <div class="table-card">
            <div class="table-card-header">
                <h3 class="table-card-title">
                    <i class="fas fa-percentage" style="color: #f59e0b;"></i>
                    Tỉ Lệ Công Nhân Theo Điểm (Tháng {{ \Carbon\Carbon::parse($selectedMonth)->format('m/Y') }})
                </h3>
            </div>

            <div class="comparison-section">
                <div class="comparison-item">
                    <div class="comparison-label">Tháng hiện tại</div>
                    <div class="comparison-value" style="color: #7c3aed;">
                        {{ $currentStats['month'] }}
                    </div>
                    <div style="margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #10b981; font-weight: 600;">≥ 95 điểm</span>
                            <a href="#" class="worker-list-link" data-period="current" data-type="above_95"
                                style="font-weight: 700; color: #10b981; text-decoration: none;">
                                {{ $currentStats['above_95'] }} CN ({{ $currentStats['above_95_percent'] }}%)
                            </a>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                style="width: {{ $currentStats['above_95_percent'] }}%; background: #10b981;"></div>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; margin-top: 1rem; margin-bottom: 0.5rem;">
                            <span style="color: #ef4444; font-weight: 600;">
                                < 95 điểm</span>
                                    <a href="#" class="worker-list-link" data-period="current"
                                        data-type="below_95"
                                        style="font-weight: 700; color: #ef4444; text-decoration: none;">
                                        {{ $currentStats['below_95'] }} CN ({{ $currentStats['below_95_percent'] }}%)
                                    </a>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                style="width: {{ $currentStats['below_95_percent'] }}%; background: #ef4444;"></div>
                        </div>

                        <div
                            style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; text-align: center;">
                            <span style="font-size: 0.875rem; color: var(--gray);">Tổng: </span>
                            <span
                                style="font-size: 1.25rem; font-weight: 700; color: #7c3aed;">{{ $currentStats['total'] }}
                                công nhân</span>
                        </div>
                    </div>
                </div>

                <div class="comparison-item">
                    <div class="comparison-label">Tháng trước</div>
                    <div class="comparison-value" style="color: var(--gray);">
                        {{ $previousMonthStats['month'] }}
                    </div>
                    <div style="margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #10b981; font-weight: 600;">≥ 95 điểm</span>
                            <a href="#" class="worker-list-link" data-period="previous_month" data-type="above_95"
                                style="font-weight: 700; color: #10b981; text-decoration: none;">
                                {{ $previousMonthStats['above_95'] }} CN ({{ $previousMonthStats['above_95_percent'] }}%)
                            </a>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                style="width: {{ $previousMonthStats['above_95_percent'] }}%; background: #10b981;"></div>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; margin-top: 1rem; margin-bottom: 0.5rem;">
                            <span style="color: #ef4444; font-weight: 600;">
                                < 95 điểm</span>
                                    <a href="#" class="worker-list-link" data-period="previous_month"
                                        data-type="below_95"
                                        style="font-weight: 700; color: #ef4444; text-decoration: none;">
                                        {{ $previousMonthStats['below_95'] }} CN
                                        ({{ $previousMonthStats['below_95_percent'] }}%)
                                    </a>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                style="width: {{ $previousMonthStats['below_95_percent'] }}%; background: #ef4444;"></div>
                        </div>

                        <div
                            style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; text-align: center;">
                            <span style="font-size: 0.875rem; color: var(--gray);">Tổng: </span>
                            <span
                                style="font-size: 1.25rem; font-weight: 700; color: var(--gray);">{{ $previousMonthStats['total'] }}
                                công nhân</span>
                        </div>
                    </div>
                </div>

                <div class="comparison-item">
                    <div class="comparison-label">Năm trước</div>
                    <div class="comparison-value" style="color: var(--gray);">
                        {{ $previousYearStats['month'] }}
                    </div>
                    <div style="margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #10b981; font-weight: 600;">≥ 95 điểm</span>
                            <a href="#" class="worker-list-link" data-period="previous_year" data-type="above_95"
                                style="font-weight: 700; color: #10b981; text-decoration: none;">
                                {{ $previousYearStats['above_95'] }} CN ({{ $previousYearStats['above_95_percent'] }}%)
                            </a>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                style="width: {{ $previousYearStats['above_95_percent'] }}%; background: #10b981;"></div>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; margin-top: 1rem; margin-bottom: 0.5rem;">
                            <span style="color: #ef4444; font-weight: 600;">
                                < 95 điểm</span>
                                    <a href="#" class="worker-list-link" data-period="previous_year"
                                        data-type="below_95"
                                        style="font-weight: 700; color: #ef4444; text-decoration: none;">
                                        {{ $previousYearStats['below_95'] }} CN
                                        ({{ $previousYearStats['below_95_percent'] }}%)
                                    </a>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                style="width: {{ $previousYearStats['below_95_percent'] }}%; background: #ef4444;"></div>
                        </div>

                        <div
                            style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; text-align: center;">
                            <span style="font-size: 0.875rem; color: var(--gray);">Tổng: </span>
                            <span
                                style="font-size: 1.25rem; font-weight: 700; color: var(--gray);">{{ $previousYearStats['total'] }}
                                công nhân</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Thống kê lỗi theo loại -->
        <div class="table-card">
            <div class="table-card-header">
                <h3 class="table-card-title">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                    Thống Kê Lỗi Theo Loại (Tháng {{ \Carbon\Carbon::parse($selectedMonth)->format('m/Y') }})
                </h3>
            </div>

            @foreach ($errorStats as $errorType => $stat)
                <div style="margin-bottom: 2rem;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #f3f4f6;">
                        <h4 style="margin: 0; color: var(--dark);">
                            <i class="fas fa-bug me-2" style="color: #ef4444;"></i>
                            {{ $stat['label'] }}
                        </h4>
                        <span style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">
                            {{ $stat['total'] }} lỗi
                        </span>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã Picker</th>
                                    <th>Tên Công Nhân</th>
                                    <th style="text-align: center;">Tổng lỗi</th>
                                    <th style="text-align: center;">Tỉ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stat['top_workers'] as $index => $worker)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $worker->picker_code }}</strong></td>
                                        <td>
                                            <a href="#" class="worker-trend-link"
                                                data-picker-code="{{ $worker->picker_code }}"
                                                data-worker-name="{{ $worker->worker_name }}"
                                                data-error-type="{{ $errorType }}"
                                                data-error-label="{{ $stat['label'] }}"
                                                style="color: #7c3aed; text-decoration: none; font-weight: 600;">
                                                {{ $worker->worker_name }}
                                                <i class="fas fa-chart-line"
                                                    style="font-size: 0.875rem; margin-left: 0.25rem;"></i>
                                            </a>
                                        </td>
                                        <td style="text-align: center; font-weight: 600; color: #ef4444;">
                                            {{ $worker->error_count }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $stat['total'] > 0 ? number_format(($worker->error_count / $stat['total']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 1rem; color: var(--gray);">
                                            Không có lỗi loại này trong tháng
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal hiển thị danh sách công nhân -->
    <div id="workerListModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background: white; border-radius: 1rem; max-width: 600px; width: 90%; max-height: 80vh; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div
                style="background: linear-gradient(135deg, #7c3aed, #5b21b6); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.25rem; color: white;" id="modalTitle">Danh Sách Công Nhân</h3>
                <button onclick="closeWorkerModal()"
                    style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">&times;</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; max-height: calc(80vh - 80px);">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã Picker</th>
                            <th>Tên Công Nhân</th>
                            <th style="text-align: center;">Số lần KT</th>
                            <th style="text-align: center;">Điểm TB</th>
                        </tr>
                    </thead>
                    <tbody id="workerListBody">
                    </tbody>
                    <tfoot id="workerListFooter" style="border-top: 2px solid #7c3aed;">
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal hiển thị xu hướng cải thiện -->
    <div id="workerTrendModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1001; align-items: center; justify-content: center;">
        <div
            style="background: white; border-radius: 1rem; max-width: 800px; width: 90%; max-height: 85vh; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div
                style="background: linear-gradient(135deg, #7c3aed, #5b21b6); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.25rem; color: white;" id="trendModalTitle">Xu Hướng Cải Thiện</h3>
                <button onclick="closeTrendModal()"
                    style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">&times;</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; max-height: calc(85vh - 80px);">
                <div id="trendLoading" style="text-align: center; padding: 2rem; color: var(--gray);">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    Đang tải dữ liệu...
                </div>
                <div id="trendContent" style="display: none;">
                    <div id="trendChart" style="margin-bottom: 1.5rem;"></div>
                    <div
                        style="background: #f9fafb; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #7c3aed;">
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: var(--gray);">
                            <i class="fas fa-info-circle"></i> Ghi chú:
                        </p>
                        <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem; color: var(--dark);">
                            <li>Biểu đồ hiển thị số lỗi trung bình trong 12 tháng gần đây</li>
                            <li>Đường xu hướng giảm = Công nhân đang cải thiện</li>
                            <li>Đường xu hướng tăng = Cần chú ý và đào tạo thêm</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hiển thị chi tiết các lần kiểm tra -->
    <div id="workerDetailModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1002; align-items: center; justify-content: center;">
        <div
            style="background: white; border-radius: 1rem; max-width: 700px; width: 90%; max-height: 85vh; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div
                style="background: linear-gradient(135deg, #7c3aed, #5b21b6); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.25rem; color: white;" id="detailModalTitle">Chi Tiết Kiểm Tra</h3>
                <button onclick="closeDetailModal()"
                    style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">&times;</button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; max-height: calc(85vh - 80px);">
                <div id="detailLoading" style="text-align: center; padding: 2rem; color: var(--gray);">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    Đang tải dữ liệu...
                </div>
                <div id="detailContent" style="display: none;">
                    <table class="table" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ngày</th>
                                <th>Lô/Nhà Kính</th>
                                <th style="text-align: center;">Điểm</th>
                                <th style="text-align: center;">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dữ liệu công nhân
        const workerData = {
            current: {
                above_95: @json($currentStats['above_95_list']),
                below_95: @json($currentStats['below_95_list'])
            },
            previous_month: {
                above_95: @json($previousMonthStats['above_95_list']),
                below_95: @json($previousMonthStats['below_95_list'])
            },
            previous_year: {
                above_95: @json($previousYearStats['above_95_list']),
                below_95: @json($previousYearStats['below_95_list'])
            }
        };

        const periodLabels = {
            current: 'Tháng Hiện Tại ({{ $currentStats['month'] }})',
            previous_month: 'Tháng Trước ({{ $previousMonthStats['month'] }})',
            previous_year: 'Năm Trước ({{ $previousYearStats['month'] }})'
        };

        const typeLabels = {
            above_95: 'Công Nhân Có Điểm TB ≥ 95',
            below_95: 'Công Nhân Có Điểm TB < 95'
        };

        // Xử lý click vào hyperlink
        document.querySelectorAll('.worker-list-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.dataset.period;
                const type = this.dataset.type;
                showWorkerList(period, type);
            });
        });

        function showWorkerList(period, type) {
            const workers = workerData[period][type];
            const modal = document.getElementById('workerListModal');
            const modalTitle = document.getElementById('modalTitle');
            const tbody = document.getElementById('workerListBody');

            // Cập nhật tiêu đề
            modalTitle.textContent = `${typeLabels[type]} - ${periodLabels[period]}`;

            const tfoot = document.getElementById('workerListFooter');

            // Tạo nội dung bảng
            if (workers.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray);">Không có dữ liệu</td></tr>';
                tfoot.innerHTML = '';
            } else {
                // Tổng số công nhân
                const totalWorkers = workers.length;

                tbody.innerHTML = workers.map((worker, index) => {
                    const avgPoints = parseFloat(worker.avg_points);
                    const checkCount = parseInt(worker.check_count);
                    const pointColor = avgPoints >= 95 ? '#10b981' : '#ef4444';
                    return `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>${worker.picker_code}</strong></td>
                            <td>${worker.worker_name || 'N/A'}</td>
                            <td style="text-align: center;">
                                <a href="#" class="worker-detail-link" 
                                   data-picker-code="${worker.picker_code}" 
                                   data-worker-name="${worker.worker_name || 'N/A'}" 
                                   data-period="${period}"
                                   style="font-weight: 600; color: #7c3aed; text-decoration: none;">
                                    ${checkCount}
                                    <i class="fas fa-list" style="font-size: 0.75rem; margin-left: 0.25rem;"></i>
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: 600; color: ${pointColor};">
                                ${avgPoints.toFixed(1)}
                            </td>
                        </tr>
                    `;
                }).join('');

                // Hiển thị tổng
                tfoot.innerHTML = `
                    <tr style="background: #f9fafb;">
                        <td colspan="3" style="text-align: right; padding: 1rem; font-weight: 700; color: #7c3aed;">Tổng số công nhân:</td>
                        <td colspan="2" style="text-align: center; padding: 1rem; font-size: 1.25rem; font-weight: 700; color: #7c3aed;">${totalWorkers} CN</td>
                    </tr>
                `;

                // Thêm event listener cho các link chi tiết
                setTimeout(() => {
                    document.querySelectorAll('.worker-detail-link').forEach(link => {
                        link.addEventListener('click', async function(e) {
                            e.preventDefault();
                            const pickerCode = this.dataset.pickerCode;
                            const workerName = this.dataset.workerName;
                            const period = this.dataset.period;
                            showWorkerDetail(pickerCode, workerName, period);
                        });
                    });
                }, 100);
            }

            // Hiển thị modal
            modal.style.display = 'flex';
        }

        function closeWorkerModal() {
            document.getElementById('workerListModal').style.display = 'none';
        }

        // Đóng modal khi click bên ngoài
        document.getElementById('workerListModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWorkerModal();
            }
        });

        // Đóng modal khi nhấn ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWorkerModal();
                closeTrendModal();
            }
        });

        // Xử lý click vào tên công nhân để xem xu hướng
        document.querySelectorAll('.worker-trend-link').forEach(link => {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                const pickerCode = this.dataset.pickerCode;
                const workerName = this.dataset.workerName;
                const errorType = this.dataset.errorType;
                const errorLabel = this.dataset.errorLabel;

                showWorkerTrend(pickerCode, workerName, errorType, errorLabel);
            });
        });

        async function showWorkerTrend(pickerCode, workerName, errorType, errorLabel) {
            const modal = document.getElementById('workerTrendModal');
            const title = document.getElementById('trendModalTitle');
            const loading = document.getElementById('trendLoading');
            const content = document.getElementById('trendContent');
            const chartDiv = document.getElementById('trendChart');

            // Hiển thị modal và loading
            modal.style.display = 'flex';
            loading.style.display = 'block';
            content.style.display = 'none';
            title.textContent = `Xu Hướng Cải Thiện: ${workerName} - ${errorLabel}`;

            try {
                // Fetch dữ liệu từ API
                const response = await fetch(`/reports/worker-trend?picker_code=${pickerCode}&error_type=${errorType}`);
                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Ẩn loading, hiển thị content
                loading.style.display = 'none';
                content.style.display = 'block';

                // Render biểu đồ
                renderTrendChart(data, chartDiv, errorLabel);
            } catch (error) {
                loading.innerHTML = `
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #ef4444; margin-bottom: 1rem; display: block;"></i>
                    <p style="color: #ef4444;">Không thể tải dữ liệu: ${error.message}</p>
                `;
            }
        }

        function renderTrendChart(data, container, errorLabel) {
            if (data.length === 0) {
                container.innerHTML =
                    '<p style="text-align: center; padding: 2rem; color: var(--gray);">Không có dữ liệu để hiển thị</p>';
                return;
            }

            // Tìm giá trị max để scale
            const maxError = Math.max(...data.map(d => parseFloat(d.avg_error)));
            const chartHeight = 300;
            const chartWidth = container.offsetWidth || 700;
            const padding = 50;
            const barWidth = Math.min((chartWidth - padding * 2) / data.length - 10, 60);

            let html = `
                <div style="position: relative; height: ${chartHeight + 80}px; background: #f9fafb; border-radius: 0.5rem; padding: ${padding}px;">
                    <svg width="${chartWidth}" height="${chartHeight}" style="position: absolute; top: ${padding}px; left: 0;">
            `;

            // Vẽ các cột
            data.forEach((item, index) => {
                const barHeight = maxError > 0 ? (parseFloat(item.avg_error) / maxError) * (chartHeight - 40) : 0;
                const x = padding + index * (barWidth + 10);
                const y = chartHeight - barHeight - 20;

                // Màu dựa trên xu hướng
                let color = '#7c3aed';
                if (index > 0) {
                    const prevError = parseFloat(data[index - 1].avg_error);
                    const currError = parseFloat(item.avg_error);
                    if (currError < prevError) {
                        color = '#10b981'; // Giảm = tốt
                    } else if (currError > prevError) {
                        color = '#ef4444'; // Tăng = xấu
                    }
                }

                html += `
                    <rect x="${x}" y="${y}" width="${barWidth}" height="${barHeight}" 
                          fill="${color}" rx="4" opacity="0.8">
                        <title>${item.month}: ${parseFloat(item.avg_error).toFixed(2)} lỗi TB</title>
                    </rect>
                    <text x="${x + barWidth/2}" y="${y - 5}" 
                          text-anchor="middle" font-size="12" font-weight="600" fill="${color}">
                        ${parseFloat(item.avg_error).toFixed(1)}
                    </text>
                    <text x="${x + barWidth/2}" y="${chartHeight + 15}" 
                          text-anchor="middle" font-size="11" fill="#666" transform="rotate(-45 ${x + barWidth/2} ${chartHeight + 15})">
                        ${item.month}
                    </text>
                `;
            });

            html += `
                    </svg>
                    <div style="margin-top: ${chartHeight + 40}px; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 20px; background: #10b981; border-radius: 4px;"></div>
                            <span style="font-size: 0.875rem;">Cải thiện</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 20px; background: #ef4444; border-radius: 4px;"></div>
                            <span style="font-size: 0.875rem;">Tăng lỗi</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 20px; background: #7c3aed; border-radius: 4px;"></div>
                            <span style="font-size: 0.875rem;">Không đổi</span>
                        </div>
                    </div>
                </div>
            `;

            container.innerHTML = html;
        }

        function closeTrendModal() {
            document.getElementById('workerTrendModal').style.display = 'none';
        }

        // Đóng trend modal khi click bên ngoài
        document.getElementById('workerTrendModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTrendModal();
            }
        });

        // Xử lý hiển thị chi tiết các lần kiểm tra
        async function showWorkerDetail(pickerCode, workerName, period) {
            const modal = document.getElementById('workerDetailModal');
            const title = document.getElementById('detailModalTitle');
            const loading = document.getElementById('detailLoading');
            const content = document.getElementById('detailContent');
            const tbody = document.getElementById('detailTableBody');

            // Xác định tháng dựa trên period
            let monthValue;
            if (period === 'current') {
                monthValue = '{{ $selectedMonth }}';
            } else if (period === 'previous_month') {
                monthValue = '{{ $previousMonthStats['month'] }}'.split('/').reverse().join('-');
            } else if (period === 'previous_year') {
                monthValue = '{{ $previousYearStats['month'] }}'.split('/').reverse().join('-');
            }

            // Hiển thị modal và loading
            modal.style.display = 'flex';
            loading.style.display = 'block';
            content.style.display = 'none';
            title.textContent = `Chi Tiết Kiểm Tra: ${workerName} (${pickerCode})`;

            try {
                // Fetch dữ liệu từ API
                const response = await fetch(`/reports/worker-audits?picker_code=${pickerCode}&month=${monthValue}`);
                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Ẩn loading, hiển thị content
                loading.style.display = 'none';
                content.style.display = 'block';

                // Render bảng
                if (data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray);">Không có dữ liệu</td></tr>';
                } else {
                    tbody.innerHTML = data.map((audit, index) => {
                        const pointColor = parseFloat(audit.total_points) >= 95 ? '#10b981' : '#ef4444';
                        const dateFormatted = new Date(audit.date).toLocaleDateString('vi-VN');
                        return `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${dateFormatted}</td>
                                <td style="font-size: 0.875rem;">${audit.plot_code || 'N/A'}<br/><span style="color: var(--gray);">${audit.greenhouse_name || ''}</span></td>
                                <td style="text-align: center; font-weight: 600; color: ${pointColor};">
                                    ${parseFloat(audit.total_points).toFixed(1)}
                                </td>
                                <td style="text-align: center;">
                                    <a href="/audits/${audit.id}" target="_blank" 
                                       style="color: #7c3aed; text-decoration: none; font-size: 0.875rem;">
                                        <i class="fas fa-external-link-alt"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (error) {
                loading.innerHTML = `
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #ef4444; margin-bottom: 1rem; display: block;"></i>
                    <p style="color: #ef4444;">Không thể tải dữ liệu: ${error.message}</p>
                `;
            }
        }

        function closeDetailModal() {
            document.getElementById('workerDetailModal').style.display = 'none';
        }

        // Đóng detail modal khi click bên ngoài
        document.getElementById('workerDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>
@endsection
