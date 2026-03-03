import { Suspense, useState, useEffect } from 'react';
import MainLayout from '../../components/layout/MainLayout';
import Header from '../../components/layout/Header';
import MeritActivityModal from './components/MeritActivityModal';
import { supabase, type MeritActivity, type BuddhistFollower } from '../../lib/supabase';

interface ActivityWithFollower extends MeritActivity {
  follower?: BuddhistFollower;
}

export default function MeritActivitiesPage() {
  const [activities, setActivities] = useState<ActivityWithFollower[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedActivity, setSelectedActivity] = useState<MeritActivity | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterType, setFilterType] = useState<string>('all');
  const [dateRange, setDateRange] = useState({ start: '', end: '' });

  useEffect(() => {
    fetchActivities();
  }, []);

  const fetchActivities = async () => {
    try {
      setLoading(true);
      const { data: activitiesData, error: activitiesError } = await supabase
        .from('merit_activities')
        .select('*')
        .order('activity_date', { ascending: false });

      if (activitiesError) throw activitiesError;

      const { data: followersData, error: followersError } = await supabase
        .from('buddhist_followers')
        .select('id, full_name, dharma_name, avatar_url');

      if (followersError) throw followersError;

      const activitiesWithFollowers = (activitiesData || []).map(activity => ({
        ...activity,
        follower: followersData?.find(f => f.id === activity.follower_id)
      }));

      setActivities(activitiesWithFollowers);
    } catch (error) {
      console.error('Error fetching activities:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddActivity = () => {
    setSelectedActivity(null);
    setIsModalOpen(true);
  };

  const handleEditActivity = (activity: MeritActivity) => {
    setSelectedActivity(activity);
    setIsModalOpen(true);
  };

  const handleDeleteActivity = async (id: string) => {
    if (!confirm('Bạn có chắc chắn muốn xóa hoạt động này?')) return;

    try {
      const { error } = await supabase
        .from('merit_activities')
        .delete()
        .eq('id', id);

      if (error) throw error;
      fetchActivities();
    } catch (error) {
      console.error('Error deleting activity:', error);
      alert('Có lỗi xảy ra khi xóa hoạt động');
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const getActivityTypeText = (type: string) => {
    switch (type) {
      case 'volunteer':
        return 'Công quả';
      case 'service':
        return 'Phụng sự';
      case 'teaching':
        return 'Giảng dạy';
      case 'donation':
        return 'Cúng dường';
      case 'other':
        return 'Khác';
      default:
        return type;
    }
  };

  const getActivityTypeColor = (type: string) => {
    switch (type) {
      case 'volunteer':
        return 'bg-green-100 text-green-700';
      case 'service':
        return 'bg-blue-100 text-blue-700';
      case 'teaching':
        return 'bg-purple-100 text-purple-700';
      case 'donation':
        return 'bg-amber-100 text-amber-700';
      default:
        return 'bg-gray-100 text-gray-700';
    }
  };

  const filteredActivities = activities.filter(activity => {
    const matchesSearch = activity.activity_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         activity.follower?.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         activity.follower?.dharma_name?.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesType = filterType === 'all' || activity.activity_type === filterType;
    
    let matchesDate = true;
    if (dateRange.start && dateRange.end) {
      const activityDate = new Date(activity.activity_date);
      const startDate = new Date(dateRange.start);
      const endDate = new Date(dateRange.end);
      matchesDate = activityDate >= startDate && activityDate <= endDate;
    }
    
    return matchesSearch && matchesType && matchesDate;
  });

  const totalHours = filteredActivities.reduce((sum, activity) => sum + (activity.hours || 0), 0);
  const volunteerCount = filteredActivities.filter(a => a.activity_type === 'volunteer').length;
  const serviceCount = filteredActivities.filter(a => a.activity_type === 'service').length;
  const uniqueFollowers = new Set(filteredActivities.map(a => a.follower_id)).size;

  return (
    <Suspense fallback={<div>Đang tải...</div>}>
      <MainLayout>
        <Header 
          title="Quản lý Công quả & Phụng sự" 
          subtitle="Theo dõi và ghi nhận các hoạt động công quả, phụng sự của Phật tử"
        />
        
        <div className="p-8">
          {/* Search and Filter Bar */}
          <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-6 mb-6">
            <div className="flex flex-col lg:flex-row gap-4">
              <div className="flex-1">
                <div className="relative">
                  <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-amber-400 w-5 h-5 flex items-center justify-center"></i>
                  <input
                    type="text"
                    placeholder="Tìm kiếm hoạt động hoặc Phật tử..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-10 pr-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  />
                </div>
              </div>
              
              <div className="flex gap-3">
                <select
                  value={filterType}
                  onChange={(e) => setFilterType(e.target.value)}
                  className="px-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value="all">Tất cả loại</option>
                  <option value="volunteer">Công quả</option>
                  <option value="service">Phụng sự</option>
                  <option value="teaching">Giảng dạy</option>
                  <option value="donation">Cúng dường</option>
                  <option value="other">Khác</option>
                </select>

                <input
                  type="date"
                  value={dateRange.start}
                  onChange={(e) => setDateRange({ ...dateRange, start: e.target.value })}
                  className="px-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="Từ ngày"
                />

                <input
                  type="date"
                  value={dateRange.end}
                  onChange={(e) => setDateRange({ ...dateRange, end: e.target.value })}
                  className="px-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="Đến ngày"
                />

                <button
                  onClick={handleAddActivity}
                  className="px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center gap-2 whitespace-nowrap"
                >
                  <i className="ri-add-line w-5 h-5 flex items-center justify-center"></i>
                  Thêm hoạt động
                </button>
              </div>
            </div>
          </div>

          {/* Statistics Cards */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-green-600 font-medium">Công quả</p>
                  <p className="text-3xl font-bold text-green-700 mt-2">{volunteerCount}</p>
                </div>
                <div className="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                  <i className="ri-hand-heart-line text-2xl text-green-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-blue-600 font-medium">Phụng sự</p>
                  <p className="text-3xl font-bold text-blue-700 mt-2">{serviceCount}</p>
                </div>
                <div className="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                  <i className="ri-service-line text-2xl text-blue-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-purple-600 font-medium">Tổng giờ</p>
                  <p className="text-3xl font-bold text-purple-700 mt-2">{totalHours.toFixed(1)}</p>
                </div>
                <div className="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                  <i className="ri-time-line text-2xl text-purple-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-6 border border-amber-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-amber-600 font-medium">Phật tử tham gia</p>
                  <p className="text-3xl font-bold text-amber-700 mt-2">{uniqueFollowers}</p>
                </div>
                <div className="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center">
                  <i className="ri-user-heart-line text-2xl text-amber-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>
          </div>

          {/* Activities List */}
          {loading ? (
            <div className="text-center py-12">
              <div className="inline-block animate-spin rounded-full h-12 w-12 border-4 border-amber-200 border-t-amber-600"></div>
              <p className="text-amber-600 mt-4">Đang tải dữ liệu...</p>
            </div>
          ) : filteredActivities.length === 0 ? (
            <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-12 text-center">
              <i className="ri-hand-heart-line text-6xl text-amber-300 w-16 h-16 flex items-center justify-center mx-auto mb-4"></i>
              <p className="text-amber-600 text-lg">Chưa có hoạt động nào</p>
              <button
                onClick={handleAddActivity}
                className="mt-4 px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap"
              >
                Thêm hoạt động đầu tiên
              </button>
            </div>
          ) : (
            <div className="bg-white rounded-lg shadow-sm border border-amber-100 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full">
                  <thead className="bg-amber-50 border-b border-amber-100">
                    <tr>
                      <th className="px-6 py-4 text-left text-sm font-semibold text-amber-900">Phật tử</th>
                      <th className="px-6 py-4 text-left text-sm font-semibold text-amber-900">Hoạt động</th>
                      <th className="px-6 py-4 text-left text-sm font-semibold text-amber-900">Loại</th>
                      <th className="px-6 py-4 text-left text-sm font-semibold text-amber-900">Ngày thực hiện</th>
                      <th className="px-6 py-4 text-left text-sm font-semibold text-amber-900">Số giờ</th>
                      <th className="px-6 py-4 text-right text-sm font-semibold text-amber-900">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-amber-100">
                    {filteredActivities.map((activity) => (
                      <tr key={activity.id} className="hover:bg-amber-50/50 transition-colors">
                        <td className="px-6 py-4">
                          <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center overflow-hidden">
                              {activity.follower?.avatar_url ? (
                                <img 
                                  src={activity.follower.avatar_url} 
                                  alt={activity.follower.full_name}
                                  className="w-full h-full object-cover"
                                />
                              ) : (
                                <i className="ri-user-line text-amber-600 w-5 h-5 flex items-center justify-center"></i>
                              )}
                            </div>
                            <div>
                              <div className="font-medium text-amber-900">{activity.follower?.full_name}</div>
                              {activity.follower?.dharma_name && (
                                <div className="text-xs text-amber-600">{activity.follower.dharma_name}</div>
                              )}
                            </div>
                          </div>
                        </td>
                        <td className="px-6 py-4">
                          <div className="font-medium text-amber-900">{activity.activity_name}</div>
                          {activity.description && (
                            <div className="text-sm text-amber-600 line-clamp-1 mt-1">{activity.description}</div>
                          )}
                        </td>
                        <td className="px-6 py-4">
                          <span className={`px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap ${getActivityTypeColor(activity.activity_type || '')}`}>
                            {getActivityTypeText(activity.activity_type || '')}
                          </span>
                        </td>
                        <td className="px-6 py-4 text-sm text-amber-700">
                          {formatDate(activity.activity_date)}
                        </td>
                        <td className="px-6 py-4">
                          <div className="flex items-center gap-1 text-amber-700">
                            <i className="ri-time-line w-4 h-4 flex items-center justify-center"></i>
                            <span className="font-medium">{activity.hours || 0}h</span>
                          </div>
                        </td>
                        <td className="px-6 py-4">
                          <div className="flex items-center justify-end gap-2">
                            <button
                              onClick={() => handleEditActivity(activity)}
                              className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                              title="Chỉnh sửa"
                            >
                              <i className="ri-edit-line w-5 h-5 flex items-center justify-center"></i>
                            </button>
                            <button
                              onClick={() => handleDeleteActivity(activity.id)}
                              className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                              title="Xóa"
                            >
                              <i className="ri-delete-bin-line w-5 h-5 flex items-center justify-center"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}
        </div>

        {isModalOpen && (
          <MeritActivityModal
            activity={selectedActivity}
            onClose={() => setIsModalOpen(false)}
            onSuccess={() => {
              fetchActivities();
              setIsModalOpen(false);
            }}
          />
        )}
      </MainLayout>
    </Suspense>
  );
}
