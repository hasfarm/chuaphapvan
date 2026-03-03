
import { useEffect, useState } from 'react';
import MainLayout from '../../components/layout/MainLayout';
import { supabase } from '../../lib/supabase';
import { recentActivities, upcomingEvents } from '../../mocks/dashboard-data';

export default function DashboardPage() {
  const [stats, setStats] = useState({ totalFollowers: 0, upcomingEvents: 0 });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const [followersRes, eventsRes] = await Promise.all([
        supabase.from('buddhist_followers').select('id', { count: 'exact', head: true }),
        supabase.from('events').select('id', { count: 'exact', head: true }).eq('status', 'upcoming')
      ]);

      setStats({
        totalFollowers: followersRes.count || 0,
        upcomingEvents: eventsRes.count || 0
      });
    } catch (error) {
      console.error('Error loading dashboard:', error);
    } finally {
      setLoading(false);
    }
  };

  const statCards = [
    {
      title: 'Tổng Phật tử',
      value: stats.totalFollowers,
      icon: 'ri-user-heart-line',
      color: 'from-amber-500 to-orange-600',
      bgColor: 'bg-amber-50'
    },
    {
      title: 'Sự kiện sắp tới',
      value: stats.upcomingEvents,
      icon: 'ri-calendar-event-line',
      color: 'from-orange-500 to-red-600',
      bgColor: 'bg-orange-50'
    }
  ];

  return (
    <MainLayout title="Tổng quan" subtitle="Thống kê và hoạt động gần đây">
      <div className="space-y-4 sm:space-y-6">
        {/* Stat Cards - 1 cột mobile, 2 cột tablet+ */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {statCards.map((card, index) => (
            <div key={index} className={`${card.bgColor} rounded-xl p-4 sm:p-6 border border-amber-200 shadow-sm hover:shadow-md transition-shadow`}>
              <div className="flex items-start justify-between">
                <div>
                  <p className="text-xs sm:text-sm text-amber-700 font-medium mb-1 sm:mb-2">{card.title}</p>
                  <p className="text-2xl sm:text-3xl font-bold text-amber-900">{loading ? '...' : card.value}</p>
                </div>
                <div className={`w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br ${card.color} rounded-lg flex items-center justify-center shadow-md`}>
                  <i className={`${card.icon} text-xl sm:text-2xl text-white`}></i>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Activity & Events - Stack trên mobile */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
          {/* Hoạt động gần đây */}
          <div className="bg-white rounded-xl p-4 sm:p-6 border border-amber-200 shadow-sm">
            <div className="flex items-center justify-between mb-4 sm:mb-6">
              <h2 className="text-base sm:text-lg font-bold text-amber-900">Hoạt động gần đây</h2>
              <button className="text-xs sm:text-sm text-amber-600 hover:text-amber-700 font-medium whitespace-nowrap">
                Xem tất cả
              </button>
            </div>
            <div className="space-y-3 sm:space-y-4">
              {recentActivities.map((activity) => (
                <div key={activity.id} className="flex items-start gap-3 sm:gap-4 p-2 sm:p-3 rounded-lg hover:bg-amber-50 transition-colors">
                  <div className="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i className={`${activity.icon} text-sm sm:text-base text-white`}></i>
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-xs sm:text-sm font-semibold text-amber-900">{activity.title}</p>
                    <p className="text-xs sm:text-sm text-amber-600 mt-1">{activity.description}</p>
                    <p className="text-xs text-amber-500 mt-1">{activity.time}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Sự kiện sắp tới */}
          <div className="bg-white rounded-xl p-4 sm:p-6 border border-amber-200 shadow-sm">
            <div className="flex items-center justify-between mb-4 sm:mb-6">
              <h2 className="text-base sm:text-lg font-bold text-amber-900">Sự kiện sắp tới</h2>
              <button className="text-xs sm:text-sm text-amber-600 hover:text-amber-700 font-medium whitespace-nowrap">
                Xem lịch
              </button>
            </div>
            <div className="space-y-3 sm:space-y-4">
              {upcomingEvents.map((event) => (
                <div key={event.id} className="p-3 sm:p-4 rounded-lg border border-amber-200 hover:border-amber-300 transition-colors bg-gradient-to-r from-amber-50 to-orange-50">
                  <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-0">
                    <div className="flex-1">
                      <h3 className="text-sm sm:text-base font-semibold text-amber-900">{event.title}</h3>
                      <div className="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 mt-2 text-xs sm:text-sm text-amber-600">
                        <span className="flex items-center gap-1">
                          <i className="ri-calendar-line"></i>
                          {event.lunarDate}
                        </span>
                        <span className="flex items-center gap-1">
                          <i className="ri-map-pin-line"></i>
                          {event.location}
                        </span>
                      </div>
                    </div>
                    <div className="text-left sm:text-right">
                      <div className="inline-block px-2 sm:px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full whitespace-nowrap">
                        {event.attendees} người
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Lời Phật dạy */}
        <div className="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl p-6 sm:p-8 text-white shadow-lg">
          <div className="flex flex-col sm:flex-row items-center sm:justify-between gap-4">
            <div className="flex-1 text-center sm:text-left">
              <h2 className="text-xl sm:text-2xl font-bold mb-2">Lời Phật dạy hôm nay</h2>
              <p className="text-amber-50 text-base sm:text-lg italic">
                "Không có con đường nào dẫn đến hạnh phúc. Hạnh phúc chính là con đường."
              </p>
              <p className="text-amber-100 text-xs sm:text-sm mt-3">- Đức Phật Thích Ca Mâu Ni</p>
            </div>
            <div className="w-20 h-20 sm:w-24 sm:h-24 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-lotus-line text-4xl sm:text-5xl"></i>
            </div>
          </div>
        </div>
      </div>
    </MainLayout>
  );
}
