@extends('admin.layouts.app')

@php
    $parentLabel = $contact->gender === 'male' ? 'Cha của' : ($contact->gender === 'female' ? 'Mẹ của' : 'Cha/Mẹ của');
    $spouseLabel = $contact->gender === 'male' ? 'Chồng của' : ($contact->gender === 'female' ? 'Vợ của' : 'Vợ/Chồng với');
@endphp

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-id-card" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chi Tiết Phật Tử
        </h1>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Chỉnh Sửa
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
            <div><strong>Họ tên:</strong><br>{{ $contact->full_name }}</div>
            <div><strong>Pháp danh:</strong><br>{{ $contact->dharma_name ?? '—' }}</div>
            <div><strong>Điện thoại:</strong><br>{{ $contact->phone ?? '—' }}</div>
            <div><strong>Email:</strong><br>{{ $contact->email ?? '—' }}</div>
            <div><strong>Ngày sinh (DL):</strong><br>{{ $contact->solar_birth_date?->format('d/m/Y') ?? '—' }}</div>
            <div><strong>Năm sinh (DL):</strong><br>{{ $contact->solar_birth_year ?? '—' }}</div>
            <div><strong>Ngày sinh (AL):</strong><br>{{ $contact->lunar_birth_date ?? '—' }}</div>
            <div><strong>Năm sinh (AL):</strong><br>{{ $contact->lunar_birth_year ?? '—' }}</div>
            <div><strong>Giới tính:</strong><br>{{ $contact->gender === 'male' ? 'Nam' : ($contact->gender === 'female' ? 'Nữ' : ($contact->gender === 'other' ? 'Khác' : '—')) }}</div>
            <div><strong>Tình trạng:</strong><br>{{ ($contact->life_status ?? 'alive') === 'deceased' ? 'Đã mất' : 'Còn sống' }}</div>
            <div><strong>Ngày mất (DL):</strong><br>{{ $contact->death_solar_date?->format('d/m/Y') ?? '—' }}</div>
            <div><strong>Ngày mất (AL):</strong><br>{{ $contact->death_lunar_date ?? '—' }}</div>
            <div><strong>Năm mất (AL):</strong><br>{{ $contact->death_lunar_year ?? '—' }}</div>
            <div><strong>Tử vi:</strong><br>{{ $contact->zodiac_info ?? '—' }}</div>
            <div><strong>Tên gia đình:</strong><br>{{ $contact->family?->family_name ?? $contact->family_name ?? '—' }}</div>
            <div><strong>Chủ hộ:</strong><br>{{ $contact->is_household_head ? $contact->full_name : ($contact->family_head_name ?? '—') }}</div>
            <div><strong>Liên lạc chính:</strong><br>{{ $contact->is_primary_contact ? 'Có' : 'Không' }}</div>
            <div style="grid-column: 1 / -1;"><strong>Địa chỉ:</strong><br>{{ $contact->address ?? '—' }}</div>
            <div style="grid-column: 1 / -1;"><strong>Địa chỉ gia đình:</strong><br>{{ $contact->family_address ?? '—' }}</div>
            <div style="grid-column: 1 / -1;"><strong>Ghi chú:</strong><br>{{ $contact->notes ?? '—' }}</div>
            <div style="grid-column: 1 / -1;">
                <strong>Quan hệ liên kết:</strong><br>
                @if ($contact->relationshipsOut->isEmpty() && $contact->relationshipsIn->isEmpty())
                    —
                @else
                    <ul style="margin: 0.5rem 0 0 1rem;">
                        @foreach ($contact->relationshipsOut as $relation)
                            <li>
                                {{ $relation->relationship_type === 'parent' ? $parentLabel : ($relation->relationship_type === 'child' ? 'Con của' : $spouseLabel) }}:
                                {{ $relation->relatedContact?->full_name ?? '—' }}
                            </li>
                        @endforeach
                        @foreach ($contact->relationshipsIn as $relation)
                            <li>
                                {{ $relation->relationship_type === 'parent' ? 'Con của' : ($relation->relationship_type === 'child' ? $parentLabel : $spouseLabel) }}:
                                {{ $relation->contact?->full_name ?? '—' }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
