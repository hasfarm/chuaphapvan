@extends('layouts.app')

@section('title', 'Chi Tiết Kiểm Soát Chất Lượng - chuaphapvan QC')

@section('styles')
    <style>
        .navbar {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
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
            color: var(--primary-green);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .detail-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .detail-header {
            margin-bottom: 2rem;
        }

        .detail-header h1 {
            font-size: 1.75rem;
            margin: 0;
        }

        .action-buttons {
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
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
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
            text-decoration: none;
            color: var(--white);
        }

        .btn-back {
            background: var(--gray);
            color: var(--white);
        }

        .btn-back:hover {
            background: var(--dark-light);
            text-decoration: none;
            color: var(--white);
        }

        .info-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-green);
            margin-bottom: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid var(--primary-green);
        }

        .info-label {
            font-weight: 600;
            color: var(--gray);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--dark);
            font-weight: 500;
        }

        .info-value.highlight {
            color: var(--primary-green);
            font-weight: 700;
        }

        .badge {
            display: inline-block;
            padding: 0.4rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--white);
        }

        .badge-success {
            background: var(--primary-green);
        }

        .badge-warning {
            background: var(--primary-orange);
        }

        .badge-danger {
            background: #ef4444;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat {
            background: linear-gradient(135deg, var(--light-green), rgba(16, 185, 129, 0.1));
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            border-left: 4px solid var(--primary-green);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-green);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 0.5rem;
        }

        .metadata {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 2rem;
            font-size: 0.85rem;
            color: var(--gray);
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .detail-card {
                padding: 1.5rem;
            }

            .detail-header {
                flex-direction: column;
            }

            .detail-header h1 {
                font-size: 1.5rem;
            }

            .action-buttons {
                width: 100%;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg" style="padding: 0 1rem;">
            <span class="navbar-brand">
                <i class="fas fa-eye me-2"></i>
                Chi Tiết Kiểm Soát Chất Lượng
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
            <span>Chi Tiết</span>
        </div>

        <!-- Detail Card -->
        <div class="detail-card">
            <!-- Header -->
            <div class="detail-header">
                <h1>
                    <i class="fas fa-file-alt text-green me-2"></i>
                    Biên Bản Kiểm Soát Chất Lượng
                </h1>
            </div>

            <!-- Thông Tin Kiểm Soát -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-calendar-alt me-2 text-green"></i>
                    Thông Tin Kiểm Soát
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Ngày</div>
                        <div class="info-value">{{ $audit->date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tên QC</div>
                        <div class="info-value">{{ $audit->qc_name }}</div>
                    </div>
                </div>
            </div>

            <!-- Thông Tin Cơ Bản -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle me-2 text-green"></i>
                    Thông Tin Cơ Bản
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nhà Kính</div>
                        <div class="info-value">{{ $audit->greenhouse_id }}</div>
                    </div>
                </div>
            </div>

            <!-- Thông Tin Nhân Sự -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-users me-2 text-green"></i>
                    Thông Tin Nhân Sự
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Mã Picker (Mã Công Nhân)</div>
                        <div class="info-value">{{ $audit->picker_code }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tên Công Nhân</div>
                        <div class="info-value">{{ $audit->worker_name }}</div>
                    </div>
                </div>
            </div>

            <!-- Thông Tin Hoa -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-flower me-2 text-green"></i>
                    Thông Tin Hoa
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Giống</div>
                        <div class="info-value">{{ $audit->variety_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Đám XP (Plot)</div>
                        <div class="info-value">{{ $audit->plot_code }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Cân Nặng Túi (Gr)</div>
                        <div class="info-value">{{ number_format($audit->bag_weight, 2) }} gr</div>
                    </div>
                </div>
            </div>

            <!-- Chỉ Tiêu Chất Lượng -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-star me-2 text-green"></i>
                    Chỉ Tiêu Chất Lượng
                </h3>
                <div class="stats-row">
                    <div class="stat">
                        <div class="stat-label">Số Lượng (QTY)</div>
                        <div class="stat-value">{{ number_format($audit->qty) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Số Lượng Đồng Đều (Uniformity QTY)</div>
                        <div class="stat-value">{{ number_format($audit->uniformity_qty) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">TL Ngọn (URC Weight)</div>
                        <div class="stat-value">{{ number_format($audit->urc_weight_qty) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Ngắn Dài (Length)</div>
                        <div class="stat-value">{{ number_format($audit->length_qty) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Số Lượng Hư Hỏng (Damaged QTY)</div>
                        <div class="stat-value">{{ number_format($audit->damaged_qty) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Cháy Lá (Leaf Burn)</div>
                        <div class="stat-value">{{ $audit->leaf_burn_qty }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Đốm Vàng (Yellow Spot)</div>
                        <div class="stat-value">{{ $audit->yellow_spot_qty }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Xơ (Wooden)</div>
                        <div class="stat-value">{{ $audit->wooden_qty }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Bẩn (Dirty)</div>
                        <div class="stat-value">{{ $audit->dirty_qty }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Sai Nhãn (Wrong Label)</div>
                        <div class="stat-value">{{ $audit->wrong_label_qty }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Sâu Bệnh (Pest Disease)</div>
                        <div class="stat-value">{{ $audit->pest_disease_qty }}</div>
                    </div>
                    <div class="stat"
                        style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-left: 6px solid #f59e0b; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3), 0 2px 4px -1px rgba(245, 158, 11, 0.2);">
                        <div class="stat-label"
                            style="color: #92400e; font-weight: 700; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-star me-1"></i>TỔNG ĐIỂM (TOTAL POINTS)
                        </div>
                        <div class="stat-value" style="color: #f59e0b; font-size: 2rem; font-weight: 800;">
                            {{ $audit->total_points }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                {{-- <a href="{{ route('audits.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Quay Lại
                </a> --}}
                <a href="{{ route('audits.edit', $audit) }}" class="btn btn-edit">
                    <i class="fas fa-edit"></i>
                    Sửa
                </a>
                <form method="POST" action="{{ route('audits.destroy', $audit) }}" style="display: inline;"
                    onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">
                        <i class="fas fa-trash"></i>
                        Xóa
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection
