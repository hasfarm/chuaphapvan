@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-shield-alt" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Bảng Phân Quyền
        </h1>
    </div>

    <div style="background: var(--white); border-radius: 1rem; box-shadow: var(--shadow); overflow: hidden;">
        <!-- Legend -->
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--light-gray); background: #f9fafb;">
            <h5 style="margin-top: 0; color: var(--dark);">Chú Thích:</h5>
            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check" style="color: var(--primary-green); font-size: 1.2rem;"></i>
                    <span style="color: var(--dark);">Được phép</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-times" style="color: #ef4444; font-size: 1.2rem;"></i>
                    <span style="color: var(--dark);">Không được phép</span>
                </div>
            </div>
        </div>

        <!-- Permissions Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--light-gray);">
                        <th
                            style="padding: 1.5rem; text-align: left; font-weight: 600; color: var(--dark); border-right: 1px solid var(--light-gray); min-width: 250px;">
                            Chức Năng
                        </th>
                        @foreach ($roles as $role)
                            <th
                                style="padding: 1.5rem; text-align: center; font-weight: 600; color: var(--dark); border-right: 1px solid var(--light-gray);">
                                <div style="font-size: 1.1rem;">
                                    {{ $role->name }}
                                </div>
                                <div style="font-size: 0.85rem; color: var(--gray); margin-top: 0.25rem;">
                                    {{ $role->display_name ?? $role->name }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $moduleKey => $module)
                        <!-- Module Header -->
                        <tr style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), transparent);">
                            <td colspan="{{ count($roles) + 1 }}"
                                style="padding: 1rem 1.5rem; font-weight: 700; color: var(--primary-green); border-bottom: 2px solid var(--light-gray);">
                                <i class="fas fa-folder me-2"></i>
                                {{ $module['name'] }}
                            </td>
                        </tr>

                        <!-- Permissions for this module -->
                        @foreach ($module['permissions'] as $permKey => $permName)
                            <tr>
                                <td
                                    style="padding: 1rem 1.5rem; color: var(--dark); border-right: 1px solid var(--light-gray); border-bottom: 1px solid var(--light-gray);">
                                    {{ $permName }}
                                </td>
                                @foreach ($roles as $role)
                                    <td
                                        style="padding: 1rem 1.5rem; text-align: center; border-right: 1px solid var(--light-gray); border-bottom: 1px solid var(--light-gray);">
                                        @if (isset($rolePermissions[$role->name][$moduleKey]) && in_array($permKey, $rolePermissions[$role->name][$moduleKey]))
                                            <i class="fas fa-check"
                                                style="color: var(--primary-green); font-size: 1.2rem;"></i>
                                        @else
                                            <i class="fas fa-times" style="color: #ef4444; font-size: 1.2rem;"></i>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div style="padding: 2rem; background: #f9fafb; border-top: 1px solid var(--light-gray);">
            <h5 style="margin-top: 0; color: var(--dark); margin-bottom: 1rem;">Tóm Tắt Roles:</h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach ($roles as $role)
                    @php
                        $totalPerms = 0;
                        $grantedPerms = 0;
                        foreach ($modules as $moduleKey => $module) {
                            foreach ($module['permissions'] as $permKey => $permName) {
                                $totalPerms++;
                                if (
                                    isset($rolePermissions[$role->name][$moduleKey]) &&
                                    in_array($permKey, $rolePermissions[$role->name][$moduleKey])
                                ) {
                                    $grantedPerms++;
                                }
                            }
                        }
                        $percentage = round(($grantedPerms / $totalPerms) * 100);
                    @endphp
                    <div
                        style="background: var(--white); padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid var(--primary-green); display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h6 style="margin: 0 0 0.5rem 0; color: var(--dark);">
                                <i class="fas fa-user-shield"
                                    style="color: var(--primary-green); margin-right: 0.5rem;"></i>
                                {{ $role->display_name ?? $role->name }}
                            </h6>
                            <p style="margin: 0.5rem 0; font-size: 0.95rem; color: var(--gray);">
                                {{ $role->description ?? 'Không có mô tả' }}
                            </p>
                            <div style="margin-top: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.85rem; color: var(--gray);">Quyền:
                                        {{ $grantedPerms }}/{{ $totalPerms }}</span>
                                    <span
                                        style="font-size: 0.85rem; color: var(--primary-green); font-weight: 600;">{{ $percentage }}%</span>
                                </div>
                                <div
                                    style="height: 6px; background: var(--light-gray); border-radius: 3px; overflow: hidden;">
                                    <div
                                        style="height: 100%; background: linear-gradient(90deg, var(--primary-green), var(--dark-green)); width: {{ $percentage }}%; border-radius: 3px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.permissions.edit', $role) }}" class="btn-edit"
                            style="margin-left: 1rem; padding: 0.5rem 1rem; background: var(--primary-green); color: var(--white); border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.85rem; white-space: nowrap; transition: all 0.3s ease;"
                            onmouseover="this.style.background='var(--dark-green)'; this.style.boxShadow='var(--shadow-lg)';"
                            onmouseout="this.style.background='var(--primary-green)'; this.style.boxShadow='none';">
                            <i class="fas fa-edit"></i> Chỉnh Sửa
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
