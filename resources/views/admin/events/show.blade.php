@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-day" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chi Tiết Sự Kiện
        </h1>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Chỉnh Sửa
            </a>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
            <div><strong>Sự kiện:</strong><br>{{ $event->display_title }}</div>
            <div><strong>Năm:</strong><br>{{ $event->event_year }}</div>
            <div><strong>Ngày dương lịch:</strong><br>{{ $event->event_date?->format('d/m/Y') ?? '—' }}</div>
            <div>
                <strong>Thời gian:</strong><br>
                @if ($event->event_start_time || $event->event_end_time)
                    {{ $event->event_start_time ? \Carbon\Carbon::parse($event->event_start_time)->format('H:i') : '??:??' }} -
                    {{ $event->event_end_time ? \Carbon\Carbon::parse($event->event_end_time)->format('H:i') : '??:??' }}
                @else
                    —
                @endif
            </div>
            <div><strong>Ngày âm lịch:</strong><br>{{ $event->event_lunar_date ?? '—' }}</div>
            <div><strong>Năm âm lịch:</strong><br>{{ $event->event_lunar_year ?? '—' }}</div>
            <div><strong>Loại sự kiện:</strong><br>{{ $event->event_type ?? '—' }}</div>
            <div><strong>Địa điểm:</strong><br>{{ $event->location ?? '—' }}</div>
            <div><strong>Lặp hàng năm:</strong><br>{{ $event->is_annual ? 'Có' : 'Không' }}</div>
            <div><strong>Trạng thái:</strong><br>{{ $event->status === 'upcoming' ? 'Sắp diễn ra' : ($event->status === 'ongoing' ? 'Đang diễn ra' : ($event->status === 'completed' ? 'Hoàn thành' : 'Đã hủy')) }}</div>
            <div style="grid-column: 1 / -1;">
                <strong>Gia đình tham gia:</strong><br>
                @if ($event->families->isEmpty())
                    —
                @else
                    {{ $event->families->map(fn ($family) => $family->family_name)->join(', ') }}
                @endif
            </div>
            <div style="grid-column: 1 / -1;">
                <strong>Cá nhân tham gia:</strong><br>
                @if ($event->contacts->isEmpty())
                    —
                @else
                    {{ $event->contacts->map(fn ($contact) => $contact->full_name)->join(', ') }}
                @endif
            </div>
            <div style="grid-column: 1 / -1;"><strong>Mô tả:</strong><br>{{ $event->description ?? '—' }}</div>
        </div>
    </div>
@endsection
