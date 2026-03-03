@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-shield-alt" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Quản Lý Vai Trò
        </h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Thêm Vai Trò
        </a>
    </div>

    <!-- Search -->
    <div
        style="background: var(--white); padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <form method="GET" action="{{ route('admin.roles.index') }}" style="display: flex; gap: 1rem;">
            <input type="text" name="search" placeholder="Tìm kiếm vai trò..." value="{{ request('search') }}"
                style="flex: 1; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tên Vai Trò</th>
                    <th>Mô Tả</th>
                    <th>Người Dùng</th>
                    <th>Ngày Tạo</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td><strong>{{ $role->name }}</strong></td>
                        <td>{{ $role->description ?? 'N/A' }}</td>
                        <td><span class="badge badge-success">{{ $role->users_count ?? 0 }}</span></td>
                        <td>{{ $role->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-secondary"
                                    title="Chỉnh Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
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
                        <td colspan="5" style="text-align: center; color: var(--gray); padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            Không tìm thấy vai trò nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $roles->links('pagination::custom') }}
@endsection
