@php
    $isEdit = isset($event) && $event;
    $eventTypeOptions = [
        'Đại lễ',
        'Khóa tu',
        'Công quả',
        'Lễ cầu an',
        'Lễ cầu siêu',
        'Pháp thoại',
        'Sinh hoạt đạo tràng',
        'Từ thiện',
        'Họp nội bộ',
        'Khác',
    ];
    $selectedEventType = old('event_type', $event->event_type ?? '');
    $selectedFamilyIds = collect(old('family_ids', isset($event) ? $event->families?->pluck('id')->all() : []))
        ->map(fn ($id) => (string) $id)
        ->all();
    $selectedContactIds = collect(old('contact_ids', isset($event) ? $event->contacts?->pluck('id')->all() : []))
        ->map(fn ($id) => (string) $id)
        ->all();
@endphp

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem;">
    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Loại sự kiện</label>
        <select name="event_type"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
            <option value="">-- Chọn loại sự kiện --</option>
            @foreach ($eventTypeOptions as $eventType)
                <option value="{{ $eventType }}" {{ $selectedEventType === $eventType ? 'selected' : '' }}>{{ $eventType }}</option>
            @endforeach
            @if ($selectedEventType && !in_array($selectedEventType, $eventTypeOptions, true))
                <option value="{{ $selectedEventType }}" selected>{{ $selectedEventType }}</option>
            @endif
        </select>
    </div>

    <input type="hidden" name="event_name" value="{{ old('event_name', $event->event_name ?? '') }}">

    <input type="hidden" name="event_year" value="{{ old('event_year', $event->event_year ?? now()->year) }}">

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ngày diễn ra (DL)</label>
        <input type="date" id="event_date" name="event_date" value="{{ old('event_date', isset($event->event_date) ? $event->event_date->format('Y-m-d') : '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('event_date') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('event_date')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Từ giờ</label>
        <input type="time" name="event_start_time" value="{{ old('event_start_time', isset($event->event_start_time) ? \Carbon\Carbon::parse($event->event_start_time)->format('H:i') : '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('event_start_time') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('event_start_time')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Đến giờ</label>
        <input type="time" name="event_end_time" value="{{ old('event_end_time', isset($event->event_end_time) ? \Carbon\Carbon::parse($event->event_end_time)->format('H:i') : '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('event_end_time') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('event_end_time')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Ngày diễn ra (AL)</label>
        <input type="text" id="event_lunar_date" name="event_lunar_date" value="{{ old('event_lunar_date', $event->event_lunar_date ?? '') }}"
            placeholder="Tự động từ ngày dương lịch" readonly
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Năm diễn ra (AL)</label>
        <input type="text" id="event_lunar_year" name="event_lunar_year" value="{{ old('event_lunar_year', $event->event_lunar_year ?? '') }}"
            placeholder="Tự động từ ngày dương lịch" readonly
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('event_lunar_year') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
        @error('event_lunar_year')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Địa điểm</label>
        <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">
    </div>

    <div style="grid-column: 1 / -1; background: #f9fafb; border: 1px solid var(--light-gray); border-radius: 0.75rem; padding: 1rem;">
        <div style="font-weight: 700; margin-bottom: 0.75rem;">Thành phần tham gia</div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            <div>
                <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Gia đình tham gia</label>
                <select id="family_participant_ids" name="family_ids[]" multiple size="6"
                    style="width: 100%; padding: 0.55rem; border: 2px solid var(--light-gray); border-radius: 0.5rem; background: #fff;">
                    @foreach (($families ?? collect()) as $familyOption)
                        <option value="{{ $familyOption->id }}" {{ in_array((string) $familyOption->id, $selectedFamilyIds, true) ? 'selected' : '' }}>
                            {{ $familyOption->family_name }}{{ $familyOption->family_code ? ' (' . $familyOption->family_code . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Cá nhân tham gia</label>
                <select id="contact_participant_ids" name="contact_ids[]" multiple size="6"
                    style="width: 100%; padding: 0.55rem; border: 2px solid var(--light-gray); border-radius: 0.5rem; background: #fff;">
                    @foreach (($contacts ?? collect()) as $contactOption)
                        <option value="{{ $contactOption->id }}" {{ in_array((string) $contactOption->id, $selectedContactIds, true) ? 'selected' : '' }}>
                            {{ $contactOption->full_name }}{{ $contactOption->dharma_name ? ' (' . $contactOption->dharma_name . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div>
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Trạng thái <span style="color: #dc2626;">*</span></label>
        <select name="status" required
            style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('status') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem;">
            <option value="upcoming" {{ old('status', $event->status ?? 'upcoming') === 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
            <option value="ongoing" {{ old('status', $event->status ?? 'upcoming') === 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
            <option value="completed" {{ old('status', $event->status ?? 'upcoming') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
            <option value="cancelled" {{ old('status', $event->status ?? 'upcoming') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
        </select>
        @error('status')<div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem;">
        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            <input type="checkbox" name="is_annual" value="1" {{ old('is_annual', $event->is_annual ?? true) ? 'checked' : '' }}>
            Lặp lại hằng năm
        </label>
    </div>

    <div style="grid-column: 1 / -1;">
        <label style="display: block; margin-bottom: 0.35rem; font-weight: 600;">Mô tả</label>
        <textarea name="description" rows="4"
            style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem;">{{ old('description', $event->description ?? '') }}</textarea>
    </div>
</div>

<script>
    (function() {
        const eventDateInput = document.getElementById('event_date');
        const eventLunarDateInput = document.getElementById('event_lunar_date');
        const eventLunarYearInput = document.getElementById('event_lunar_year');

        if (!eventDateInput || !eventLunarDateInput || !eventLunarYearInput) {
            return;
        }

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

        function getLunarYearCanChi(year) {
            const can = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
            const chi = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
            return `${can[(year + 6) % 10]} ${chi[(year + 8) % 12]}`;
        }

        function syncLunarDate() {
            const value = eventDateInput.value;
            if (!value) {
                eventLunarDateInput.value = '';
                eventLunarYearInput.value = '';
                return;
            }

            const [yearText, monthText, dayText] = value.split('-');
            const year = parseInt(yearText, 10);
            const month = parseInt(monthText, 10);
            const day = parseInt(dayText, 10);

            if (!year || !month || !day) {
                eventLunarDateInput.value = '';
                eventLunarYearInput.value = '';
                return;
            }

            const [lunarDay, lunarMonth, lunarYear] = convertSolar2Lunar(day, month, year, 7);
            const dd = String(lunarDay).padStart(2, '0');
            const mm = String(lunarMonth).padStart(2, '0');
            eventLunarDateInput.value = `${dd}/${mm}`;
            eventLunarYearInput.value = getLunarYearCanChi(lunarYear);
        }

        eventDateInput.addEventListener('input', syncLunarDate);
        eventDateInput.addEventListener('change', syncLunarDate);
        syncLunarDate();
    })();
</script>

<div style="display: flex; gap: 1rem; margin-top: 2rem;">
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary" style="flex: 1; justify-content: center;">
        <i class="fas fa-times"></i>
        Hủy
    </a>
    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
        <i class="fas fa-save"></i>
        {{ $isEdit ? 'Cập Nhật' : 'Tạo Mới' }}
    </button>
</div>
