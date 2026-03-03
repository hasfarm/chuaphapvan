@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-edit" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chỉnh Sửa Phân Quyền: {{ $role->display_name ?? $role->name }}
        </h1>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Quay Lại
        </a>
    </div>

    <div style="background: var(--white); border-radius: 1rem; box-shadow: var(--shadow); padding: 2rem;">
        <form method="POST" action="{{ route('admin.permissions.update', $role) }}" novalidate>
            @csrf
            @method('PUT')

            <!-- Info -->
            <div
                style="background: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border-left: 4px solid var(--primary-green);">
                <h6 style="margin-top: 0; color: var(--dark);">Vai Trò:
                    <strong>{{ $role->display_name ?? $role->name }}</strong></h6>
                <p style="margin: 0.5rem 0 0 0; color: var(--gray); font-size: 0.95rem;">
                    {{ $role->description ?? 'Không có mô tả' }}
                </p>
            </div>

            <!-- Permissions Grid -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                @foreach ($modules as $moduleKey => $module)
                    <div
                        style="background: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; border: 2px solid var(--light-gray);">
                        <h6 style="margin-top: 0; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                            <i class="{{ $module['icon'] ?? 'fas fa-cog' }}" style="color: var(--primary-green);"></i>
                            {{ $module['name'] }}
                        </h6>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach ($module['permissions'] as $permKey => $permName)
                                @php
                                    $permissionKey = "{$moduleKey}.{$permKey}";
                                    $isChecked =
                                        isset($rolePermissions[$role->name][$moduleKey]) &&
                                        in_array($permKey, $rolePermissions[$role->name][$moduleKey]);
                                @endphp
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0;">
                                    <input type="checkbox" name="permissions[]" value="{{ $permissionKey }}"
                                        style="width: 18px; height: 18px; cursor: pointer;"
                                        {{ $isChecked ? 'checked' : '' }}>
                                    <span style="color: var(--dark); font-weight: 500;">{{ $permName }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div
                style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), transparent); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                <p style="margin: 0; color: var(--dark);">
                    <strong>Tổng quyền được cấp:</strong>
                    <span id="totalPermissions" style="color: var(--primary-green); font-weight: 700;">0</span> /
                    <span id="maxPermissions" style="color: var(--gray);">0</span>
                </p>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary"
                    style="flex: 1; justify-content: center;">
                    <i class="fas fa-times"></i>
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <i class="fas fa-save"></i>
                    Lưu Phân Quyền
                </button>
            </div>
        </form>
    </div>

    <style>
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

        .btn-secondary {
            background: var(--light-gray);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: var(--gray);
            color: var(--white);
        }
    </style>

    <script>
        function updatePermissionCount() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            const checkedCount = document.querySelectorAll('input[name="permissions[]"]:checked').length;

            document.getElementById('totalPermissions').textContent = checkedCount;
            document.getElementById('maxPermissions').textContent = checkboxes.length;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePermissionCount();

            // Update when checkbox changes
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updatePermissionCount);
            });
        });
    </script>
@endsection
