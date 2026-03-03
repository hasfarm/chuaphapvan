@extends('admin.layouts.app')

@section('admin-content')
    @php
        $eventTypeColors = [
            'Đại lễ' => ['#dcfce7', '#166534'],
            'Khóa tu' => ['#dbeafe', '#1e3a8a'],
            'Công quả' => ['#ffedd5', '#9a3412'],
            'Lễ cầu an' => ['#e0e7ff', '#312e81'],
            'Lễ cầu siêu' => ['#fce7f3', '#9d174d'],
            'Pháp thoại' => ['#ccfbf1', '#134e4a'],
            'Sinh hoạt đạo tràng' => ['#f3e8ff', '#581c87'],
            'Từ thiện' => ['#fef3c7', '#92400e'],
            'Họp nội bộ' => ['#e5e7eb', '#1f2937'],
            'Khác' => ['#ecfeff', '#164e63'],
        ];
    @endphp

    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-days" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Quản Lý Sự Kiện
        </h1>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.events.calendar') }}" class="btn btn-secondary">
                <i class="fas fa-calendar-week"></i>
                Xem Calendar
            </a>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm Sự Kiện
            </a>
        </div>
    </div>

    <div style="background: var(--white); padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('admin.events.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm theo tên sự kiện, loại, địa điểm..."
                style="flex: 1; min-width: 240px; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">

            <input type="number" name="event_year" value="{{ request('event_year') }}" placeholder="Năm"
                style="width: 120px; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">

            <select name="status" style="padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 0.5rem; min-width: 180px;">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
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
                    <th>Tên sự kiện</th>
                    <th>Năm</th>
                    <th>Ngày DL</th>
                    <th>Thời gian</th>
                    <th>Ngày AL</th>
                    <th>Năm AL</th>
                    <th>Loại</th>
                    <th>Địa điểm</th>
                    <th>Tham gia</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>{{ $event->display_title }}</td>
                        <td>{{ $event->event_year }}</td>
                        <td>{{ $event->event_date?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if ($event->event_start_time || $event->event_end_time)
                                {{ $event->event_start_time ? \Carbon\Carbon::parse($event->event_start_time)->format('H:i') : '??:??' }} -
                                {{ $event->event_end_time ? \Carbon\Carbon::parse($event->event_end_time)->format('H:i') : '??:??' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $event->event_lunar_date ?? '—' }}</td>
                        <td>{{ $event->event_lunar_year ?? '—' }}</td>
                        @php
                            $eventTypeLabel = $event->event_type ?? 'Khác';
                            $eventTypeColor = $eventTypeColors[$eventTypeLabel] ?? $eventTypeColors['Khác'];
                        @endphp
                        <td>
                            <span style="display:inline-block; padding: 0.25rem 0.6rem; border-radius: 9999px; background: {{ $eventTypeColor[0] }}; color: {{ $eventTypeColor[1] }}; font-weight: 600;">
                                {{ $event->event_type ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $event->location ?? '—' }}</td>
                        <td>
                            <span style="display: inline-flex; gap: 0.55rem; flex-wrap: wrap;">
                                <span class="badge badge-success">GĐ: {{ $event->families_count ?? 0 }}</span>
                                <span class="badge badge-warning">CN: {{ $event->contacts_count ?? 0 }}</span>
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $event->status === 'upcoming' ? 'badge-warning' : ($event->status === 'ongoing' ? 'badge-success' : ($event->status === 'completed' ? 'badge-secondary' : 'badge-danger')) }}">
                                {{ $event->status === 'upcoming' ? 'Sắp diễn ra' : ($event->status === 'ongoing' ? 'Đang diễn ra' : ($event->status === 'completed' ? 'Hoàn thành' : 'Đã hủy')) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display: inline;">
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
                        <td colspan="11" style="text-align: center; color: var(--gray); padding: 2rem;">Không có dữ liệu sự kiện</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $events->links('pagination::custom') }}
@endsection
