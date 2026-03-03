import { useMemo } from 'react';
import { Link } from 'react-router-dom';
import { type WorshipEvent } from '../../../mocks/worship-calendar-data';
import { getFollowerById, getFamilyById } from '../../../mocks/followers-data';

interface CalendarViewProps {
  events: WorshipEvent[];
  selectedMonth: number;
  onMonthChange: (month: number) => void;
  filterYear: number;
  onUpdateStatus: (id: string, status: 'upcoming' | 'completed' | 'overdue') => void;
}

const MONTH_NAMES = [
  'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
  'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
];

const DAY_NAMES = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

export default function CalendarView({ events, selectedMonth, onMonthChange, filterYear, onUpdateStatus }: CalendarViewProps) {
  // Tạo lịch cho tháng được chọn
  const calendarDays = useMemo(() => {
    const firstDay = new Date(filterYear, selectedMonth, 1);
    const lastDay = new Date(filterYear, selectedMonth + 1, 0);
    const startDayOfWeek = firstDay.getDay();
    const daysInMonth = lastDay.getDate();

    const days: { date: number | null; events: WorshipEvent[] }[] = [];

    // Ngày trống đầu tháng
    for (let i = 0; i < startDayOfWeek; i++) {
      days.push({ date: null, events: [] });
    }

    // Các ngày trong tháng
    for (let d = 1; d <= daysInMonth; d++) {
      const dateStr = `${filterYear}-${String(selectedMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
      const dayEvents = events.filter(e => e.date_solar === dateStr);
      days.push({ date: d, events: dayEvents });
    }

    return days;
  }, [events, selectedMonth, filterYear]);

  // Sự kiện trong tháng
  const monthEvents = useMemo(() => {
    return events.filter(e => {
      const d = new Date(e.date_solar);
      return d.getMonth() === selectedMonth && d.getFullYear() === filterYear;
    }).sort((a, b) => new Date(a.date_solar).getTime() - new Date(b.date_solar).getTime());
  }, [events, selectedMonth, filterYear]);

  // Sự kiện sắp tới (tất cả tháng)
  const upcomingEvents = useMemo(() => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return events
      .filter(e => e.status === 'upcoming' && new Date(e.date_solar) >= today)
      .sort((a, b) => new Date(a.date_solar).getTime() - new Date(b.date_solar).getTime())
      .slice(0, 8);
  }, [events]);

  const isToday = (day: number | null) => {
    if (!day) return false;
    const today = new Date();
    return today.getDate() === day && today.getMonth() === selectedMonth && today.getFullYear() === filterYear;
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  return (
    <div className="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6">
      {/* Lịch */}
      <div className="xl:col-span-2 bg-white rounded-lg border border-amber-100 shadow-sm overflow-hidden">
        {/* Header tháng */}
        <div className="flex items-center justify-between px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-100">
          <button
            onClick={() => onMonthChange(selectedMonth === 0 ? 11 : selectedMonth - 1)}
            className="p-1.5 md:p-2 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
          >
            <i className="ri-arrow-left-s-line text-lg md:text-xl text-amber-700 w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
          </button>
          <h3 className="text-base md:text-lg font-bold text-amber-900">
            {MONTH_NAMES[selectedMonth]} {filterYear}
          </h3>
          <button
            onClick={() => onMonthChange(selectedMonth === 11 ? 0 : selectedMonth + 1)}
            className="p-1.5 md:p-2 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
          >
            <i className="ri-arrow-right-s-line text-lg md:text-xl text-amber-700 w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
          </button>
        </div>

        {/* Chọn tháng nhanh */}
        <div className="px-3 md:px-6 py-2 md:py-3 border-b border-amber-50 flex gap-1 flex-wrap">
          {MONTH_NAMES.map((name, idx) => (
            <button
              key={idx}
              onClick={() => onMonthChange(idx)}
              className={`px-2 md:px-3 py-1 md:py-1.5 rounded-full text-[10px] md:text-xs font-medium transition-colors cursor-pointer whitespace-nowrap ${
                idx === selectedMonth
                  ? 'bg-amber-600 text-white'
                  : 'text-amber-600 hover:bg-amber-100'
              }`}
            >
              {name}
            </button>
          ))}
        </div>

        {/* Lưới lịch */}
        <div className="p-2 md:p-4">
          <div className="grid grid-cols-7 gap-0.5 md:gap-1 mb-1 md:mb-2">
            {DAY_NAMES.map(d => (
              <div key={d} className="text-center text-[10px] md:text-xs font-semibold text-amber-600 py-1 md:py-2">
                {d}
              </div>
            ))}
          </div>
          <div className="grid grid-cols-7 gap-0.5 md:gap-1">
            {calendarDays.map((day, idx) => (
              <div
                key={idx}
                className={`min-h-[60px] md:min-h-[80px] p-1 md:p-1.5 rounded-lg border transition-colors ${
                  day.date === null
                    ? 'border-transparent'
                    : isToday(day.date)
                    ? 'border-amber-400 bg-amber-50'
                    : day.events.length > 0
                    ? 'border-amber-200 bg-amber-50/50 hover:bg-amber-50'
                    : 'border-gray-100 hover:bg-gray-50'
                }`}
              >
                {day.date !== null && (
                  <>
                    <div className={`text-[10px] md:text-xs font-medium mb-0.5 md:mb-1 ${
                      isToday(day.date) ? 'text-amber-700 font-bold' : 'text-gray-600'
                    }`}>
                      {day.date}
                    </div>
                    <div className="space-y-0.5">
                      {day.events.slice(0, 2).map(e => (
                        <div
                          key={e.id}
                          className={`px-1 md:px-1.5 py-0.5 rounded text-[8px] md:text-[10px] font-medium truncate cursor-default ${
                            e.type === 'gio'
                              ? 'bg-gray-200 text-gray-700'
                              : e.star_type === 'bad'
                              ? 'bg-red-100 text-red-700'
                              : e.star_type === 'good'
                              ? 'bg-green-100 text-green-700'
                              : 'bg-amber-100 text-amber-700'
                          }`}
                          title={e.title}
                        >
                          {e.type === 'gio' ? '🕯️' : '⭐'} {getFollowerById(e.follower_id)?.full_name || ''}
                        </div>
                      ))}
                      {day.events.length > 2 && (
                        <div className="text-[8px] md:text-[10px] text-amber-500 font-medium px-1">
                          +{day.events.length - 2} khác
                        </div>
                      )}
                    </div>
                  </>
                )}
              </div>
            ))}
          </div>
        </div>

        {/* Chú thích */}
        <div className="px-3 md:px-6 py-2 md:py-3 border-t border-amber-50 flex items-center gap-2 md:gap-4 flex-wrap">
          <div className="flex items-center gap-1 md:gap-1.5 text-[10px] md:text-xs text-gray-600">
            <span className="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-gray-200 inline-block"></span> Cúng giỗ
          </div>
          <div className="flex items-center gap-1 md:gap-1.5 text-[10px] md:text-xs text-gray-600">
            <span className="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-red-100 inline-block"></span> Sao xấu
          </div>
          <div className="flex items-center gap-1 md:gap-1.5 text-[10px] md:text-xs text-gray-600">
            <span className="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-green-100 inline-block"></span> Sao tốt
          </div>
          <div className="flex items-center gap-1 md:gap-1.5 text-[10px] md:text-xs text-gray-600">
            <span className="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-amber-100 inline-block"></span> Sao trung bình
          </div>
        </div>
      </div>

      {/* Sidebar - Sự kiện trong tháng & sắp tới */}
      <div className="space-y-4 md:space-y-6">
        {/* Sự kiện trong tháng */}
        <div className="bg-white rounded-lg border border-amber-100 shadow-sm overflow-hidden">
          <div className="px-4 md:px-5 py-3 md:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-100">
            <h4 className="font-bold text-amber-900 text-xs md:text-sm">
              Sự kiện {MONTH_NAMES[selectedMonth]}
            </h4>
            <p className="text-[10px] md:text-xs text-amber-600 mt-0.5">{monthEvents.length} sự kiện</p>
          </div>
          <div className="max-h-[280px] md:max-h-[320px] overflow-y-auto">
            {monthEvents.length === 0 ? (
              <div className="p-4 md:p-6 text-center">
                <i className="ri-calendar-line text-2xl md:text-3xl text-amber-200"></i>
                <p className="text-xs md:text-sm text-amber-500 mt-2">Không có sự kiện trong tháng này</p>
              </div>
            ) : (
              <div className="divide-y divide-amber-50">
                {monthEvents.map(e => {
                  const follower = getFollowerById(e.follower_id);
                  return (
                    <div key={e.id} className="px-4 md:px-5 py-2.5 md:py-3 hover:bg-amber-50/50 transition-colors">
                      <div className="flex items-start gap-2 md:gap-3">
                        <div className={`w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 ${
                          e.type === 'gio' ? 'bg-gray-100' : 'bg-amber-100'
                        }`}>
                          <i className={`${e.type === 'gio' ? 'ri-ghost-smile-line text-gray-600' : 'ri-star-fill text-amber-600'} text-xs md:text-sm w-3.5 h-3.5 md:w-4 md:h-4 flex items-center justify-center`}></i>
                        </div>
                        <div className="flex-1 min-w-0">
                          <p className="text-xs md:text-sm font-medium text-amber-900 truncate">{follower?.full_name}</p>
                          <p className="text-[10px] md:text-xs text-amber-600 mt-0.5">
                            {e.type === 'gio' ? 'Cúng giỗ' : `Cúng sao ${e.star_name}`}
                          </p>
                          <div className="flex items-center gap-2 mt-1">
                            <span className="text-[10px] md:text-xs text-gray-500">
                              {new Date(e.date_solar).toLocaleDateString('vi-VN')}
                            </span>
                            <span className={`px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-medium ${
                              e.status === 'completed' ? 'bg-green-100 text-green-700' :
                              e.status === 'overdue' ? 'bg-red-100 text-red-700' :
                              'bg-amber-100 text-amber-700'
                            }`}>
                              {e.status === 'completed' ? 'Đã cúng' : e.status === 'overdue' ? 'Quá hạn' : 'Sắp tới'}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            )}
          </div>
        </div>

        {/* Sắp tới */}
        <div className="bg-white rounded-lg border border-amber-100 shadow-sm overflow-hidden">
          <div className="px-4 md:px-5 py-3 md:py-4 bg-gradient-to-r from-orange-50 to-red-50 border-b border-orange-100">
            <h4 className="font-bold text-orange-900 text-xs md:text-sm flex items-center gap-2">
              <i className="ri-alarm-warning-line w-3.5 h-3.5 md:w-4 md:h-4 flex items-center justify-center text-orange-600"></i>
              Sắp tới gần nhất
            </h4>
          </div>
          <div className="max-h-[280px] md:max-h-[320px] overflow-y-auto">
            {upcomingEvents.length === 0 ? (
              <div className="p-4 md:p-6 text-center">
                <i className="ri-checkbox-circle-line text-2xl md:text-3xl text-green-200"></i>
                <p className="text-xs md:text-sm text-green-600 mt-2">Không có sự kiện sắp tới</p>
              </div>
            ) : (
              <div className="divide-y divide-amber-50">
                {upcomingEvents.map(e => {
                  const follower = getFollowerById(e.follower_id);
                  const daysLeft = Math.ceil((new Date(e.date_solar).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24));
                  return (
                    <div key={e.id} className="px-4 md:px-5 py-2.5 md:py-3 hover:bg-amber-50/50 transition-colors">
                      <div className="flex items-center justify-between gap-2">
                        <div className="flex-1 min-w-0">
                          <p className="text-xs md:text-sm font-medium text-amber-900 truncate">{follower?.full_name}</p>
                          <p className="text-[10px] md:text-xs text-amber-600">
                            {e.type === 'gio' ? '🕯️ Cúng giỗ' : `⭐ ${e.star_name}`} - {e.date_lunar}
                          </p>
                        </div>
                        <div className="flex items-center gap-1.5 md:gap-2">
                          <span className={`px-1.5 md:px-2 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold whitespace-nowrap ${
                            daysLeft <= 7 ? 'bg-red-100 text-red-700' :
                            daysLeft <= 30 ? 'bg-amber-100 text-amber-700' :
                            'bg-green-100 text-green-700'
                          }`}>
                            {daysLeft} ngày
                          </span>
                          <button
                            onClick={() => onUpdateStatus(e.id, 'completed')}
                            className="p-1 text-green-600 hover:bg-green-100 rounded transition-colors cursor-pointer"
                            title="Đánh dấu đã cúng"
                          >
                            <i className="ri-checkbox-circle-line w-3.5 h-3.5 md:w-4 md:h-4 flex items-center justify-center"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
