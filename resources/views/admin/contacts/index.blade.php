@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-praying-hands" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Quản Lý Phật Tử
        </h1>
        <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Thêm Phật Tử
        </a>
    </div>

    <div style="background: var(--white); padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('admin.contacts.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm theo tên, pháp danh, điện thoại, email..."
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
                    <th>Họ tên</th>
                    <th>Pháp danh</th>
                    <th>Tình trạng</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Gia đình</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->full_name }}</td>
                        <td>{{ $contact->dharma_name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ ($contact->life_status ?? 'alive') === 'deceased' ? 'badge-danger' : 'badge-success' }}">
                                {{ ($contact->life_status ?? 'alive') === 'deceased' ? 'Đã mất' : 'Còn sống' }}
                            </span>
                        </td>
                        <td>{{ $contact->phone ?? '—' }}</td>
                        <td>{{ $contact->email ?? '—' }}</td>
                        <td>
                            {{ $contact->family?->family_name ?? $contact->family_name ?? '—' }}
                            @if ($contact->is_household_head)
                                <span class="badge badge-danger" style="margin-left: 0.35rem;">Chủ hộ</span>
                            @endif
                            @if ($contact->is_primary_contact)
                                <span class="badge badge-warning" style="margin-left: 0.35rem;">Liên hệ chính</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $contact->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                {{ $contact->status === 'active' ? 'Hoạt động' : 'Ngưng hoạt động' }}
                            </span>
                        </td>
                        <td>{{ $contact->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-secondary" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-secondary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" style="display: inline;">
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
                        <td colspan="9" style="text-align: center; color: var(--gray); padding: 2rem;">Không có dữ liệu phật tử</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $contacts->links('pagination::custom') }}
@endsection
