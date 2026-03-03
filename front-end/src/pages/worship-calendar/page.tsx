import { useState, useMemo } from 'react';
import { Link } from 'react-router-dom';
import MainLayout from '../../components/layout/MainLayout';
import CalendarView from './components/CalendarView';
import WorshipListView from './components/WorshipListView';
import WorshipStats from './components/WorshipStats';
import { allWorshipEvents, type WorshipEvent } from '../../mocks/worship-calendar-data';
import { followersData, familiesData, getFollowerById, getFamilyById } from '../../mocks/followers-data';
import { calculateStar } from '../../mocks/star-worship-data';

export default function WorshipCalendarPage() {
  const [events, setEvents] = useState<WorshipEvent[]>(allWorshipEvents);
  const [activeTab, setActiveTab] = useState<'calendar' | 'list' | 'deceased'>('calendar');
  const [filterYear, setFilterYear] = useState<number>(2025);
  const [filterType, setFilterType] = useState<'all' | 'gio' | 'sao'>('all');
  const [filterFamily, setFilterFamily] = useState<string>('all');
  const [filterStatus, setFilterStatus] = useState<string>('all');
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedMonth, setSelectedMonth] = useState<number>(new Date().getMonth());

  // Lọc sự kiện
  const filteredEvents = useMemo(() => {
    return events.filter(e => {
      const follower = getFollowerById(e.follower_id);
      const family = getFamilyById(e.family_id);
      const matchYear = e.year === filterYear;
      const matchType = filterType === 'all' || e.type === filterType;
      const matchFamily = filterFamily === 'all' || e.family_id === filterFamily;
      const matchStatus = filterStatus === 'all' || e.status === filterStatus;
      const matchSearch = !searchTerm ||
        follower?.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        follower?.dharma_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        family?.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        e.title.toLowerCase().includes(searchTerm.toLowerCase());
      return matchYear && matchType && matchFamily && matchStatus && matchSearch;
    });
  }, [events, filterYear, filterType, filterFamily, filterStatus, searchTerm]);

  // Danh sách Phật tử đã mất với thông tin ngày giỗ
  const deceasedFollowers = useMemo(() => {
    return followersData
      .filter(f => f.status === 'deceased' && f.death_date_lunar)
      .map(f => {
        const family = getFamilyById(f.family_id);
        const gioEvents = events.filter(e => e.type === 'gio' && e.follower_id === f.id && e.year === filterYear);
        return { ...f, family, gioEvents };
      });
  }, [events, filterYear]);

  // Danh sách Phật tử còn sống với sao chiếu mệnh
  const aliveFollowersWithStar = useMemo(() => {
    return followersData
      .filter(f => f.status === 'alive')
      .map(f => {
        const family = getFamilyById(f.family_id);
        const star = calculateStar(f.birth_year_solar, filterYear, f.gender);
        const saoEvents = events.filter(e => e.type === 'sao' && e.follower_id === f.id && e.year === filterYear);
        return { ...f, family, star, saoEvents };
      });
  }, [events, filterYear]);

  // Thống kê
  const stats = useMemo(() => {
    const yearEvents = events.filter(e => e.year === filterYear);
    const gioEvents = yearEvents.filter(e => e.type === 'gio');
    const saoEvents = yearEvents.filter(e => e.type === 'sao');
    return {
      totalGio: gioEvents.length,
      totalSao: saoEvents.length,
      completed: yearEvents.filter(e => e.status === 'completed').length,
      upcoming: yearEvents.filter(e => e.status === 'upcoming').length,
      overdue: yearEvents.filter(e => e.status === 'overdue').length,
      totalAmount: yearEvents.filter(e => e.status === 'completed').reduce((s, e) => s + e.amount, 0),
      saoBad: saoEvents.filter(e => e.star_type === 'bad').length,
      saoGood: saoEvents.filter(e => e.star_type === 'good').length,
    };
  }, [events, filterYear]);

  const handleUpdateStatus = (id: string, status: 'upcoming' | 'completed' | 'overdue') => {
    setEvents(prev => prev.map(e => e.id === id ? { ...e, status } : e));
  };

  return (
    <MainLayout title="Lịch Cúng Giỗ & Cúng Sao" subtitle="Theo dõi lịch cúng giỗ và cúng sao cho tất cả Phật tử">
      <div className="space-y-4 md:space-y-6">
        {/* Thống kê */}
        <WorshipStats stats={stats} filterYear={filterYear} />

        {/* Tabs */}
        <div className="bg-white rounded-lg border border-amber-100 shadow-sm">
          <div className="flex border-b border-amber-100 overflow-x-auto">
            <button
              onClick={() => setActiveTab('calendar')}
              className={`flex items-center gap-2 px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium transition-colors cursor-pointer whitespace-nowrap ${
                activeTab === 'calendar'
                  ? 'text-amber-700 border-b-2 border-amber-600 bg-amber-50'
                  : 'text-amber-500 hover:text-amber-700 hover:bg-amber-50'
              }`}
            >
              <i className="ri-calendar-2-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
              Lịch tổng hợp
            </button>
            <button
              onClick={() => setActiveTab('list')}
              className={`flex items-center gap-2 px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium transition-colors cursor-pointer whitespace-nowrap ${
                activeTab === 'list'
                  ? 'text-amber-700 border-b-2 border-amber-600 bg-amber-50'
                  : 'text-amber-500 hover:text-amber-700 hover:bg-amber-50'
              }`}
            >
              <i className="ri-list-check-2 w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
              Danh sách chi tiết
            </button>
            <button
              onClick={() => setActiveTab('deceased')}
              className={`flex items-center gap-2 px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium transition-colors cursor-pointer whitespace-nowrap ${
                activeTab === 'deceased'
                  ? 'text-amber-700 border-b-2 border-amber-600 bg-amber-50'
                  : 'text-amber-500 hover:text-amber-700 hover:bg-amber-50'
              }`}
            >
              <i className="ri-ghost-smile-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
              Phật tử đã mất
            </button>
          </div>

          {/* Bộ lọc */}
          <div className="p-4 md:p-6 border-b border-amber-50">
            <div className="flex flex-col gap-3 md:gap-4">
              <div className="w-full">
                <div className="relative">
                  <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-amber-400 w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
                  <input
                    type="text"
                    placeholder="Tìm kiếm theo tên Phật tử, pháp danh, gia đình..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-9 md:pl-10 pr-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm"
                  />
                </div>
              </div>
              <div className="grid grid-cols-2 md:flex gap-2 md:gap-3">
                <select
                  value={filterYear}
                  onChange={(e) => setFilterYear(parseInt(e.target.value))}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value={2023}>Năm 2023</option>
                  <option value={2024}>Năm 2024</option>
                  <option value={2025}>Năm 2025</option>
                  <option value={2026}>Năm 2026</option>
                </select>
                <select
                  value={filterType}
                  onChange={(e) => setFilterType(e.target.value as 'all' | 'gio' | 'sao')}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value="all">Tất cả loại</option>
                  <option value="gio">Cúng giỗ</option>
                  <option value="sao">Cúng sao</option>
                </select>
                <select
                  value={filterFamily}
                  onChange={(e) => setFilterFamily(e.target.value)}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value="all">Tất cả gia đình</option>
                  {familiesData.map(f => (
                    <option key={f.id} value={f.id}>{f.name}</option>
                  ))}
                </select>
                <select
                  value={filterStatus}
                  onChange={(e) => setFilterStatus(e.target.value)}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value="all">Tất cả trạng thái</option>
                  <option value="upcoming">Sắp tới</option>
                  <option value="completed">Đã cúng</option>
                  <option value="overdue">Quá hạn</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        {/* Nội dung theo tab */}
        {activeTab === 'calendar' && (
          <CalendarView
            events={filteredEvents}
            selectedMonth={selectedMonth}
            onMonthChange={setSelectedMonth}
            filterYear={filterYear}
            onUpdateStatus={handleUpdateStatus}
          />
        )}

        {activeTab === 'list' && (
          <WorshipListView
            events={filteredEvents}
            onUpdateStatus={handleUpdateStatus}
          />
        )}

        {activeTab === 'deceased' && (
          <div className="space-y-4 md:space-y-6">
            {/* Danh sách Phật tử đã mất */}
            <div className="bg-white rounded-lg border border-amber-100 shadow-sm overflow-hidden">
              <div className="bg-gradient-to-r from-gray-50 to-gray-100 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <h3 className="font-bold text-sm md:text-base text-gray-800 flex items-center gap-2">
                  <i className="ri-ghost-smile-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center text-gray-600"></i>
                  Danh sách Phật tử đã mất & Lịch cúng giỗ
                </h3>
                <p className="text-xs md:text-sm text-gray-500 mt-1">{deceasedFollowers.length} Phật tử đã mất</p>
              </div>
              {deceasedFollowers.length === 0 ? (
                <div className="p-8 md:p-12 text-center">
                  <i className="ri-ghost-smile-line text-4xl md:text-5xl text-gray-300"></i>
                  <p className="text-sm md:text-base text-gray-500 mt-3">Không có Phật tử đã mất</p>
                </div>
              ) : (
                <div className="divide-y divide-gray-100">
                  {deceasedFollowers.map(f => (
                    <div key={f.id} className="p-4 md:p-6 hover:bg-gray-50 transition-colors">
                      <div className="flex items-start gap-3 md:gap-4">
                        <div className="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center text-white font-bold text-base md:text-lg flex-shrink-0">
                          {f.full_name.charAt(0)}
                        </div>
                        <div className="flex-1 min-w-0">
                          <div className="flex items-center gap-2 md:gap-3 flex-wrap">
                            <Link to={`/followers/${f.id}`} className="font-bold text-sm md:text-base text-gray-800 hover:text-amber-600 cursor-pointer">
                              {f.full_name}
                            </Link>
                            <span className="text-xs md:text-sm text-gray-500">({f.dharma_name})</span>
                            <span className="px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full text-xs font-medium">Đã mất</span>
                          </div>
                          <div className="mt-2 space-y-2 md:space-y-0 md:grid md:grid-cols-3 gap-2 md:gap-3">
                            <div className="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                              <i className="ri-cake-2-line w-4 h-4 flex items-center justify-center text-gray-400"></i>
                              Sinh: {f.birth_year_solar} ({f.birth_year_lunar})
                            </div>
                            <div className="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                              <i className="ri-calendar-close-line w-4 h-4 flex items-center justify-center text-gray-400"></i>
                              Mất: {f.death_date ? new Date(f.death_date).toLocaleDateString('vi-VN') : '-'}
                            </div>
                            <div className="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                              <i className="ri-moon-line w-4 h-4 flex items-center justify-center text-gray-400"></i>
                              Âm lịch: {f.death_date_lunar || '-'}
                            </div>
                          </div>
                          <div className="flex items-center gap-2 text-xs md:text-sm text-gray-500 mt-1">
                            <i className="ri-home-heart-line w-4 h-4 flex items-center justify-center text-gray-400"></i>
                            {f.family?.name || '-'}
                          </div>

                          {/* Lịch cúng giỗ năm hiện tại */}
                          <div className="mt-3 md:mt-4 p-3 md:p-4 bg-amber-50 rounded-lg border border-amber-200">
                            <div className="flex items-center justify-between mb-2">
                              <h4 className="text-xs md:text-sm font-semibold text-amber-800 flex items-center gap-2">
                                <i className="ri-fire-line w-4 h-4 flex items-center justify-center"></i>
                                Cúng giỗ năm {filterYear}
                              </h4>
                              {f.gioEvents.length > 0 && (
                                <span className={`px-2 py-0.5 rounded-full text-xs font-medium ${
                                  f.gioEvents[0].status === 'completed' ? 'bg-green-100 text-green-700' :
                                  f.gioEvents[0].status === 'overdue' ? 'bg-red-100 text-red-700' :
                                  'bg-amber-100 text-amber-700'
                                }`}>
                                  {f.gioEvents[0].status === 'completed' ? 'Đã cúng' :
                                   f.gioEvents[0].status === 'overdue' ? 'Quá hạn' : 'Sắp tới'}
                                </span>
                              )}
                            </div>
                            {f.gioEvents.length > 0 ? (
                              <div className="text-xs md:text-sm text-amber-700 space-y-1">
                                <p>Ngày: <strong>{new Date(f.gioEvents[0].date_solar).toLocaleDateString('vi-VN')}</strong> ({f.gioEvents[0].date_lunar})</p>
                                <p>Số tiền: <strong>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(f.gioEvents[0].amount)}</strong></p>
                              </div>
                            ) : (
                              <p className="text-xs md:text-sm text-amber-600 italic">Chưa có lịch cúng giỗ năm {filterYear}</p>
                            )}
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Bảng tổng hợp sao chiếu mệnh */}
            <div className="bg-white rounded-lg border border-amber-100 shadow-sm overflow-hidden">
              <div className="bg-gradient-to-r from-amber-50 to-orange-50 px-4 md:px-6 py-3 md:py-4 border-b border-amber-200">
                <h3 className="font-bold text-sm md:text-base text-amber-800 flex items-center gap-2">
                  <i className="ri-star-smile-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center text-amber-600"></i>
                  Sao chiếu mệnh năm {filterYear} - Phật tử còn sống
                </h3>
                <p className="text-xs md:text-sm text-amber-600 mt-1">{aliveFollowersWithStar.length} Phật tử</p>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full min-w-[600px]">
                  <thead className="bg-amber-50">
                    <tr>
                      <th className="px-3 md:px-5 py-2 md:py-3 text-left text-xs font-semibold text-amber-700 uppercase">Phật tử</th>
                      <th className="px-3 md:px-5 py-2 md:py-3 text-left text-xs font-semibold text-amber-700 uppercase">Gia đình</th>
                      <th className="px-3 md:px-5 py-2 md:py-3 text-left text-xs font-semibold text-amber-700 uppercase">Tuổi</th>
                      <th className="px-3 md:px-5 py-2 md:py-3 text-left text-xs font-semibold text-amber-700 uppercase">Sao {filterYear}</th>
                      <th className="px-3 md:px-5 py-2 md:py-3 text-left text-xs font-semibold text-amber-700 uppercase">Trạng thái cúng</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-amber-50">
                    {aliveFollowersWithStar.map(f => (
                      <tr key={f.id} className="hover:bg-amber-50/50 transition-colors">
                        <td className="px-3 md:px-5 py-3 md:py-4">
                          <Link to={`/followers/${f.id}`} className="flex items-center gap-2 md:gap-3 cursor-pointer hover:text-amber-600">
                            <div className="w-8 h-8 md:w-9 md:h-9 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-white font-semibold text-xs md:text-sm flex-shrink-0">
                              {f.full_name.charAt(0)}
                            </div>
                            <div>
                              <p className="font-medium text-xs md:text-sm text-amber-900">{f.full_name}</p>
                              <p className="text-[10px] md:text-xs text-amber-600">{f.dharma_name}</p>
                            </div>
                          </Link>
                        </td>
                        <td className="px-3 md:px-5 py-3 md:py-4 text-xs md:text-sm text-amber-700">{f.family?.name || '-'}</td>
                        <td className="px-3 md:px-5 py-3 md:py-4">
                          <span className="text-xs md:text-sm text-amber-700">{f.birth_year_lunar} ({f.birth_year_solar})</span>
                        </td>
                        <td className="px-3 md:px-5 py-3 md:py-4">
                          <span className={`inline-flex items-center gap-1 md:gap-1.5 px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-semibold border ${
                            f.star.type === 'good' ? 'text-green-700 bg-green-50 border-green-200' :
                            f.star.type === 'bad' ? 'text-red-700 bg-red-50 border-red-200' :
                            'text-amber-700 bg-amber-50 border-amber-200'
                          }`}>
                            <i className="ri-star-fill w-3 h-3 flex items-center justify-center"></i>
                            {f.star.name}
                          </span>
                        </td>
                        <td className="px-3 md:px-5 py-3 md:py-4">
                          {f.saoEvents.length > 0 ? (
                            <span className={`px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-medium ${
                              f.saoEvents[0].status === 'completed' ? 'bg-green-100 text-green-700' :
                              f.saoEvents[0].status === 'overdue' ? 'bg-red-100 text-red-700' :
                              'bg-amber-100 text-amber-700'
                            }`}>
                              {f.saoEvents[0].status === 'completed' ? 'Đã cúng' :
                               f.saoEvents[0].status === 'overdue' ? 'Quá hạn' : 'Sắp tới'}
                            </span>
                          ) : (
                            <span className="px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-medium bg-gray-100 text-gray-500">Chưa đăng ký</span>
                          )}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}
      </div>
    </MainLayout>
  );
}
