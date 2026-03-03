@extends('admin.layouts.app')

@section('admin-content')
    <div style="max-width: 1200px; margin: 0 auto; width: 100%;">
    <div class="page-header">
        <h1>
            <i class="fas fa-tachometer-alt" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Bảng Điều Khiển Quản Trị
        </h1>
    </div>

    <!-- Stats Cards -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Users Card -->
        <div
            style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); border-left: 4px solid var(--primary-green);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; color: var(--gray); font-size: 0.9rem; font-weight: 600;">NGƯỜI DÙNG</p>
                    <h2 style="margin: 0.5rem 0 0 0; font-size: 2rem; color: var(--dark);">{{ $stats['total_users'] }}</h2>
                </div>
                <i class="fas fa-users" style="font-size: 2.5rem; color: var(--primary-green); opacity: 0.2;"></i>
            </div>
        </div>


        <!-- Roles Card -->
        <div
            style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); border-left: 4px solid #f59e0b;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; color: var(--gray); font-size: 0.9rem; font-weight: 600;">VAI TRÒ</p>
                    <h2 style="margin: 0.5rem 0 0 0; font-size: 2rem; color: var(--dark);">{{ $stats['total_roles'] }}</h2>
                </div>
                <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: #f59e0b; opacity: 0.2;"></i>
            </div>
        </div>

        <!-- Audits Card -->
        <div
            style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); border-left: 4px solid #06b6d4;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; color: var(--gray); font-size: 0.9rem; font-weight: 600;">Kiểm Soát Chất Lượng</p>
                    <h2 style="margin: 0.5rem 0 0 0; font-size: 2rem; color: var(--dark);">{{ $stats['total_audits'] }}</h2>
                </div>
                <i class="fas fa-file-alt" style="font-size: 2.5rem; color: #06b6d4; opacity: 0.2;"></i>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div
        style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <h3 style="margin-top: 0; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-user-plus" style="color: var(--primary-green);"></i>
            Người Dùng Gần Đây
        </h3>

        <table>
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai Trò</th>
                    <th>Ngày Tạo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recent_users as $user)
                    <tr>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->isAdmin() ? 'badge-danger' : 'badge-success' }}">
                                {{ $user->role->role_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--gray);">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    </div>
@endsection
