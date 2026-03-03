@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-people-roof" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Quản Lý Gia Đình
        </h1>
        <a href="{{ route('admin.families.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Thêm Gia Đình
        </a>
    </div>

    <div style="background: var(--white); padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('admin.families.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm theo tên gia đình, mã, chủ hộ, điện thoại..."
                style="flex: 1; min-width: 240px; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">

            <select name="status" style="padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem; min-width: 180px;">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Tìm Kiếm
            </button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tên gia đình</th>
                    <th>Mã gia đình</th>
                    <th>Chủ hộ</th>
                    <th>Số thành viên</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($families as $family)
                    <tr>
                        <td>{{ $family->family_name }}</td>
                        <td>{{ $family->family_code ?? '—' }}</td>
                        <td>{{ $family->head_name ?? '—' }}</td>
                        <td>{{ $family->contacts_count ?? 0 }}</td>
                        <td>{{ $family->phone ?? '—' }}</td>
                        <td>{{ $family->email ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $family->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                {{ $family->status === 'active' ? 'Hoạt động' : 'Ngưng hoạt động' }}
                            </span>
                        </td>
                        <td>{{ $family->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.families.show', $family) }}" class="btn btn-secondary" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.families.edit', $family) }}" class="btn btn-secondary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.families.destroy', $family) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--gray); padding: 2rem;">Không có dữ liệu gia đình</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $families->links('pagination::custom') }}
@endsection
