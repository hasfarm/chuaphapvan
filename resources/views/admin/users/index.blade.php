@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-users" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Quản Lý Người Dùng
        </h1>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: flex-end;">
            <button type="button" class="btn btn-primary" onclick="openImportModal()">
                <i class="fas fa-file-excel"></i>
                Import Excel
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm Người Dùng
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div
        style="background: var(--white); padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Tìm kiếm theo tên, họ tên hoặc email..."
                value="{{ request('search') }}"
                style="flex: 1; min-width: 200px; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">

            <select name="role_id" style="padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                <option value="">-- Tất cả vai trò --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Tìm Kiếm
            </button>
        </form>
    </div>

    @if ($errors->has('import') || session()->has('error'))
        <div
            style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <strong><i class="fas fa-exclamation-circle"></i> Lỗi:</strong>
            {{ $errors->first('import') ?? session()->get('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div
            style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <strong><i class="fas fa-check-circle"></i> Thành công:</strong>
            {{ session()->get('success') }}
        </div>
    @endif

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar"
        style="display: none; background: var(--white); padding: 1rem 1.5rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 1.5rem; align-items: center; gap: 1rem;">
        <div style="flex: 1;">
            <strong style="color: var(--dark);"><span id="selectedCount">0</span> người dùng được chọn</strong>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <select id="bulkRoleSelect"
                style="padding: 0.5rem 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem; font-size: 0.9rem;">
                <option value="">-- Chọn vai trò --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="button" onclick="bulkUpdateRole()" class="btn btn-secondary"
                style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                <i class="fas fa-user-tag"></i>
                Cập Nhật Vai Trò
            </button>
            <button type="button" onclick="bulkDelete()" class="btn btn-danger"
                style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                <i class="fas fa-trash"></i>
                Xóa
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container desktop-table">
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"
                            style="cursor: pointer; width: 18px; height: 18px;">
                    </th>
                    <th>Tên</th>
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Vai Trò</th>
                    <th>Ngày Tạo</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}"
                                onchange="toggleBulkActions()" style="cursor: pointer; width: 18px; height: 18px;">
                        </td>
                        <td>{{ $user->user_code }}</td>
                        <td>{{ $user->fullname ?? '—' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span
                                class="badge {{ $user->isAdmin() ? 'badge-danger' : ($user->isModerator() ? 'badge-warning' : 'badge-success') }}">
                                {{ $user->role->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary"
                                    title="Chỉnh Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa?');" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            Không tìm thấy người dùng nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="mobile-cards">
        @forelse ($users as $user)
            <div class="mobile-card-wrapper">
                <div class="mobile-card" data-user-id="{{ $user->id }}">
                    <div class="mobile-card-content">
                        <div class="mobile-card-header">
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                                <input type="checkbox" class="user-checkbox-mobile" value="{{ $user->id }}"
                                    onchange="toggleBulkActions()"
                                    style="cursor: pointer; width: 20px; height: 20px; flex-shrink: 0;">
                                <div style="flex: 1; min-width: 0;">
                                    <h3 class="mobile-card-title">{{ $user->user_code }}</h3>
                                    <div class="mobile-card-subtitle">{{ $user->email }}</div>
                                </div>
                            </div>
                            <span
                                class="badge {{ $user->isAdmin() ? 'badge-danger' : ($user->isModerator() ? 'badge-warning' : 'badge-success') }}"
                                style="flex-shrink: 0;">
                                {{ $user->role->name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="mobile-card-grid">
                            <div class="mobile-card-item">
                                <div class="mobile-card-label">Họ Tên</div>
                                <div class="mobile-card-value">{{ $user->fullname ?? '—' }}</div>
                            </div>
                            <div class="mobile-card-item">
                                <div class="mobile-card-label">Ngày Tạo</div>
                                <div class="mobile-card-value">{{ $user->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        <div class="swipe-hint">
                            <i class="fas fa-chevron-left"></i>
                            Vuốt để xem tùy chọn
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('admin.users.edit', $user) }}" class="mobile-action-btn mobile-action-edit"
                            onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                            Sửa
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
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
        @empty
            <div
                style="text-align: center; color: var(--gray); padding: 3rem 1rem; background: var(--white); border-radius: 1rem;">
                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                <p style="margin: 0;">Không tìm thấy người dùng nào</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{ $users->links('pagination::custom') }}

    <!-- Import Modal -->
    <div id="importModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center; overflow: auto;">
        <div
            style="background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); max-width: 450px; width: 90%; padding: 2rem; animation: slideIn 0.3s ease-out; margin: auto;">
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--dark);">
                    <i class="fas fa-file-excel" style="color: #166534; margin-right: 0.5rem;"></i>
                    Import Khách Hàng từ Excel
                </h2>
                <button type="button" onclick="closeImportModal()"
                    style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--gray);">
                    ×
                </button>
            </div>

            <!-- Info Message -->
            <div
                style="background: #eff6ff; border-left: 4px solid #0284c7; padding: 0.75rem 1rem; margin-bottom: 1.5rem; border-radius: 0.25rem;">
                <p style="margin: 0; color: #0c4a6e; font-size: 0.95rem;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    <strong>Hướng dẫn:</strong> Tải file mẫu Excel, điền thông tin và upload lại.
                </p>
            </div>

            <!-- Download Template Button -->
            <a href="{{ route('admin.users.template') }}" class="btn btn-secondary"
                style="display: block; text-align: center; margin-bottom: 1.5rem; text-decoration: none;">
                <i class="fas fa-file-download"></i>
                Tải Template
            </a>

            <!-- File Input Form -->
            <form id="importForm" method="POST" action="{{ route('admin.users.import') }}"
                enctype="multipart/form-data" onsubmit="showImportLoading()">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                        Chọn file Excel/CSV <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="position: relative; border: 2px dashed var(--light-gray); border-radius: 0.5rem; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s ease;"
                        id="fileDropZone">
                        <i class="fas fa-cloud-upload-alt"
                            style="font-size: 2rem; color: var(--primary-green); margin-bottom: 0.5rem; display: block;"></i>
                        <p style="margin: 0.5rem 0; color: var(--dark); font-weight: 500;">Kéo file vào đây hoặc click để
                            chọn</p>
                        <p style="margin: 0; color: var(--gray); font-size: 0.85rem;">Chỉ chấp nhận .xlsx, .xls, .csv</p>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required style="display: none;"
                            id="fileInput">
                    </div>
                    <p id="fileName"
                        style="margin: 0.5rem 0 0 0; color: var(--primary-green); font-size: 0.9rem; display: none;"></p>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 1rem;">
                    <button type="button" onclick="closeImportModal()" class="btn btn-secondary" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Đóng
                    </button>
                    <button type="submit" id="importSubmitBtn" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-upload"></i>
                        Import Ngay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="importLoadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.8); z-index: 9999; justify-content: center; align-items: center;">
        <div
            style="background: white; border-radius: 1rem; padding: 2.5rem; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <!-- Spinner -->
            <div style="margin-bottom: 1.5rem;">
                <div class="loading-spinner"></div>
            </div>

            <!-- Title -->
            <h3 style="margin: 0 0 0.5rem 0; color: var(--dark); font-size: 1.25rem; font-weight: 600;">
                <i class="fas fa-upload" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
                Đang Import Dữ Liệu
            </h3>

            <!-- Description -->
            <p style="margin: 0 0 1.5rem 0; color: var(--gray); font-size: 0.95rem;">
                Vui lòng đợi, hệ thống đang xử lý file của bạn...
            </p>

            <!-- Progress Bar -->
            <div style="background: #e5e7eb; height: 8px; border-radius: 999px; overflow: hidden; margin-bottom: 1rem;">
                <div class="progress-bar-animated"
                    style="background: linear-gradient(90deg, var(--primary-green), #10b981); height: 100%; width: 0%; border-radius: 999px;">
                </div>
            </div>

            <!-- Warning -->
            <p style="margin: 0; color: #dc2626; font-size: 0.85rem; font-weight: 500;">
                <i class="fas fa-exclamation-triangle"></i>
                Không đóng trình duyệt cho đến khi hoàn tất!
            </p>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #importModal.show {
            display: flex !important;
        }

        #fileDropZone:hover {
            border-color: var(--primary-green);
            background: #f0fdf4;
        }

        #fileDropZone.dragover {
            border-color: var(--primary-green);
            background: #dcfce7;
        }

        /* Mobile Cards Styles */
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
            gap: 0.5rem;
        }

        .mobile-card-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark);
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mobile-card-subtitle {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

        /* Loading Spinner */
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f4f6;
            border-top: 5px solid var(--primary-green);
            border-radius: 50%;
            margin: 0 auto;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Animated Progress Bar */
        .progress-bar-animated {
            animation: progressAnimation 2s ease-in-out infinite;
        }

        @keyframes progressAnimation {
            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .desktop-table {
                display: none !important;
            }

            .mobile-cards {
                display: block !important;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .page-header>div {
                width: 100%;
            }

            .page-header .btn {
                width: 100%;
                justify-content: center;
            }

            #bulkActionsBar {
                flex-direction: column;
                align-items: stretch !important;
            }

            #bulkActionsBar>div:last-child {
                flex-direction: column;
            }

            #bulkActionsBar .btn,
            #bulkActionsBar select {
                width: 100%;
            }
        }
    </style>

    <script>
        function openImportModal() {
            document.getElementById('importModal').classList.add('show');
            document.getElementById('importModal').style.display = 'flex';
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.remove('show');
            document.getElementById('importModal').style.display = 'none';
            document.getElementById('fileInput').value = '';
            document.getElementById('fileName').style.display = 'none';
        }

        function showImportLoading() {
            // Close import modal
            closeImportModal();

            // Show loading overlay
            const loadingOverlay = document.getElementById('importLoadingOverlay');
            loadingOverlay.style.display = 'flex';

            // Disable the submit button to prevent double submission
            const submitBtn = document.getElementById('importSubmitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            }

            // Return true to allow form submission
            return true;
        }

        // File input handling - wrapped to ensure DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const fileDropZone = document.getElementById('fileDropZone');
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileName');

            if (!fileDropZone || !fileInput || !fileNameDisplay) {
                console.error('File input elements not found');
                return;
            }

            // File input click
            fileDropZone.addEventListener('click', () => fileInput.click());

            // File selection
            fileInput.addEventListener('change', (e) => {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    fileNameDisplay.textContent = '✓ ' + fileName;
                    fileNameDisplay.style.display = 'block';
                    fileNameDisplay.style.color = 'var(--primary-green)';
                    fileNameDisplay.style.fontWeight = '600';
                } else {
                    fileNameDisplay.style.display = 'none';
                }
            });

            // Drag and drop
            fileDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileDropZone.classList.add('dragover');
            });

            fileDropZone.addEventListener('dragleave', () => {
                fileDropZone.classList.remove('dragover');
            });

            fileDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                fileDropZone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    const event = new Event('change', {
                        bubbles: true
                    });
                    fileInput.dispatchEvent(event);
                }
            });
        });

        // Close modal when clicking outside
        document.getElementById('importModal').addEventListener('click', (e) => {
            if (e.target.id === 'importModal') {
                closeImportModal();
            }
        });

        // Bulk Actions JavaScript
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            toggleBulkActions();
        }

        function toggleBulkActions() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked, .user-checkbox-mobile:checked');
            const bulkBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');

            selectedCount.textContent = checkboxes.length;

            if (checkboxes.length > 0) {
                bulkBar.style.display = 'flex';
            } else {
                bulkBar.style.display = 'none';
            }

            // Update select all checkbox state
            const allCheckboxes = document.querySelectorAll('.user-checkbox, .user-checkbox-mobile');
            selectAll.checked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
            selectAll.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
        }

        function getSelectedUserIds() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked, .user-checkbox-mobile:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        function bulkUpdateRole() {
            const roleId = document.getElementById('bulkRoleSelect').value;
            const userIds = getSelectedUserIds();

            if (!roleId) {
                alert('Vui lòng chọn vai trò!');
                return;
            }

            if (userIds.length === 0) {
                alert('Vui lòng chọn ít nhất một người dùng!');
                return;
            }

            if (!confirm(`Bạn có chắc muốn cập nhật vai trò cho ${userIds.length} người dùng?`)) {
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.users.bulk-update-role') }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const roleInput = document.createElement('input');
            roleInput.type = 'hidden';
            roleInput.name = 'role_id';
            roleInput.value = roleId;
            form.appendChild(roleInput);

            userIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function bulkDelete() {
            const userIds = getSelectedUserIds();

            if (userIds.length === 0) {
                alert('Vui lòng chọn ít nhất một người dùng!');
                return;
            }

            if (!confirm(`Bạn có chắc muốn xóa ${userIds.length} người dùng? Hành động này không thể hoàn tác!`)) {
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.users.bulk-delete') }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            userIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        // Mobile Swipe Functionality
        if (window.innerWidth <= 768) {
            const mobileCards = document.querySelectorAll('.mobile-card');

            mobileCards.forEach(card => {
                let startX = 0;
                let currentX = 0;
                let isDragging = false;
                let isSwiped = false;
                const swipeThreshold = 50;
                const actionWidth = 100;

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

                    if (diff > 0) {
                        const translateX = Math.min(diff, actionWidth);
                        card.style.transform = `translateX(-${translateX}px)`;
                    } else if (isSwiped) {
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
                        if (diff < -swipeThreshold) {
                            card.style.transform = 'translateX(0)';
                            card.classList.remove('swiped');
                            isSwiped = false;
                        } else {
                            card.style.transform = `translateX(-${actionWidth}px)`;
                        }
                    } else {
                        if (diff > swipeThreshold) {
                            card.style.transform = `translateX(-${actionWidth}px)`;
                            card.classList.add('swiped');
                            isSwiped = true;

                            mobileCards.forEach(otherCard => {
                                if (otherCard !== card) {
                                    otherCard.style.transform = 'translateX(0)';
                                    otherCard.classList.remove('swiped');
                                }
                            });
                        } else {
                            card.style.transform = 'translateX(0)';
                        }
                    }
                }, {
                    passive: true
                });

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
@endsection
