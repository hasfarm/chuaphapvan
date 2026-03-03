@extends('layouts.app')

@section('title', 'Sửa Kiểm Soát Chất Lượng - chuaphapvan QC')

@section('styles')
    <style>
        .navbar {
            background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
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
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
        }

        .breadcrumb {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .breadcrumb a {
            color: var(--primary-orange);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .form-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--gray);
            margin: 0;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            padding-bottom: 1rem;
            border-bottom: 2px solid #fed7aa;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .form-label small {
            font-weight: 400;
            color: var(--gray);
            font-size: 0.85rem;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--light-gray);
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
        }

        .form-control[readonly] {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error-message::before {
            content: '!';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1rem;
            height: 1rem;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.7rem;
        }

        .form-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding: 1.5rem;
            border-top: 1px solid var(--light-gray);
            position: sticky;
            bottom: 0;
            background: var(--white);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
            border-radius: 0 0 1rem 1rem;
        }

        .btn {
            padding: 0.75rem 2rem;
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

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
            color: var(--white);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--dark-orange), var(--primary-orange));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-cancel {
            background: var(--light-gray);
            color: var(--dark);
        }

        .btn-cancel:hover {
            background: var(--gray);
            color: var(--white);
            text-decoration: none;
        }

        .info-box {
            background: #fef3c7;
            border-left: 4px solid var(--primary-orange);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .info-box i {
            color: var(--primary-orange);
            margin-right: 0.5rem;
        }

        /* Points Modal Styles */
        .points-modal {
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .points-modal-content {
            background-color: white;
            border-radius: 1rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: slideUp 0.3s ease;
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

        .points-modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .points-modal-header h3 {
            margin: 0;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .points-modal-close {
            background: none;
            border: none;
            font-size: 2rem;
            color: #9ca3af;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s;
        }

        .points-modal-close:hover {
            color: #374151;
        }

        .points-modal-body {
            padding: 1.5rem;
        }

        .points-modal-body table td,
        .points-modal-body table th {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .points-modal-body table tr:last-child td {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-buttons {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .search-dropdown {
            position: relative;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--light-green);
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            padding: 0.75rem;
            border-bottom: 1px solid var(--light-gray);
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .search-result-item:hover {
            background: var(--light-green);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-code {
            font-weight: 600;
            color: var(--primary-green);
        }

        .search-result-name {
            color: var(--gray);
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg" style="padding: 0 1rem;">
            <span class="navbar-brand">
                <i class="fas fa-pen-square me-2"></i>
                Sửa Kiểm Soát Chất Lượng
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
            <span>Chỉnh Sửa</span>
        </div>

        <div class="form-card">
            <!-- Form Header -->
            <div class="form-header">
                <h1>
                    <i class="fas fa-file-import text-orange me-2"></i>
                    Cập Nhật Bản Ghi Kiểm Soát Chất Lượng
                </h1>
                <p>Sửa đổi thông tin Kiểm Soát Chất Lượng chất lượng</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                Tất cả các trường có dấu <span style="color: #dc2626;">*</span> là bắt buộc
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('audits.update', $audit) }}" novalidate id="auditForm">
                @csrf
                @method('PUT')

                <!-- Date and QC Name Section -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-calendar-alt me-2 text-green"></i>
                        Thông Tin Kiểm Soát
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="date">
                                Ngày <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ old('date', $audit->date->format('Y-m-d')) }}" required readonly>
                            @error('date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="qc_name">
                                Tên QC <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" class="form-control" id="qc_name" name="qc_name"
                                value="{{ old('qc_name', $audit->qc_name) }}" required readonly>
                            @error('qc_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 1: Thông Tin Cơ Bản -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông Tin Cơ Bản
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="greenhouse_id">
                                Nhà Kính <span style="color: #dc2626;">*</span>
                            </label>
                            <div class="search-dropdown">
                                <input type="text" class="form-control @error('greenhouse_id') is-invalid @enderror"
                                    id="greenhouse_id" placeholder="Tìm nhà kính (nhập mã hoặc tên)..."
                                    value="{{ old('greenhouse_id', $audit->greenhouse_id) }}" required autocomplete="off">
                                <div class="search-results" id="search-results"></div>
                            </div>
                            <input type="hidden" name="greenhouse_id" id="greenhouse_id_hidden"
                                value="{{ old('greenhouse_id', $audit->greenhouse_id) }}" required>
                            @error('greenhouse_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Thông Tin Nhân Sự -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-users me-2"></i>
                        Thông Tin Nhân Sự
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="picker_code">
                                Mã Picker (Mã Công Nhân) <span style="color: #dc2626;">*</span>
                            </label>
                            <div class="search-dropdown">
                                <input type="text" class="form-control @error('picker_code') is-invalid @enderror"
                                    id="picker_code" placeholder="Tìm picker..."
                                    value="{{ old('picker_code', $audit->picker_code) }}" autocomplete="off">
                                <div class="search-results" id="picker-results"></div>
                            </div>
                            <input type="hidden" name="picker_code" id="picker_code_hidden"
                                value="{{ old('picker_code', $audit->picker_code) }}">
                            @error('picker_code')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="worker_name">
                                Tên Công Nhân <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" class="form-control @error('worker_name') is-invalid @enderror"
                                id="worker_name" name="worker_name" placeholder="Tự động điền từ mã picker"
                                value="{{ old('worker_name', $audit->worker_name) }}" readonly required>
                            @error('worker_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" name="worker_name" id="worker_name_hidden"
                            value="{{ old('worker_name', $audit->worker_name) }}" required>
                        @error('worker_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Section 3: Thông Tin Hoa -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-flower me-2"></i>
                        Thông Tin Hoa
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="variety_name">
                                Giống <span style="color: #dc2626;">*</span>
                            </label>
                            <div class="search-dropdown">
                                <input type="text" class="form-control @error('variety_name') is-invalid @enderror"
                                    id="variety_name" placeholder="Tìm sản phẩm..."
                                    value="{{ old('variety_name', $audit->variety_name) }}" autocomplete="off">
                                <div class="search-results" id="variety-results"></div>
                            </div>
                            <input type="hidden" name="variety_name" id="variety_name_hidden"
                                value="{{ old('variety_name', $audit->variety_name) }}" required>
                            @error('variety_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="plot_code">
                                Đám XP (Plot) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" class="form-control @error('plot_code') is-invalid @enderror"
                                id="plot_code" name="plot_code" placeholder="VD: 27.2025"
                                value="{{ old('plot_code', $audit->plot_code) }}" pattern="\d{2}\.\d{4}"
                                title="Định dạng: WW.YYYY (VD: 27.2025)" required>
                            <small class="text-muted">Định dạng: WW.YYYY (2 số tuần + dấu chấm + 4 số năm)</small>
                            @error('plot_code')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="bag_weight">
                                Cân Nặng Túi (Gr) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('bag_weight') is-invalid @enderror"
                                id="bag_weight" name="bag_weight" placeholder="0.00" step="0.01" min="0"
                                value="{{ old('bag_weight', $audit->bag_weight) }}" required>
                            @error('bag_weight')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Số Lượng -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-calculator me-2"></i>
                        Chỉ Tiêu Chất Lượng
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="qty">
                                Số Lượng (QTY) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty"
                                name="qty" placeholder="0" min="0" value="{{ old('qty', $audit->qty) }}"
                                required>
                            @error('qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="uniformity_qty">
                                Số Lượng Đồng Đều (Uniformity QTY) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('uniformity_qty') is-invalid @enderror"
                                id="uniformity_qty" name="uniformity_qty" placeholder="0" min="0"
                                value="{{ old('uniformity_qty', $audit->uniformity_qty) }}" required>
                            @error('uniformity_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="urc_weight_qty">
                                Trọng Lượng URC (URC Weight) (Gr) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('urc_weight_qty') is-invalid @enderror"
                                id="urc_weight_qty" name="urc_weight_qty" placeholder="0.00" step="0.01"
                                min="0" value="{{ old('urc_weight_qty', $audit->urc_weight_qty) }}" required>
                            @error('urc_weight_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="length_qty">
                                Ngắn Dài (Length) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('length_qty') is-invalid @enderror"
                                id="length_qty" name="length_qty" placeholder="0" min="0"
                                value="{{ old('length_qty', $audit->length_qty) }}" required>
                            @error('length_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="damaged_qty">
                                Số Lượng Hư Hỏng (Damaged QTY) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('damaged_qty') is-invalid @enderror"
                                id="damaged_qty" name="damaged_qty" placeholder="0" min="0"
                                value="{{ old('damaged_qty', $audit->damaged_qty) }}" required>
                            @error('damaged_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="leaf_burn_qty">
                                Cháy Lá (Leaf Burn) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('leaf_burn_qty') is-invalid @enderror"
                                id="leaf_burn_qty" name="leaf_burn_qty" placeholder="0" min="0"
                                value="{{ old('leaf_burn_qty', $audit->leaf_burn_qty) }}" required>
                            @error('leaf_burn_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="yellow_spot_qty">
                                Đốm Vàng (Yellow Spot) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('yellow_spot_qty') is-invalid @enderror"
                                id="yellow_spot_qty" name="yellow_spot_qty" min="0"
                                value="{{ old('yellow_spot_qty', $audit->yellow_spot_qty) }}" required>
                            @error('yellow_spot_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="wooden_qty">
                                Xơ (Wooden) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('wooden_qty') is-invalid @enderror"
                                id="wooden_qty" name="wooden_qty" min="0"
                                value="{{ old('wooden_qty', $audit->wooden_qty) }}" required>
                            @error('wooden_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="dirty_qty">
                                Bẩn (Dirty) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('dirty_qty') is-invalid @enderror"
                                id="dirty_qty" name="dirty_qty" min="0"
                                value="{{ old('dirty_qty', $audit->dirty_qty) }}" required>
                            @error('dirty_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="wrong_label_qty">
                                Sai Nhãn (Wrong Label) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('wrong_label_qty') is-invalid @enderror"
                                id="wrong_label_qty" name="wrong_label_qty" min="0"
                                value="{{ old('wrong_label_qty', $audit->wrong_label_qty) }}" required>
                            @error('wrong_label_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="pest_disease_qty">
                                Sâu Bệnh (Pest Disease) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" class="form-control @error('pest_disease_qty') is-invalid @enderror"
                                id="pest_disease_qty" name="pest_disease_qty" min="0"
                                value="{{ old('pest_disease_qty', $audit->pest_disease_qty) }}" required>
                            @error('pest_disease_qty')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="total_points_display"
                                style="display: flex; align-items: center; gap: 0.5rem;">
                                Tổng Điểm (Total Points)
                                <button type="button" class="btn-hint" onclick="showPointsModal()"
                                    style="background: none; border: none; cursor: pointer; color: #059669; padding: 0; font-size: 1.2rem;"
                                    title="Xem cách tính điểm">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </label>
                            <input type="text" class="form-control" id="total_points_display"
                                value="{{ old('total_points', $audit->total_points ?? 0) }}" readonly
                                style="background-color: #f3f4f6; font-weight: 600; font-size: 1.1rem; color: #059669;">
                            <small class="text-muted">Tự động tính dựa trên cấu hình điểm</small>
                        </div>

                        <div class="info-box">
                            <i class="bi bi-info-circle"></i>
                            <strong>Tổng điểm sẽ được tính tự động</strong> dựa trên các chỉ tiêu bạn nhập.
                        </div>
                    </div>


                </div>



                <!-- Points Calculation Modal -->
                <div id="pointsModal" class="points-modal" style="display: none;">
                    <div class="points-modal-content">
                        <div class="points-modal-header">
                            <h3><i class="fas fa-calculator"></i> Cách Tính Tổng Điểm</h3>
                            <button type="button" class="points-modal-close"
                                onclick="closePointsModal()">&times;</button>
                        </div>
                        <div class="points-modal-body">
                            <p style="margin-bottom: 1rem; color: #6b7280;">
                                Tổng điểm được tính bằng công thức:
                            </p>
                            <div
                                style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; font-family: monospace;">
                                <strong>Tổng Điểm = Σ (Số Lượng × Điểm Cấu Hình)</strong>
                            </div>
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1rem;">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th style="padding: 0.75rem; text-align: left; border-bottom: 2px solid #e5e7eb;">
                                            Chỉ Tiêu
                                        </th>
                                        <th
                                            style="padding: 0.75rem; text-align: center; border-bottom: 2px solid #e5e7eb;">
                                            Điểm/Đơn Vị</th>
                                    </tr>
                                </thead>
                                <tbody id="pointsConfigTable">
                                    <tr>
                                        <td colspan="2" style="padding: 1rem; text-align: center; color: #9ca3af;">Đang
                                            tải...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                style="background: #ecfdf5; border-left: 4px solid #059669; padding: 1rem; border-radius: 0.25rem;">
                                <strong style="color: #059669;">Ví dụ:</strong>
                                <p style="margin: 0.5rem 0 0 0; color: #374151;">
                                    Nếu bạn nhập: Cháy Lá = 5, Đốm Vàng = 3 và mỗi chỉ tiêu có điểm = 1<br>
                                    → Tổng Điểm = (5 × 1) + (3 × 1) = <strong>8 điểm</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="form-buttons">
                    <a href="{{ route('audits.show', $audit) }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i>
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save"></i>
                        Cập Nhật Bản Ghi
                    </button>
                </div>
            </form>
        </div>
        </div>


    </main>

    <script>
        // Greenhouse Search Functionality
        const greenHouseInput = document.getElementById('greenhouse_id');
        const greenHouseHiddenInput = document.getElementById('greenhouse_id_hidden');
        const searchResults = document.getElementById('search-results');
        let allGreenhouses = [];
        let selectedIndex = -1;

        // Load greenhouses on page load
        async function loadGreenhouses() {
            try {
                console.log('Bắt đầu load greenhouses...');
                const response = await fetch('{{ url('/api/greenhouses/search') }}');
                console.log('Response status:', response.status);
                allGreenhouses = await response.json();
                console.log('Đã load được', allGreenhouses.length, 'greenhouses:', allGreenhouses);
            } catch (error) {
                console.error('Lỗi khi load greenhouses:', error);
            }
        }

        // Load greenhouses immediately
        loadGreenhouses();

        // Show all greenhouses on focus
        greenHouseInput.addEventListener('focus', function() {
            if (allGreenhouses.length > 0) {
                displayGreenhouses(allGreenhouses);
            }
        });

        // Search functionality
        greenHouseInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            selectedIndex = -1;

            if (searchTerm.length === 0) {
                displayGreenhouses(allGreenhouses);
                return;
            }

            const filtered = allGreenhouses.filter(gh =>
                gh.greenhouse_code.toLowerCase().includes(searchTerm) ||
                gh.greenhouse_name.toLowerCase().includes(searchTerm) ||
                gh.farm_name.toLowerCase().includes(searchTerm)
            );

            displayGreenhouses(filtered);
        });

        function displayGreenhouses(greenhouses) {
            if (greenhouses.length === 0) {
                searchResults.classList.remove('show');
                return;
            }

            searchResults.innerHTML = greenhouses.map(gh => `
                        <div class="search-result-item" data-code="${gh.greenhouse_code}" data-name="${gh.greenhouse_name}">
                            <div class="search-result-code">${gh.greenhouse_code}</div>
                            <div class="search-result-name">${gh.greenhouse_name} - ${gh.farm_name}</div>
                        </div>
                    `).join('');

            // Add click event listeners
            searchResults.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectGreenhouse(this.dataset.code, this.dataset.name);
                });
            });

            searchResults.classList.add('show');
        }

        // Keyboard navigation
        greenHouseInput.addEventListener('keydown', function(e) {
            const items = searchResults.querySelectorAll('.search-result-item');

            if (!searchResults.classList.contains('show') || items.length === 0) {
                return;
            }

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    highlightItem(items);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    highlightItem(items);
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0) {
                        const item = items[selectedIndex];
                        const code = item.dataset.code;
                        selectGreenhouse(code, item.querySelector('.search-result-name').textContent);
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    searchResults.classList.remove('show');
                    break;
            }
        });

        function highlightItem(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.style.backgroundColor = '#d1fae5';
                    item.style.fontWeight = '500';
                } else {
                    item.style.backgroundColor = '';
                    item.style.fontWeight = '';
                }
            });
        }

        function selectGreenhouse(code, name) {
            greenHouseInput.value = code;
            greenHouseHiddenInput.value = code;
            searchResults.classList.remove('show');
            selectedIndex = -1;
        }

        // Close search results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-dropdown')) {
                searchResults.classList.remove('show');
            }
        });

        // Load greenhouses on page load
        loadGreenhouses();

        // Picker Search Functionality (auto-fill worker name)
        const pickerCodeInput = document.getElementById('picker_code');
        const pickerCodeHidden = document.getElementById('picker_code_hidden');
        const pickerResults = document.getElementById('picker-results');
        const workerNameInput = document.getElementById('worker_name');
        let pickerSelectedIndex = -1;
        let allWorkers = []; // Initialize workers array first

        // Load workers for picker dropdown
        async function loadWorkers() {
            try {
                const response = await fetch('{{ route('api.workers.search') }}');
                allWorkers = await response.json();
            } catch (error) {
                console.error('Error loading workers:', error);
            }
        }

        // Load workers immediately
        loadWorkers();

        // Show all pickers on focus
        pickerCodeInput.addEventListener('focus', function() {
            if (allWorkers.length > 0) {
                displayPickers(allWorkers);
            }
        });

        // Picker search functionality
        pickerCodeInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            pickerSelectedIndex = -1;

            if (searchTerm.length === 0) {
                displayPickers(allWorkers);
                return;
            }

            const filtered = allWorkers.filter(worker =>
                worker.fullname.toLowerCase().includes(searchTerm) ||
                worker.email.toLowerCase().includes(searchTerm) ||
                (worker.user_code && worker.user_code.toLowerCase().includes(searchTerm))
            );

            displayPickers(filtered);
        });

        function displayPickers(workers) {
            if (workers.length === 0) {
                pickerResults.classList.remove('show');
                return;
            }

            pickerResults.innerHTML = workers.map(worker => `
                <div class="search-result-item" data-code="${worker.user_code}" data-name="${worker.fullname}">
                    <div class="search-result-code">${worker.user_code}</div>
                    <div class="search-result-name">${worker.fullname} - ${worker.email}</div>
                </div>
            `).join('');

            // Add click event listeners
            pickerResults.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectPicker(this.dataset.code, this.dataset.name);
                });
            });

            pickerResults.classList.add('show');
        }

        // Picker keyboard navigation
        pickerCodeInput.addEventListener('keydown', function(e) {
            const items = pickerResults.querySelectorAll('.search-result-item');

            if (!pickerResults.classList.contains('show') || items.length === 0) {
                return;
            }

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    pickerSelectedIndex = Math.min(pickerSelectedIndex + 1, items.length - 1);
                    highlightPickerItem(items);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    pickerSelectedIndex = Math.max(pickerSelectedIndex - 1, -1);
                    highlightPickerItem(items);
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (pickerSelectedIndex >= 0) {
                        items[pickerSelectedIndex].click();
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    pickerResults.classList.remove('show');
                    break;
            }
        });

        function highlightPickerItem(items) {
            items.forEach((item, index) => {
                if (index === pickerSelectedIndex) {
                    item.style.backgroundColor = '#d1fae5';
                    item.style.fontWeight = '500';
                } else {
                    item.style.backgroundColor = '';
                    item.style.fontWeight = '';
                }
            });
        }

        function selectPicker(pickerCode, pickerName) {
            pickerCodeInput.value = pickerCode;
            pickerCodeHidden.value = pickerCode;
            workerNameInput.value = pickerName;
            pickerResults.classList.remove('show');
            pickerSelectedIndex = -1;
        }

        // Close picker results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-dropdown') || !event.target.closest('#picker_code')) {
                pickerResults.classList.remove('show');
            }
        });

        // Product/Variety Search Functionality
        const varietyInput = document.getElementById('variety_name');
        const varietyHiddenInput = document.getElementById('variety_name_hidden');
        const varietyResults = document.getElementById('variety-results');
        let allProducts = []; // Initialize products array first
        let varietySelectedIndex = -1;

        // Load products on page load
        async function loadProducts() {
            try {
                const response = await fetch('{{ url('/api/products/search') }}');
                allProducts = await response.json();
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        // Load products immediately
        loadProducts();

        // Show all products on focus
        varietyInput.addEventListener('focus', function() {
            if (allProducts.length > 0) {
                displayProducts(allProducts);
            }
        });

        // Product search functionality
        varietyInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            varietySelectedIndex = -1;

            if (searchTerm.length === 0) {
                displayProducts(allProducts);
                return;
            }

            const filtered = allProducts.filter(product =>
                product.product_code.toLowerCase().includes(searchTerm) ||
                product.product_name.toLowerCase().includes(searchTerm) ||
                (product.variety && product.variety.toLowerCase().includes(searchTerm))
            );

            displayProducts(filtered);
        });

        function displayProducts(products) {
            if (products.length === 0) {
                varietyResults.classList.remove('show');
                return;
            }

            varietyResults.innerHTML = products.map(product => `
                <div class="search-result-item" data-variety="${product.product_name}">
                    <div class="search-result-code">${product.product_code}</div>
                    <div class="search-result-name">${product.product_name}${product.variety ? ' (' + product.variety + ')' : ''}</div>
                </div>
            `).join('');

            // Add click event listeners
            varietyResults.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectVariety(this.dataset.variety);
                });
            });

            varietyResults.classList.add('show');
        }

        // Product keyboard navigation
        varietyInput.addEventListener('keydown', function(e) {
            const items = varietyResults.querySelectorAll('.search-result-item');

            if (!varietyResults.classList.contains('show') || items.length === 0) {
                return;
            }

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    varietySelectedIndex = Math.min(varietySelectedIndex + 1, items.length - 1);
                    highlightVarietyItem(items);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    varietySelectedIndex = Math.max(varietySelectedIndex - 1, -1);
                    highlightVarietyItem(items);
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (varietySelectedIndex >= 0) {
                        const item = items[varietySelectedIndex];
                        const variety = item.dataset.variety;
                        selectVariety(variety);
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    varietyResults.classList.remove('show');
                    break;
            }
        });

        function highlightVarietyItem(items) {
            items.forEach((item, index) => {
                if (index === varietySelectedIndex) {
                    item.style.backgroundColor = '#d1fae5';
                    item.style.fontWeight = '500';
                } else {
                    item.style.backgroundColor = '';
                    item.style.fontWeight = '';
                }
            });
        }

        function selectVariety(variety) {
            varietyInput.value = variety;
            varietyHiddenInput.value = variety;
            varietyResults.classList.remove('show');
            varietySelectedIndex = -1;
        }

        // Close variety results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-dropdown')) {
                searchResults.classList.remove('show');
                pickerResults.classList.remove('show');
                varietyResults.classList.remove('show');
            }
        });

        // Plot Code Format Validation (WW.YYYY)
        const plotCodeInput = document.getElementById('plot_code');

        plotCodeInput.addEventListener('input', function() {
            validatePlotCode(this);
        });

        plotCodeInput.addEventListener('blur', function() {
            validatePlotCode(this);
        });

        function validatePlotCode(input) {
            const value = input.value.trim();
            const pattern = /^\d{2}\.\d{4}$/;

            // Remove any existing error message
            let errorDiv = input.parentElement.querySelector('.plot-code-error');

            if (value === '') {
                // If empty, remove error (will be caught by required validation on submit)
                if (errorDiv) errorDiv.remove();
                input.style.borderColor = '';
                return;
            }

            if (!pattern.test(value)) {
                // Add error styling
                input.style.borderColor = '#dc2626';

                // Create or update error message
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'plot-code-error';
                    errorDiv.style.color = '#dc2626';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '0.25rem';
                    input.parentElement.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Định dạng không đúng! Phải là WW.YYYY (VD: 27.2025)';
            } else {
                // Valid format
                input.style.borderColor = '#10b981';
                if (errorDiv) errorDiv.remove();
            }
        }

        // Calculate Total Points Real-time
        let pointsConfig = {};
        let pointsConfigFull = {}; // Lưu đầy đủ thông tin (display_name, points)
        const totalPointsDisplay = document.getElementById('total_points_display');

        // Load points configuration
        async function loadPointsConfig() {
            try {
                const response = await fetch('{{ url('/api/audit-points-config') }}');
                pointsConfigFull = await response.json();
                // Tạo object chỉ chứa field_name => points để tính toán
                pointsConfig = {};
                Object.keys(pointsConfigFull).forEach(key => {
                    pointsConfig[key] = pointsConfigFull[key].points;
                });
                console.log('Đã load cấu hình điểm:', pointsConfig);
                calculateTotalPoints(); // Calculate on load
            } catch (error) {
                console.error('Lỗi khi load cấu hình điểm:', error);
            }
        }

        // Calculate total points
        function calculateTotalPoints() {
            const qty = parseInt(document.getElementById('qty').value) || 0;
            const uniformityQty = parseInt(document.getElementById('uniformity_qty').value) || 0;
            const urcWeightQty = parseFloat(document.getElementById('urc_weight_qty').value) || 0;
            const lengthQty = parseInt(document.getElementById('length_qty').value) || 0;
            const damagedQty = parseInt(document.getElementById('damaged_qty').value) || 0;
            const leafBurn = parseInt(document.getElementById('leaf_burn_qty').value) || 0;
            const yellowSpot = parseInt(document.getElementById('yellow_spot_qty').value) || 0;
            const wooden = parseInt(document.getElementById('wooden_qty').value) || 0;
            const dirty = parseInt(document.getElementById('dirty_qty').value) || 0;
            const wrongLabel = parseInt(document.getElementById('wrong_label_qty').value) || 0;
            const pestDisease = parseInt(document.getElementById('pest_disease_qty').value) || 0;

            const totalPoints =
                (qty * (pointsConfig.qty || 0)) +
                (uniformityQty * (pointsConfig.uniformity_qty || 0)) +
                (urcWeightQty * (pointsConfig.urc_weight_qty || 0)) +
                (lengthQty * (pointsConfig.length_qty || 0)) +
                (damagedQty * (pointsConfig.damaged_qty || 0)) +
                (leafBurn * (pointsConfig.leaf_burn_qty || 0)) +
                (yellowSpot * (pointsConfig.yellow_spot_qty || 0)) +
                (wooden * (pointsConfig.wooden_qty || 0)) +
                (dirty * (pointsConfig.dirty_qty || 0)) +
                (wrongLabel * (pointsConfig.wrong_label_qty || 0)) +
                (pestDisease * (pointsConfig.pest_disease_qty || 0));

            totalPointsDisplay.value = totalPoints;
            console.log('Tổng điểm:', totalPoints);
        }

        // Add event listeners to all quality metric inputs
        const qualityInputs = [
            'qty',
            'uniformity_qty',
            'urc_weight_qty',
            'length_qty',
            'damaged_qty',
            'leaf_burn_qty',
            'yellow_spot_qty',
            'wooden_qty',
            'dirty_qty',
            'wrong_label_qty',
            'pest_disease_qty'
        ];

        qualityInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', calculateTotalPoints);
            }
        });

        // Load configuration on page load
        loadPointsConfig();

        // Points Modal Functions
        function showPointsModal() {
            const modal = document.getElementById('pointsModal');
            modal.style.display = 'flex';

            // Populate the points config table
            const tableBody = document.getElementById('pointsConfigTable');
            if (Object.keys(pointsConfigFull).length > 0) {
                let html = '';
                for (const [field, config] of Object.entries(pointsConfigFull)) {
                    const displayName = config.display_name || field;
                    const points = config.points || 0;
                    html += `
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">${displayName}</td>
                            <td style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #059669;">${points}</td>
                        </tr>
                    `;
                }
                tableBody.innerHTML = html;
            } else {
                tableBody.innerHTML =
                    '<tr><td colspan="2" style="padding: 1rem; text-align: center; color: #9ca3af;">Chưa có cấu hình điểm</td></tr>';
            }
        }

        function closePointsModal() {
            const modal = document.getElementById('pointsModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('pointsModal');
            if (event.target === modal) {
                closePointsModal();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePointsModal();
            }
        });
    </script>
@endsection
