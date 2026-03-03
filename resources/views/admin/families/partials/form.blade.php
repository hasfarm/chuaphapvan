@php
    $isEdit = isset($family) && $family;
@endphp

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem;">
    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Tên gia đình <span style="color: #dc2626;">*</span></label>
        <input type="text" name="family_name" value="{{ old('family_name', $family->family_name ?? '') }}" required
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('family_name') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('family_name')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Mã gia đình</label>
        <input type="text" name="family_code" value="{{ old('family_code', $family->family_code ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('family_code') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('family_code')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Chủ hộ</label>
        <input type="text" name="head_name" list="family-head-contact-list" value="{{ old('head_name', $family->head_name ?? '') }}"
            placeholder="Gõ để tìm từ danh sách phật tử..."
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
        <datalist id="family-head-contact-list">
            @foreach (($contacts ?? collect()) as $contact)
                <option value="{{ $contact->full_name }}">{{ $contact->dharma_name ? $contact->dharma_name . ' - ' : '' }}{{ $contact->full_name }}</option>
            @endforeach
        </datalist>
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Điện thoại</label>
        <input type="text" name="phone" value="{{ old('phone', $family->phone ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Email</label>
        <input type="email" name="email" value="{{ old('email', $family->email ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('email') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('email')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Trạng thái <span style="color: #dc2626;">*</span></label>
        <select name="status" required
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('status') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
            <option value="active" {{ old('status', $family->status ?? 'active') === 'active' ? 'selected' : '' }}>Hoạt động</option>
            <option value="inactive" {{ old('status', $family->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
        </select>
        @error('status')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Địa chỉ</label>
        <textarea name="address" rows="3"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">{{ old('address', $family->address ?? '') }}</textarea>
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ghi chú</label>
        <textarea name="notes" rows="4"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">{{ old('notes', $family->notes ?? '') }}</textarea>
    </div>
</div>

<div style="display: flex; gap: 1rem; margin-top: 2rem;">
    <a href="{{ route('admin.families.index') }}" class="btn btn-secondary" style="flex: 1; justify-content: center;">
        <i class="fas fa-times"></i>
        Hủy
    </a>
    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
        <i class="fas fa-save"></i>
        {{ $isEdit ? 'Cập Nhật' : 'Tạo Mới' }}
    </button>
</div>
