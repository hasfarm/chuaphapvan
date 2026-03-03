@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-week" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Lịch Sự Kiện
        </h1>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i>
                Danh Sách
            </a>
            <button type="button" id="open-create-event-modal" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm Sự Kiện
            </button>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">

    <div style="background: var(--white); padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <div id="events-calendar"></div>
    </div>

    <div id="event-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.45); z-index: 2000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="width: min(760px, 96vw); max-height: 92vh; overflow: auto; background: var(--white); border-radius: 1rem; box-shadow: var(--shadow-lg);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding: 1rem 1.25rem; border-bottom: 1px solid var(--light-gray);">
                <h3 id="event-modal-title" style="margin:0; font-size:1.1rem;">Thêm sự kiện</h3>
                <button type="button" id="close-event-modal" class="btn btn-secondary" style="padding: 0.4rem 0.7rem;">×</button>
            </div>

            <form id="event-calendar-form" style="padding: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 0.9rem;">
                <input type="hidden" id="calendar_event_id">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="event_name" value="">

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Loại sự kiện</label>
                    <select name="event_type" id="event_type" data-no-select2="1" style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                        <option value="">-- Chọn loại sự kiện --</option>
                        <option value="Đại lễ">Đại lễ</option>
                        <option value="Khóa tu">Khóa tu</option>
                        <option value="Công quả">Công quả</option>
                        <option value="Lễ cầu an">Lễ cầu an</option>
                        <option value="Lễ cầu siêu">Lễ cầu siêu</option>
                        <option value="Pháp thoại">Pháp thoại</option>
                        <option value="Sinh hoạt đạo tràng">Sinh hoạt đạo tràng</option>
                        <option value="Từ thiện">Từ thiện</option>
                        <option value="Họp nội bộ">Họp nội bộ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>

                <input type="hidden" name="event_year" id="event_year">

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Ngày diễn ra (DL)</label>
                    <input type="date" name="event_date" id="event_date" style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                    <div class="event-form-error" data-error="event_date" style="display:none; color:#dc2626; font-size:0.85rem; margin-top:0.2rem;"></div>
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Từ giờ</label>
                    <input type="time" name="event_start_time" id="event_start_time" style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                    <div class="event-form-error" data-error="event_start_time" style="display:none; color:#dc2626; font-size:0.85rem; margin-top:0.2rem;"></div>
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Đến giờ</label>
                    <input type="time" name="event_end_time" id="event_end_time" style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                    <div class="event-form-error" data-error="event_end_time" style="display:none; color:#dc2626; font-size:0.85rem; margin-top:0.2rem;"></div>
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Ngày diễn ra (AL)</label>
                    <input type="text" name="event_lunar_date" id="event_lunar_date" readonly style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Năm diễn ra (AL)</label>
                    <input type="text" name="event_lunar_year" id="event_lunar_year" readonly style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Địa điểm</label>
                    <input type="text" name="location" id="location" style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Trạng thái <span style="color:#dc2626;">*</span></label>
                    <select name="status" id="status" data-no-select2="1" required style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;">
                        <option value="upcoming">Sắp diễn ra</option>
                        <option value="ongoing">Đang diễn ra</option>
                        <option value="completed">Đã hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                    <div class="event-form-error" data-error="status" style="display:none; color:#dc2626; font-size:0.85rem; margin-top:0.2rem;"></div>
                </div>

                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <label style="display:flex; align-items:center; gap:0.5rem; font-weight:600;">
                        <input type="checkbox" name="is_annual" id="is_annual" value="1" checked>
                        Lặp lại hằng năm
                    </label>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="display:block; margin-bottom:0.35rem; font-weight:600;">Mô tả</label>
                    <textarea name="description" id="description" rows="3" style="width:100%; padding:0.75rem; border:2px solid var(--light-gray); border-radius:0.5rem;"></textarea>
                </div>

                <div style="grid-column: 1 / -1; display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-top:0.25rem;">
                    <a href="#" id="open-event-detail-link" class="btn btn-secondary" style="display:none;">Xem chi tiết</a>
                    <div style="display:flex; gap:0.75rem; margin-left:auto;">
                        <button type="button" id="cancel-event-modal" class="btn btn-secondary">Hủy</button>
                        <button type="submit" id="save-event-modal" class="btn btn-primary">Lưu sự kiện</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('events-calendar');
            if (!calendarEl) {
                return;
            }

            const backdrop = document.getElementById('event-modal-backdrop');
            const form = document.getElementById('event-calendar-form');
            const openCreateButton = document.getElementById('open-create-event-modal');
            const closeModalButton = document.getElementById('close-event-modal');
            const cancelModalButton = document.getElementById('cancel-event-modal');
            const modalTitle = document.getElementById('event-modal-title');
            const detailLink = document.getElementById('open-event-detail-link');
            const eventIdInput = document.getElementById('calendar_event_id');
            const eventYearInput = document.getElementById('event_year');
            const eventTypeInput = document.getElementById('event_type');
            const eventDateInput = document.getElementById('event_date');
            const eventStartTimeInput = document.getElementById('event_start_time');
            const eventEndTimeInput = document.getElementById('event_end_time');
            const eventLunarDateInput = document.getElementById('event_lunar_date');
            const eventLunarYearInput = document.getElementById('event_lunar_year');
            const locationInput = document.getElementById('location');
            const statusInput = document.getElementById('status');
            const isAnnualInput = document.getElementById('is_annual');
            const descriptionInput = document.getElementById('description');
            const saveButton = document.getElementById('save-event-modal');

            const eventTypeColors = {
                'Đại lễ': { backgroundColor: '#dcfce7', borderColor: '#16a34a', textColor: '#166534' },
                'Khóa tu': { backgroundColor: '#dbeafe', borderColor: '#2563eb', textColor: '#1e3a8a' },
                'Công quả': { backgroundColor: '#ffedd5', borderColor: '#ea580c', textColor: '#9a3412' },
                'Lễ cầu an': { backgroundColor: '#e0e7ff', borderColor: '#4f46e5', textColor: '#312e81' },
                'Lễ cầu siêu': { backgroundColor: '#fce7f3', borderColor: '#db2777', textColor: '#9d174d' },
                'Pháp thoại': { backgroundColor: '#ccfbf1', borderColor: '#0d9488', textColor: '#134e4a' },
                'Sinh hoạt đạo tràng': { backgroundColor: '#f3e8ff', borderColor: '#9333ea', textColor: '#581c87' },
                'Từ thiện': { backgroundColor: '#fef3c7', borderColor: '#d97706', textColor: '#92400e' },
                'Họp nội bộ': { backgroundColor: '#e5e7eb', borderColor: '#6b7280', textColor: '#1f2937' },
                'Khác': { backgroundColor: '#ecfeff', borderColor: '#0891b2', textColor: '#164e63' },
            };

            const events = (@json($events) || []).map(function(eventItem) {
                const eventType = eventItem.extendedProps?.event_type || 'Khác';
                const colors = eventTypeColors[eventType] || eventTypeColors['Khác'];
                return {
                    ...eventItem,
                    ...colors,
                };
            });

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

            function getLunarDateInfo(dateObj) {
                if (!(dateObj instanceof Date)) {
                    return null;
                }

                const year = dateObj.getFullYear();
                const month = dateObj.getMonth() + 1;
                const day = dateObj.getDate();
                const [lunarDay, lunarMonth] = convertSolar2Lunar(day, month, year, 7);

                return {
                    lunarDay: lunarDay,
                    lunarMonth: lunarMonth,
                    label: `${String(lunarDay).padStart(2, '0')}/${String(lunarMonth).padStart(2, '0')}`,
                };
            }

            function syncLunarDate() {
                const value = eventDateInput.value;
                if (!value) {
                    eventLunarDateInput.value = '';
                    eventLunarYearInput.value = '';
                    eventYearInput.value = '';
                    return;
                }

                const [yearText, monthText, dayText] = value.split('-');
                const year = parseInt(yearText, 10);
                const month = parseInt(monthText, 10);
                const day = parseInt(dayText, 10);

                if (!year || !month || !day) {
                    eventLunarDateInput.value = '';
                    eventLunarYearInput.value = '';
                    eventYearInput.value = '';
                    return;
                }

                const [lunarDay, lunarMonth, lunarYear] = convertSolar2Lunar(day, month, year, 7);
                eventLunarDateInput.value = `${String(lunarDay).padStart(2, '0')}/${String(lunarMonth).padStart(2, '0')}`;
                eventLunarYearInput.value = getLunarYearCanChi(lunarYear);
                eventYearInput.value = String(year);
            }

            function clearErrors() {
                document.querySelectorAll('.event-form-error').forEach(function(el) {
                    el.style.display = 'none';
                    el.textContent = '';
                });
            }

            function showErrors(errors) {
                clearErrors();
                Object.keys(errors || {}).forEach(function(field) {
                    const target = document.querySelector(`.event-form-error[data-error="${field}"]`);
                    if (target) {
                        target.textContent = errors[field][0] || 'Dữ liệu không hợp lệ';
                        target.style.display = 'block';
                    }
                });
            }

            function openModal(mode, payload) {
                clearErrors();
                if (mode === 'edit' && payload) {
                    modalTitle.textContent = 'Chỉnh sửa sự kiện';
                    eventIdInput.value = payload.id || '';
                    eventTypeInput.value = payload.extendedProps?.event_type || '';
                    eventDateInput.value = payload.event_date || payload.start || '';
                    eventStartTimeInput.value = payload.event_start_time || '';
                    eventEndTimeInput.value = payload.event_end_time || '';
                    locationInput.value = payload.location || '';
                    statusInput.value = payload.status || 'upcoming';
                    isAnnualInput.checked = !!payload.is_annual;
                    descriptionInput.value = payload.description || '';
                    detailLink.href = payload.url || '#';
                    detailLink.style.display = payload.url ? 'inline-flex' : 'none';
                } else {
                    modalTitle.textContent = 'Thêm sự kiện';
                    eventIdInput.value = '';
                    form.reset();
                    statusInput.value = 'upcoming';
                    isAnnualInput.checked = true;
                    detailLink.style.display = 'none';
                    detailLink.href = '#';
                    if (payload?.event_date) {
                        eventDateInput.value = payload.event_date;
                    }
                }

                syncLunarDate();
                backdrop.style.display = 'flex';
            }

            function closeModal() {
                backdrop.style.display = 'none';
            }

            openCreateButton.addEventListener('click', function() {
                openModal('create');
            });

            closeModalButton.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', closeModal);
            backdrop.addEventListener('click', function(event) {
                if (event.target === backdrop) {
                    closeModal();
                }
            });

            eventDateInput.addEventListener('input', syncLunarDate);
            eventDateInput.addEventListener('change', syncLunarDate);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                height: 'auto',
                slotMinTime: '04:00:00',
                slotMaxTime: '23:00:00',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hôm nay',
                    month: 'Tháng',
                    week: 'Tuần',
                    day: 'Ngày'
                },
                dayCellDidMount: function(info) {
                    if (info.view.type !== 'dayGridMonth') {
                        return;
                    }

                    const dayTop = info.el.querySelector('.fc-daygrid-day-top');
                    if (!dayTop || dayTop.querySelector('.fc-lunar-date')) {
                        return;
                    }

                    const lunarInfo = getLunarDateInfo(info.date);
                    if (!lunarInfo) {
                        return;
                    }

                    const isLunarSpecialDay = lunarInfo.lunarDay === 1 || lunarInfo.lunarDay === 15;
                    if (isLunarSpecialDay) {
                        info.el.style.background = 'var(--light-orange)';
                        info.el.style.boxShadow = 'inset 0 0 0 1px var(--dark-orange)';
                    }

                    const lunarElement = document.createElement('div');
                    lunarElement.className = 'fc-lunar-date';
                    lunarElement.textContent = `${lunarInfo.label} AL`;
                    lunarElement.style.fontSize = '0.72rem';
                    lunarElement.style.lineHeight = '1.1';
                    lunarElement.style.color = isLunarSpecialDay ? 'var(--dark-orange)' : '#6b7280';
                    lunarElement.style.marginTop = '0.15rem';
                    lunarElement.style.textAlign = 'right';
                    lunarElement.style.width = '100%';
                    lunarElement.style.fontWeight = isLunarSpecialDay ? '700' : '500';

                    dayTop.appendChild(lunarElement);
                },
                events: events,
                dateClick: function(info) {
                    openModal('create', {
                        event_date: info.dateStr,
                    });
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const payload = {
                        id: info.event.id,
                        start: info.event.startStr,
                        url: info.event.url,
                        event_date: info.event.extendedProps.event_date || info.event.startStr,
                        event_start_time: info.event.extendedProps.event_start_time,
                        event_end_time: info.event.extendedProps.event_end_time,
                        event_lunar_date: info.event.extendedProps.event_lunar_date,
                        event_lunar_year: info.event.extendedProps.event_lunar_year,
                        location: info.event.extendedProps.location,
                        description: info.event.extendedProps.description,
                        status: info.event.extendedProps.status,
                        is_annual: info.event.extendedProps.is_annual,
                        extendedProps: {
                            event_type: info.event.extendedProps.event_type,
                        },
                    };
                    openModal('edit', payload);
                }
            });

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                clearErrors();

                const id = eventIdInput.value;
                const isEdit = !!id;
                const endpoint = isEdit
                    ? `{{ url('admin/events') }}/${id}/calendar`
                    : `{{ route('admin.events.calendar.store') }}`;

                const formData = new FormData(form);
                if (isEdit) {
                    formData.append('_method', 'PUT');
                }
                if (!isAnnualInput.checked) {
                    formData.delete('is_annual');
                }

                saveButton.disabled = true;
                saveButton.textContent = 'Đang lưu...';

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData,
                    });

                    const result = await response.json();
                    if (!response.ok) {
                        showErrors(result.errors || {});
                        return;
                    }

                    const savedEvent = result.event;
                    const eventType = savedEvent.extendedProps?.event_type || 'Khác';
                    const colors = eventTypeColors[eventType] || eventTypeColors['Khác'];
                    const eventData = {
                        ...savedEvent,
                        ...colors,
                    };

                    const oldEvent = isEdit ? calendar.getEventById(String(savedEvent.id)) : null;
                    if (oldEvent) {
                        oldEvent.remove();
                    }
                    calendar.addEvent(eventData);
                    closeModal();
                } catch (error) {
                    alert('Không thể lưu sự kiện. Vui lòng thử lại.');
                } finally {
                    saveButton.disabled = false;
                    saveButton.textContent = 'Lưu sự kiện';
                }
            });

            calendar.render();
        });
    </script>
@endsection
