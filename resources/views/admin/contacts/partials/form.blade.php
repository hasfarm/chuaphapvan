@php
    $isEdit = isset($contact) && $contact;
    $isDeceased = old('life_status', $contact->life_status ?? 'alive') === 'deceased';
    $genderValue = old('gender', $contact->gender ?? '');
    $parentRelationshipLabel = $genderValue === 'male'
        ? 'Cha của'
        : ($genderValue === 'female' ? 'Mẹ của' : 'Cha/Mẹ của');
    $spouseRelationshipLabel = $genderValue === 'male'
        ? 'Chồng của'
        : ($genderValue === 'female' ? 'Vợ của' : 'Vợ/Chồng với');
    $relationshipRows = old('relationship_type') ? collect(old('relationship_type'))->map(function ($type, $index) {
        return [
            'relationship_type' => $type,
            'related_contact_id' => old('related_contact_id.' . $index),
        ];
    })->toArray() : ($contactRelationships ?? []);
@endphp

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem;">
    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Họ tên <span style="color: #dc2626;">*</span></label>
        <input type="text" name="full_name" value="{{ old('full_name', $contact->full_name ?? '') }}" required
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('full_name') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('full_name')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Pháp danh</label>
        <input type="text" name="dharma_name" value="{{ old('dharma_name', $contact->dharma_name ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Điện thoại</label>
        <input type="text" name="phone" value="{{ old('phone', $contact->phone ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Email</label>
        <input type="email" name="email" value="{{ old('email', $contact->email ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('email') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('email')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ngày sinh (DL)</label>
        <input type="date" id="solar_birth_date" name="solar_birth_date" value="{{ old('solar_birth_date', isset($contact->solar_birth_date) ? $contact->solar_birth_date->format('Y-m-d') : '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Năm sinh (DL)</label>
        <input type="number" id="solar_birth_year" name="solar_birth_year" value="{{ old('solar_birth_year', $contact->solar_birth_year ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('solar_birth_year') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('solar_birth_year')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ngày sinh (AL)</label>
        <input type="text" id="lunar_birth_date" name="lunar_birth_date" value="{{ old('lunar_birth_date', $contact->lunar_birth_date ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Năm sinh (AL)</label>
        <input type="text" id="lunar_birth_year" name="lunar_birth_year" value="{{ old('lunar_birth_year', $contact->lunar_birth_year ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Giới tính</label>
        <select id="gender" name="gender" style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
            <option value="">-- Chọn giới tính --</option>
            <option value="male" {{ old('gender', $contact->gender ?? '') === 'male' ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ old('gender', $contact->gender ?? '') === 'female' ? 'selected' : '' }}>Nữ</option>
            <option value="other" {{ old('gender', $contact->gender ?? '') === 'other' ? 'selected' : '' }}>Khác</option>
        </select>
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Tử vi</label>
        <input type="text" id="zodiac_info" name="zodiac_info" value="{{ old('zodiac_info', $contact->zodiac_info ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Tình trạng <span style="color: #dc2626;">*</span></label>
        <select id="life_status" name="life_status" data-no-select2="1" required
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('life_status') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
            <option value="alive" {{ old('life_status', $contact->life_status ?? 'alive') === 'alive' ? 'selected' : '' }}>Còn sống</option>
            <option value="deceased" {{ old('life_status', $contact->life_status ?? 'alive') === 'deceased' ? 'selected' : '' }}>Đã mất</option>
        </select>
        @error('life_status')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div id="death_solar_date_wrapper" style="display: {{ $isDeceased ? 'block' : 'none' }};">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ngày mất (DL)</label>
        <input type="date" id="death_solar_date" name="death_solar_date" value="{{ old('death_solar_date', isset($contact->death_solar_date) ? $contact->death_solar_date->format('Y-m-d') : '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('death_solar_date') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('death_solar_date')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div id="death_lunar_date_wrapper" style="display: {{ $isDeceased ? 'block' : 'none' }};">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ngày mất (AL)</label>
        <input type="text" id="death_lunar_date" name="death_lunar_date" value="{{ old('death_lunar_date', $contact->death_lunar_date ?? '') }}"
            readonly style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('death_lunar_date') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('death_lunar_date')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div id="death_lunar_year_wrapper" style="display: {{ $isDeceased ? 'block' : 'none' }};">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Năm mất (AL)</label>
        <input type="text" id="death_lunar_year" name="death_lunar_year" value="{{ old('death_lunar_year', $contact->death_lunar_year ?? '') }}"
            readonly style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('death_lunar_year') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('death_lunar_year')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Gia đình</label>
        <input type="text" id="family_search" placeholder="Tìm theo tên hoặc mã gia đình..."
            style="display: none; width: 100%; padding: 0.65rem; margin-bottom: 0.45rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
        <select id="family_id" name="family_id" size="6"
            style="width: 100%; padding: 0.55rem; border: 2px solid {{ $errors->has('family_id') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; background: #fff;">
            <option value="">-- Chưa gán gia đình --</option>
            @foreach ($families as $family)
                <option value="{{ $family->id }}" {{ (string) old('family_id', $contact->family_id ?? '') === (string) $family->id ? 'selected' : '' }}>
                    {{ $family->family_name }}{{ $family->family_code ? ' (' . $family->family_code . ')' : '' }}
                </option>
            @endforeach
        </select>
        <div id="family-search-empty" style="display: none; margin-top: 0.35rem; font-size: 0.82rem; color: #b45309;">
            Không tìm thấy gia đình phù hợp.
        </div>

        @error('family_id')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
        <input type="hidden" id="create_new_family" name="create_new_family" value="{{ old('create_new_family') ? 1 : 0 }}">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.65rem; flex-wrap: wrap;">
            <button type="button" id="open-new-family-modal" class="btn btn-secondary" style="padding: 0.4rem 0.75rem;">
                <i class="fas fa-plus"></i>
                Tạo gia đình mới
            </button>
            <span id="new-family-active-text" style="display: {{ old('create_new_family') ? 'inline' : 'none' }}; font-size: 0.82rem; color: #065f46;">
                Đang dùng thông tin gia đình mới
            </span>
            <button type="button" id="clear-new-family" class="btn btn-danger" style="display: {{ old('create_new_family') ? 'inline-flex' : 'none' }}; padding: 0.35rem 0.65rem;">
                <i class="fas fa-xmark"></i>
                Bỏ tạo mới
            </button>
        </div>
    </div>

    <div id="new-family-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="width: min(900px, 96vw); max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 0.85rem; box-shadow: 0 24px 48px rgba(0,0,0,0.22);">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.2rem; border-bottom: 1px solid var(--light-gray);">
                <h3 style="margin: 0; font-size: 1.05rem; color: var(--dark);">Thêm gia đình mới</h3>
                <button type="button" id="close-new-family-modal" class="btn btn-secondary" style="padding: 0.35rem 0.6rem;">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <div style="padding: 1rem 1.2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Tên gia đình <span style="color: #dc2626;">*</span></label>
                    <input type="text" id="new_family_name" name="new_family_name" value="{{ old('new_family_name') }}"
                        style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                    @error('new_family_name')<div style="color: #dc2626; font-size: 0.82rem; margin-top: 0.2rem;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Mã gia đình</label>
                    <input type="text" name="new_family_code" value="{{ old('new_family_code') }}"
                        style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Chủ hộ</label>
                    <input type="text" name="new_family_head_name" value="{{ old('new_family_head_name') }}"
                        style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Điện thoại</label>
                    <input type="text" name="new_family_phone" value="{{ old('new_family_phone') }}"
                        style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Email</label>
                    <input type="email" name="new_family_email" value="{{ old('new_family_email') }}"
                        style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Trạng thái</label>
                    <select name="new_family_status" style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                        <option value="active" {{ old('new_family_status', 'active') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('new_family_status') === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
                    </select>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Địa chỉ gia đình</label>
                    <textarea name="new_family_address" rows="2" style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">{{ old('new_family_address') }}</textarea>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 0.3rem; font-weight: 600;">Ghi chú gia đình</label>
                    <textarea name="new_family_notes" rows="2" style="width: 100%; padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">{{ old('new_family_notes') }}</textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem; padding: 0 1.2rem 1rem 1.2rem;">
                <button type="button" id="cancel-new-family-modal" class="btn btn-secondary" style="padding: 0.45rem 0.8rem;">
                    Hủy
                </button>
                <button type="button" id="save-new-family-modal" class="btn btn-primary" style="padding: 0.45rem 0.9rem;">
                    <i class="fas fa-check"></i>
                    Dùng thông tin này
                </button>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; justify-content: center; gap: 0.5rem;">
        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            <input type="checkbox" name="is_household_head" value="1" {{ old('is_household_head', $contact->is_household_head ?? false) ? 'checked' : '' }}>
            Là chủ hộ
        </label>
        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            <input type="checkbox" name="is_primary_contact" value="1" {{ old('is_primary_contact', $contact->is_primary_contact ?? false) ? 'checked' : '' }}>
            Là liên lạc chính
        </label>
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Địa chỉ</label>
        <textarea name="address" rows="3"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">{{ old('address', $contact->address ?? '') }}</textarea>
    </div>

    <div style="grid-column: 1 / -1; background: #f9fafb; padding: 1rem; border: 1px solid var(--light-gray); border-radius: 0.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <label style="font-weight: 700; margin: 0;">Quan hệ với phật tử khác</label>
            <button type="button" class="btn btn-secondary" style="padding: 0.35rem 0.75rem;" onclick="addRelationshipRow()">
                <i class="fas fa-plus"></i> Thêm quan hệ
            </button>
        </div>
        <div id="relationships-container" style="display: grid; gap: 0.75rem;">
            @forelse ($relationshipRows as $row)
                <div class="relationship-row" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 0.5rem; align-items: center;">
                    <select name="relationship_type[]" style="padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                        <option value="">-- Loại quan hệ --</option>
                        <option value="parent" {{ ($row['relationship_type'] ?? '') === 'parent' ? 'selected' : '' }}>{{ $parentRelationshipLabel }}</option>
                        <option value="child" {{ ($row['relationship_type'] ?? '') === 'child' ? 'selected' : '' }}>Con cái</option>
                        <option value="spouse" {{ ($row['relationship_type'] ?? '') === 'spouse' ? 'selected' : '' }}>{{ $spouseRelationshipLabel }}</option>
                    </select>
                    <select name="related_contact_id[]" class="relationship-related-contact" data-placeholder="-- Chọn phật tử --" style="padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                        <option value="">-- Chọn phật tử --</option>
                        @foreach ($availableContacts as $relatedContact)
                            <option value="{{ $relatedContact->id }}" {{ (string) ($row['related_contact_id'] ?? '') === (string) $relatedContact->id ? 'selected' : '' }}>
                                {{ $relatedContact->full_name }}{{ $relatedContact->dharma_name ? ' (' . $relatedContact->dharma_name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger" style="padding: 0.4rem 0.7rem;" onclick="removeRelationshipRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            @empty
                <div class="relationship-row" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 0.5rem; align-items: center;">
                    <select name="relationship_type[]" style="padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                        <option value="">-- Loại quan hệ --</option>
                        <option value="parent">{{ $parentRelationshipLabel }}</option>
                        <option value="child">Con cái</option>
                        <option value="spouse">{{ $spouseRelationshipLabel }}</option>
                    </select>
                    <select name="related_contact_id[]" class="relationship-related-contact" data-placeholder="-- Chọn phật tử --" style="padding: 0.65rem; border: 1px solid var(--light-gray); border-radius: 0.5rem;">
                        <option value="">-- Chọn phật tử --</option>
                        @foreach ($availableContacts as $relatedContact)
                            <option value="{{ $relatedContact->id }}">
                                {{ $relatedContact->full_name }}{{ $relatedContact->dharma_name ? ' (' . $relatedContact->dharma_name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger" style="padding: 0.4rem 0.7rem;" onclick="removeRelationshipRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ghi chú</label>
        <textarea name="notes" rows="4"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">{{ old('notes', $contact->notes ?? '') }}</textarea>
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Trạng thái <span style="color: #dc2626;">*</span></label>
        <select name="status" required
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('status') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
            <option value="active" {{ old('status', $contact->status ?? 'active') === 'active' ? 'selected' : '' }}>Hoạt động</option>
            <option value="inactive" {{ old('status', $contact->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
        </select>
        @error('status')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>
</div>

<div style="display: flex; gap: 1rem; margin-top: 2rem;">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary" style="flex: 1; justify-content: center;">
        <i class="fas fa-times"></i>
        Hủy
    </a>
    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
        <i class="fas fa-save"></i>
        {{ $isEdit ? 'Cập Nhật' : 'Tạo Mới' }}
    </button>
</div>

<script>
    (function() {
        const createNewFamilyInput = document.getElementById('create_new_family');
        const familySelect = document.getElementById('family_id');
        const familySearch = document.getElementById('family_search');
        const familySearchEmpty = document.getElementById('family-search-empty');
        const openNewFamilyModalButton = document.getElementById('open-new-family-modal');
        const closeNewFamilyModalButton = document.getElementById('close-new-family-modal');
        const cancelNewFamilyModalButton = document.getElementById('cancel-new-family-modal');
        const saveNewFamilyModalButton = document.getElementById('save-new-family-modal');
        const clearNewFamilyButton = document.getElementById('clear-new-family');
        const newFamilyActiveText = document.getElementById('new-family-active-text');
        const newFamilyModal = document.getElementById('new-family-modal');
        const newFamilyNameInput = document.getElementById('new_family_name');

        if (!createNewFamilyInput || !familySelect) {
            return;
        }

        function normalizeText(value) {
            return (value || '')
                .toString()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim();
        }

        function filterFamilyOptions() {
            if (!familySearch) {
                return;
            }

            const keyword = normalizeText(familySearch.value);
            const options = Array.from(familySelect.options);
            let visibleCount = 0;

            options.forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const text = normalizeText(option.textContent);
                const isSelected = option.selected;
                const isMatch = !keyword || text.includes(keyword) || isSelected;

                option.hidden = !isMatch;
                if (isMatch) {
                    visibleCount++;
                }
            });

            if (familySearchEmpty) {
                familySearchEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        function setFamilyPickerDisabled(isDisabled) {
            if (familySearch) {
                familySearch.disabled = isDisabled;
                familySearch.style.opacity = isDisabled ? '0.65' : '1';
            }

            familySelect.disabled = isDisabled;
            familySelect.style.opacity = isDisabled ? '0.65' : '1';

            filterFamilyOptions();
        }

        function isCreatingNewFamily() {
            return String(createNewFamilyInput.value || '0') === '1';
        }

        function openNewFamilyModal() {
            if (!newFamilyModal) {
                return;
            }

            newFamilyModal.style.display = 'flex';
            if (newFamilyNameInput) {
                setTimeout(() => newFamilyNameInput.focus(), 0);
            }
        }

        function closeNewFamilyModal() {
            if (!newFamilyModal) {
                return;
            }

            newFamilyModal.style.display = 'none';
        }

        function setCreatingNewFamily(active) {
            createNewFamilyInput.value = active ? '1' : '0';

            if (newFamilyActiveText) {
                newFamilyActiveText.style.display = active ? 'inline' : 'none';
            }

            if (clearNewFamilyButton) {
                clearNewFamilyButton.style.display = active ? 'inline-flex' : 'none';
            }

            setFamilyPickerDisabled(active);

            if (active) {
                familySelect.value = '';
            }

            filterFamilyOptions();
        }

        familySelect.addEventListener('change', function() {
            if (familySelect.value) {
                setCreatingNewFamily(false);
            }

            filterFamilyOptions();
        });

        if (familySearch) {
            familySearch.addEventListener('input', filterFamilyOptions);
        }

        if (openNewFamilyModalButton) {
            openNewFamilyModalButton.addEventListener('click', openNewFamilyModal);
        }

        if (closeNewFamilyModalButton) {
            closeNewFamilyModalButton.addEventListener('click', closeNewFamilyModal);
        }

        if (cancelNewFamilyModalButton) {
            cancelNewFamilyModalButton.addEventListener('click', closeNewFamilyModal);
        }

        if (saveNewFamilyModalButton) {
            saveNewFamilyModalButton.addEventListener('click', function() {
                setCreatingNewFamily(true);
                closeNewFamilyModal();
            });
        }

        if (clearNewFamilyButton) {
            clearNewFamilyButton.addEventListener('click', function() {
                setCreatingNewFamily(false);
            });
        }

        if (newFamilyModal) {
            newFamilyModal.addEventListener('click', function(event) {
                if (event.target === newFamilyModal) {
                    closeNewFamilyModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeNewFamilyModal();
            }
        });

        setCreatingNewFamily(isCreatingNewFamily());

        @if ($errors->has('new_family_name') || $errors->has('new_family_code') || $errors->has('new_family_head_name') || $errors->has('new_family_phone') || $errors->has('new_family_email') || $errors->has('new_family_status') || $errors->has('new_family_address') || $errors->has('new_family_notes'))
            openNewFamilyModal();
        @endif

        filterFamilyOptions();
    })();
</script>

<script>
    (function() {
        const solarDateInput = document.getElementById('solar_birth_date');
        const solarYearInput = document.getElementById('solar_birth_year');
        const lunarDateInput = document.getElementById('lunar_birth_date');
        const lunarYearInput = document.getElementById('lunar_birth_year');
        const deathSolarDateInput = document.getElementById('death_solar_date');
        const deathLunarDateInput = document.getElementById('death_lunar_date');
        const deathLunarYearInput = document.getElementById('death_lunar_year');
        const lifeStatusInput = document.getElementById('life_status');
        const deathSolarDateWrapper = document.getElementById('death_solar_date_wrapper');
        const deathLunarDateWrapper = document.getElementById('death_lunar_date_wrapper');
        const deathLunarYearWrapper = document.getElementById('death_lunar_year_wrapper');
        const genderInput = document.getElementById('gender');
        const zodiacInfoInput = document.getElementById('zodiac_info');

        if (!solarYearInput || !lunarYearInput || !solarDateInput || !lunarDateInput || !genderInput || !zodiacInfoInput) {
            return;
        }

        const heavenlyStems = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
        const earthlyBranches = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
        const destinyByCanChi = {
            'Giáp Tý': 'Hải Trung Kim',
            'Ất Sửu': 'Hải Trung Kim',
            'Bính Dần': 'Lư Trung Hỏa',
            'Đinh Mão': 'Lư Trung Hỏa',
            'Mậu Thìn': 'Đại Lâm Mộc',
            'Kỷ Tỵ': 'Đại Lâm Mộc',
            'Canh Ngọ': 'Lộ Bàng Thổ',
            'Tân Mùi': 'Lộ Bàng Thổ',
            'Nhâm Thân': 'Kiếm Phong Kim',
            'Quý Dậu': 'Kiếm Phong Kim',
            'Giáp Tuất': 'Sơn Đầu Hỏa',
            'Ất Hợi': 'Sơn Đầu Hỏa',
            'Bính Tý': 'Giản Hạ Thủy',
            'Đinh Sửu': 'Giản Hạ Thủy',
            'Mậu Dần': 'Thành Đầu Thổ',
            'Kỷ Mão': 'Thành Đầu Thổ',
            'Canh Thìn': 'Bạch Lạp Kim',
            'Tân Tỵ': 'Bạch Lạp Kim',
            'Nhâm Ngọ': 'Dương Liễu Mộc',
            'Quý Mùi': 'Dương Liễu Mộc',
            'Giáp Thân': 'Tuyền Trung Thủy',
            'Ất Dậu': 'Tuyền Trung Thủy',
            'Bính Tuất': 'Ốc Thượng Thổ',
            'Đinh Hợi': 'Ốc Thượng Thổ',
            'Mậu Tý': 'Tích Lịch Hỏa',
            'Kỷ Sửu': 'Tích Lịch Hỏa',
            'Canh Dần': 'Tùng Bách Mộc',
            'Tân Mão': 'Tùng Bách Mộc',
            'Nhâm Thìn': 'Trường Lưu Thủy',
            'Quý Tỵ': 'Trường Lưu Thủy',
            'Giáp Ngọ': 'Sa Trung Kim',
            'Ất Mùi': 'Sa Trung Kim',
            'Bính Thân': 'Sơn Hạ Hỏa',
            'Đinh Dậu': 'Sơn Hạ Hỏa',
            'Mậu Tuất': 'Bình Địa Mộc',
            'Kỷ Hợi': 'Bình Địa Mộc',
            'Canh Tý': 'Bích Thượng Thổ',
            'Tân Sửu': 'Bích Thượng Thổ',
            'Nhâm Dần': 'Kim Bạch Kim',
            'Quý Mão': 'Kim Bạch Kim',
            'Giáp Thìn': 'Phú Đăng Hỏa',
            'Ất Tỵ': 'Phú Đăng Hỏa',
            'Bính Ngọ': 'Thiên Hà Thủy',
            'Đinh Mùi': 'Thiên Hà Thủy',
            'Mậu Thân': 'Đại Trạch Thổ',
            'Kỷ Dậu': 'Đại Trạch Thổ',
            'Canh Tuất': 'Thoa Xuyến Kim',
            'Tân Hợi': 'Thoa Xuyến Kim',
            'Nhâm Tý': 'Tang Đố Mộc',
            'Quý Sửu': 'Tang Đố Mộc',
            'Giáp Dần': 'Đại Khê Thủy',
            'Ất Mão': 'Đại Khê Thủy',
            'Bính Thìn': 'Sa Trung Thổ',
            'Đinh Tỵ': 'Sa Trung Thổ',
            'Mậu Ngọ': 'Thiên Thượng Hỏa',
            'Kỷ Mùi': 'Thiên Thượng Hỏa',
            'Canh Thân': 'Thạch Lựu Mộc',
            'Tân Dậu': 'Thạch Lựu Mộc',
            'Nhâm Tuất': 'Đại Hải Thủy',
            'Quý Hợi': 'Đại Hải Thủy'
        };

        function INT(value) {
            return Math.floor(value);
        }

        function jdFromDate(dd, mm, yy) {
            const a = INT((14 - mm) / 12);
            const y = yy + 4800 - a;
            const m = mm + 12 * a - 3;
            let jd = dd + INT((153 * m + 2) / 5) + 365 * y + INT(y / 4) - INT(y / 100) + INT(y / 400) - 32045;
            if (jd < 2299161) {
                jd = dd + INT((153 * m + 2) / 5) + 365 * y + INT(y / 4) - 32083;
            }
            return jd;
        }

        function newMoon(k) {
            const T = k / 1236.85;
            const T2 = T * T;
            const T3 = T2 * T;
            const dr = Math.PI / 180;
            let jd1 = 2415020.75933 + 29.53058868 * k + 0.0001178 * T2 - 0.000000155 * T3;
            jd1 += 0.00033 * Math.sin((166.56 + 132.87 * T - 0.009173 * T2) * dr);
            const M = 359.2242 + 29.10535608 * k - 0.0000333 * T2 - 0.00000347 * T3;
            const Mpr = 306.0253 + 385.81691806 * k + 0.0107306 * T2 + 0.00001236 * T3;
            const F = 21.2964 + 390.67050646 * k - 0.0016528 * T2 - 0.00000239 * T3;
            let C1 = (0.1734 - 0.000393 * T) * Math.sin(M * dr) + 0.0021 * Math.sin(2 * dr * M);
            C1 -= 0.4068 * Math.sin(Mpr * dr) + 0.0161 * Math.sin(dr * 2 * Mpr);
            C1 -= 0.0004 * Math.sin(dr * 3 * Mpr);
            C1 += 0.0104 * Math.sin(dr * 2 * F) - 0.0051 * Math.sin(dr * (M + Mpr));
            C1 -= 0.0074 * Math.sin(dr * (M - Mpr)) + 0.0004 * Math.sin(dr * (2 * F + M));
            C1 -= 0.0004 * Math.sin(dr * (2 * F - M)) - 0.0006 * Math.sin(dr * (2 * F + Mpr));
            C1 += 0.0010 * Math.sin(dr * (2 * F - Mpr)) + 0.0005 * Math.sin(dr * (2 * Mpr + M));
            let deltat;
            if (T < -11) {
                deltat = 0.001 + 0.000839 * T + 0.0002261 * T2 - 0.00000845 * T3 - 0.000000081 * T * T3;
            } else {
                deltat = -0.000278 + 0.000265 * T + 0.000262 * T2;
            }
            return jd1 + C1 - deltat;
        }

        function getNewMoonDay(k, timeZone) {
            return INT(newMoon(k) + 0.5 + timeZone / 24);
        }

        function sunLongitude(jdn, timeZone) {
            const T = (jdn - 2451545.5 - timeZone / 24) / 36525;
            const T2 = T * T;
            const dr = Math.PI / 180;
            const M = 357.52910 + 35999.05030 * T - 0.0001559 * T2 - 0.00000048 * T * T2;
            const L0 = 280.46645 + 36000.76983 * T + 0.0003032 * T2;
            let DL = (1.914600 - 0.004817 * T - 0.000014 * T2) * Math.sin(dr * M);
            DL += (0.019993 - 0.000101 * T) * Math.sin(dr * 2 * M) + 0.000290 * Math.sin(dr * 3 * M);
            let L = (L0 + DL) * dr;
            L = L - Math.PI * 2 * INT(L / (Math.PI * 2));
            return INT((L / Math.PI) * 6);
        }

        function getLunarMonth11(yy, timeZone) {
            const off = jdFromDate(31, 12, yy) - 2415021;
            const k = INT(off / 29.530588853);
            let nm = getNewMoonDay(k, timeZone);
            const sunLong = sunLongitude(nm, timeZone);
            if (sunLong >= 9) {
                nm = getNewMoonDay(k - 1, timeZone);
            }
            return nm;
        }

        function getLeapMonthOffset(a11, timeZone) {
            const k = INT((a11 - 2415021.076998695) / 29.530588853 + 0.5);
            let last = 0;
            let i = 1;
            let arc = sunLongitude(getNewMoonDay(k + i, timeZone), timeZone);
            do {
                last = arc;
                i++;
                arc = sunLongitude(getNewMoonDay(k + i, timeZone), timeZone);
            } while (arc !== last && i < 14);
            return i - 1;
        }

        function convertSolar2Lunar(dd, mm, yy, timeZone) {
            const dayNumber = jdFromDate(dd, mm, yy);
            const k = INT((dayNumber - 2415021.076998695) / 29.530588853);
            let monthStart = getNewMoonDay(k + 1, timeZone);
            if (monthStart > dayNumber) {
                monthStart = getNewMoonDay(k, timeZone);
            }
            let a11 = getLunarMonth11(yy, timeZone);
            let b11 = a11;
            let lunarYear;
            if (a11 >= monthStart) {
                lunarYear = yy;
                a11 = getLunarMonth11(yy - 1, timeZone);
            } else {
                lunarYear = yy + 1;
                b11 = getLunarMonth11(yy + 1, timeZone);
            }
            const lunarDay = dayNumber - monthStart + 1;
            const diff = INT((monthStart - a11) / 29);
            let lunarMonth = diff + 11;

            if (b11 - a11 > 365) {
                const leapMonthDiff = getLeapMonthOffset(a11, timeZone);
                if (diff >= leapMonthDiff) {
                    lunarMonth = diff + 10;
                }
            }

            if (lunarMonth > 12) {
                lunarMonth -= 12;
            }
            if (lunarMonth >= 11 && diff < 4) {
                lunarYear -= 1;
            }
            return [lunarDay, lunarMonth, lunarYear];
        }

        function getCanChiYear(year) {
            if (!Number.isInteger(year) || year < 1900 || year > 2100) {
                return '';
            }

            const stem = heavenlyStems[(year + 6) % 10];
            const branch = earthlyBranches[(year + 8) % 12];
            return `${stem} ${branch}`;
        }

        function sumDigits(number) {
            return String(number)
                .split('')
                .reduce((total, digit) => total + parseInt(digit, 10), 0);
        }

        function reduceToSingleDigit(number) {
            let result = number;
            while (result > 9) {
                result = sumDigits(result);
            }
            return result;
        }

        function getCungMenh(solarYear, gender) {
            if (!Number.isInteger(solarYear) || !gender || gender === 'other') {
                return '';
            }

            const yearTail = solarYear % 100;
            const digit = reduceToSingleDigit(yearTail);
            let kuaNumber;

            if (gender === 'male') {
                kuaNumber = solarYear < 2000 ? 10 - digit : 9 - digit;
            } else {
                kuaNumber = solarYear < 2000 ? 5 + digit : 6 + digit;
            }

            if (kuaNumber <= 0) {
                kuaNumber += 9;
            }

            kuaNumber = reduceToSingleDigit(kuaNumber);

            if (kuaNumber === 5) {
                return gender === 'male' ? 'Khôn' : 'Cấn';
            }

            const cungByKua = {
                1: 'Khảm',
                2: 'Khôn',
                3: 'Chấn',
                4: 'Tốn',
                6: 'Càn',
                7: 'Đoài',
                8: 'Cấn',
                9: 'Ly'
            };

            return cungByKua[kuaNumber] || '';
        }

        function syncZodiacInfo(lunarYear, solarYear) {
            if (!Number.isInteger(lunarYear)) {
                if (!solarDateInput.value && !solarYearInput.value) {
                    zodiacInfoInput.value = '';
                }
                return;
            }

            const canChi = getCanChiYear(lunarYear);
            const menh = destinyByCanChi[canChi] || '';
            const cung = getCungMenh(solarYear, genderInput.value);

            const parts = [];
            if (menh) {
                parts.push(`Mệnh ${menh}`);
            }
            if (cung) {
                parts.push(`Cung ${cung}`);
            }

            zodiacInfoInput.value = parts.join(' - ');
        }

        function formatLunarDate(day, month) {
            const dd = String(day).padStart(2, '0');
            const mm = String(month).padStart(2, '0');
            return `${dd}/${mm}`;
        }

        function syncDeathLunarFromSolarDate() {
            if (!deathSolarDateInput || !deathLunarDateInput) {
                return;
            }

            const value = deathSolarDateInput.value;
            if (!value) {
                deathLunarDateInput.value = '';
                if (deathLunarYearInput) {
                    deathLunarYearInput.value = '';
                }
                return;
            }

            const [yearText, monthText, dayText] = value.split('-');
            const year = parseInt(yearText, 10);
            const month = parseInt(monthText, 10);
            const day = parseInt(dayText, 10);
            if (!year || !month || !day) {
                deathLunarDateInput.value = '';
                if (deathLunarYearInput) {
                    deathLunarYearInput.value = '';
                }
                return;
            }

            const [lunarDay, lunarMonth, lunarYear] = convertSolar2Lunar(day, month, year, 7);
            deathLunarDateInput.value = formatLunarDate(lunarDay, lunarMonth);
            if (deathLunarYearInput) {
                deathLunarYearInput.value = getCanChiYear(lunarYear);
            }
        }

        function syncLifeStatusFields() {
            if (!lifeStatusInput || !deathSolarDateWrapper || !deathLunarDateWrapper || !deathLunarYearWrapper) {
                return;
            }

            const isDeceased = lifeStatusInput.value === 'deceased';
            deathSolarDateWrapper.style.display = isDeceased ? 'block' : 'none';
            deathLunarDateWrapper.style.display = isDeceased ? 'block' : 'none';
            deathLunarYearWrapper.style.display = isDeceased ? 'block' : 'none';

            if (deathSolarDateInput) {
                deathSolarDateInput.required = isDeceased;
                if (!isDeceased) {
                    deathSolarDateInput.value = '';
                }
            }

            if (deathLunarDateInput && !isDeceased) {
                deathLunarDateInput.value = '';
            }

            if (deathLunarYearInput && !isDeceased) {
                deathLunarYearInput.value = '';
            }

            if (isDeceased) {
                syncDeathLunarFromSolarDate();
            }
        }

        function syncLunarFromSolarDate() {
            const value = solarDateInput.value;
            if (!value) {
                lunarDateInput.value = '';
                return false;
            }

            const [yearText, monthText, dayText] = value.split('-');
            const year = parseInt(yearText, 10);
            const month = parseInt(monthText, 10);
            const day = parseInt(dayText, 10);

            if (!year || !month || !day) {
                return false;
            }

            const [lunarDay, lunarMonth, lunarYear] = convertSolar2Lunar(day, month, year, 7);
            lunarDateInput.value = formatLunarDate(lunarDay, lunarMonth);
            lunarYearInput.value = getCanChiYear(lunarYear);
            solarYearInput.value = year;
            syncZodiacInfo(lunarYear, year);
            return true;
        }

        function syncLunarYear() {
            const solarYear = parseInt(solarYearInput.value, 10);
            const canChi = getCanChiYear(solarYear);
            lunarYearInput.value = canChi;
            syncZodiacInfo(solarYear, solarYear);
        }

        function syncLunarFields() {
            const hasFullDate = syncLunarFromSolarDate();
            if (!hasFullDate) {
                syncLunarYear();
            }
        }

        solarDateInput.addEventListener('input', syncLunarFields);
        solarDateInput.addEventListener('change', syncLunarFields);
        solarYearInput.addEventListener('input', syncLunarFields);
        solarYearInput.addEventListener('change', syncLunarFields);
        genderInput.addEventListener('change', syncLunarFields);
        if (deathSolarDateInput) {
            deathSolarDateInput.addEventListener('input', syncDeathLunarFromSolarDate);
            deathSolarDateInput.addEventListener('change', syncDeathLunarFromSolarDate);
        }
        if (lifeStatusInput) {
            lifeStatusInput.addEventListener('change', syncLifeStatusFields);
        }

        syncLunarFields();
        syncLifeStatusFields();
    })();
</script>

<script>
    let relationshipRowTemplate = null;

    function sanitizeOptionsHtml(optionsHtml) {
        const tempSelect = document.createElement('select');
        tempSelect.innerHTML = optionsHtml;
        tempSelect.querySelectorAll('option').forEach(option => option.removeAttribute('selected'));
        return tempSelect.innerHTML;
    }

    function initRelationshipSearchableSelect(context) {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn || !window.jQuery.fn.select2) {
            return;
        }

        const root = context || document;
        const selects = root.querySelectorAll('select.relationship-related-contact');
        selects.forEach(select => {
            const $select = window.jQuery(select);
            if ($select.hasClass('select2-hidden-accessible')) {
                return;
            }

            $select.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: select.getAttribute('data-placeholder') || '-- Chọn phật tử --',
                allowClear: true,
            });
        });
    }

    function initRelationshipRowTemplate() {
        const container = document.getElementById('relationships-container');
        if (!container) return;

        const firstRow = container.querySelector('.relationship-row');
        if (!firstRow) return;

        const selects = firstRow.querySelectorAll('select');
        const removeButton = firstRow.querySelector('button');
        if (selects.length < 2 || !removeButton) return;

        relationshipRowTemplate = {
            rowStyle: firstRow.getAttribute('style') || '',
            typeSelectStyle: selects[0].getAttribute('style') || '',
            relatedSelectStyle: selects[1].getAttribute('style') || '',
            removeButtonStyle: removeButton.getAttribute('style') || '',
            typeOptionsHtml: sanitizeOptionsHtml(selects[0].innerHTML),
            relatedOptionsHtml: sanitizeOptionsHtml(selects[1].innerHTML),
        };
    }

    function addRelationshipRow() {
        const container = document.getElementById('relationships-container');
        if (!container) return;

        if (!relationshipRowTemplate) {
            initRelationshipRowTemplate();
        }
        if (!relationshipRowTemplate) return;

        const row = document.createElement('div');
        row.className = 'relationship-row';
        row.setAttribute('style', relationshipRowTemplate.rowStyle);

        const relationSelect = document.createElement('select');
        relationSelect.name = 'relationship_type[]';
        relationSelect.setAttribute('style', relationshipRowTemplate.typeSelectStyle);
        relationSelect.innerHTML = relationshipRowTemplate.typeOptionsHtml;
        relationSelect.value = '';

        const relatedContactSelect = document.createElement('select');
        relatedContactSelect.name = 'related_contact_id[]';
        relatedContactSelect.className = 'relationship-related-contact';
        relatedContactSelect.setAttribute('data-placeholder', '-- Chọn phật tử --');
        relatedContactSelect.setAttribute('style', relationshipRowTemplate.relatedSelectStyle);
        relatedContactSelect.innerHTML = relationshipRowTemplate.relatedOptionsHtml;
        relatedContactSelect.value = '';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger';
        removeButton.setAttribute('style', relationshipRowTemplate.removeButtonStyle);
        removeButton.setAttribute('onclick', 'removeRelationshipRow(this)');
        removeButton.innerHTML = '<i class="fas fa-trash"></i>';

        row.appendChild(relationSelect);
        row.appendChild(relatedContactSelect);
        row.appendChild(removeButton);

        container.appendChild(row);
        initRelationshipSearchableSelect(row);
        syncParentRelationshipLabels();
    }

    function removeRelationshipRow(button) {
        const container = document.getElementById('relationships-container');
        if (!container) return;

        const rows = container.querySelectorAll('.relationship-row');
        if (rows.length <= 1) {
            rows[0].querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            return;
        }

        button.closest('.relationship-row')?.remove();
    }

    function getParentRelationshipLabel() {
        const genderInput = document.getElementById('gender');
        const gender = genderInput ? genderInput.value : '';

        if (gender === 'male') {
            return 'Cha của';
        }

        if (gender === 'female') {
            return 'Mẹ của';
        }

        return 'Cha/Mẹ của';
    }

    function getSpouseRelationshipLabel() {
        const genderInput = document.getElementById('gender');
        const gender = genderInput ? genderInput.value : '';

        if (gender === 'male') {
            return 'Chồng của';
        }

        if (gender === 'female') {
            return 'Vợ của';
        }

        return 'Vợ/Chồng với';
    }

    function syncParentRelationshipLabels() {
        const parentLabel = getParentRelationshipLabel();
        const spouseLabel = getSpouseRelationshipLabel();
        const parentOptions = document.querySelectorAll('select[name="relationship_type[]"] option[value="parent"]');
        parentOptions.forEach(option => {
            option.textContent = parentLabel;
        });

        const spouseOptions = document.querySelectorAll('select[name="relationship_type[]"] option[value="spouse"]');
        spouseOptions.forEach(option => {
            option.textContent = spouseLabel;
        });
    }

    (function() {
        initRelationshipRowTemplate();
        initRelationshipSearchableSelect(document);

        const genderInput = document.getElementById('gender');
        if (genderInput) {
            genderInput.addEventListener('change', syncParentRelationshipLabels);
        }
        syncParentRelationshipLabels();
    })();
</script>
