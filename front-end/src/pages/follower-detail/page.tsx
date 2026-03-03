import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { followersData, familiesData, getFamilyById, getFollowersByFamily, getFollowerById, Follower } from '../../mocks/followers-data';

interface FollowerEvent {
  id: string;
  follower_id: string;
  event_type: string;
  event_name: string;
  event_date: string;
  event_date_lunar: string;
  is_recurring: boolean;
  notes: string;
  reminder_enabled: boolean;
  reminder_days_before: number;
}

// Dữ liệu demo sự kiện
const eventsData: FollowerEvent[] = [
  {
    id: 'evt-001',
    follower_id: 'pt-001',
    event_type: 'birthday',
    event_name: 'Sinh nhật',
    event_date: '2025-03-15',
    event_date_lunar: '15/02',
    is_recurring: true,
    notes: 'Tổ chức tại chùa',
    reminder_enabled: true,
    reminder_days_before: 7
  },
  {
    id: 'evt-002',
    follower_id: 'pt-001',
    event_type: 'star_worship',
    event_name: 'Cúng sao giải hạn',
    event_date: '2025-02-10',
    event_date_lunar: '13/01',
    is_recurring: true,
    notes: 'Sao La Hầu',
    reminder_enabled: true,
    reminder_days_before: 3
  }
];

export default function FollowerDetailPage() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [follower, setFollower] = useState<Follower | null>(null);
  const [events, setEvents] = useState<FollowerEvent[]>([]);
  const [familyMembers, setFamilyMembers] = useState<Follower[]>([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState<'info' | 'events' | 'family'>('info');
  const [showEventModal, setShowEventModal] = useState(false);
  const [editingEvent, setEditingEvent] = useState<FollowerEvent | null>(null);

  useEffect(() => {
    if (id) {
      // Tìm Phật tử trong dữ liệu demo
      const foundFollower = followersData.find(f => f.id === id);
      setFollower(foundFollower || null);
      
      // Lấy sự kiện của Phật tử
      const followerEvents = eventsData.filter(e => e.follower_id === id);
      
      // Nếu Phật tử đã mất, tự động thêm sự kiện giỗ
      if (foundFollower?.status === 'deceased' && foundFollower.death_date) {
        const deathAnniversaryExists = followerEvents.some(e => e.event_type === 'death_anniversary');
        if (!deathAnniversaryExists) {
          const deathAnniversary: FollowerEvent = {
            id: `evt-death-${id}`,
            follower_id: id,
            event_type: 'death_anniversary',
            event_name: 'Ngày giỗ',
            event_date: foundFollower.death_date,
            event_date_lunar: foundFollower.death_date_lunar || '',
            is_recurring: true,
            notes: 'Ngày giỗ hàng năm',
            reminder_enabled: true,
            reminder_days_before: 7
          };
          followerEvents.push(deathAnniversary);
        }
      }
      
      setEvents(followerEvents);
      
      // Lấy thành viên gia đình
      if (foundFollower?.family_id) {
        const members = getFollowersByFamily(foundFollower.family_id).filter(f => f.id !== id);
        setFamilyMembers(members);
      }
      
      setLoading(false);
    }
  }, [id]);

  const handleDeleteEvent = (eventId: string) => {
    if (!confirm('Bạn có chắc chắn muốn xóa sự kiện này?')) return;
    setEvents(events.filter(e => e.id !== eventId));
  };

  const getEventTypeLabel = (type: string) => {
    const types: Record<string, string> = {
      'death_anniversary': 'Ngày giỗ',
      'star_worship': 'Cúng sao',
      'zodiac_reading': 'Xem tử vi',
      'birthday': 'Sinh nhật',
      'other': 'Khác'
    };
    return types[type] || type;
  };

  const getEventTypeColor = (type: string) => {
    const colors: Record<string, string> = {
      'death_anniversary': 'bg-gray-100 text-gray-700',
      'star_worship': 'bg-yellow-100 text-yellow-700',
      'zodiac_reading': 'bg-teal-100 text-teal-700',
      'birthday': 'bg-pink-100 text-pink-700',
      'other': 'bg-amber-100 text-amber-700'
    };
    return colors[type] || 'bg-gray-100 text-gray-700';
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <div className="text-center">
          <i className="ri-loader-4-line text-4xl text-amber-600 animate-spin"></i>
          <p className="mt-4 text-amber-700">Đang tải dữ liệu...</p>
        </div>
      </div>
    );
  }

  if (!follower) {
    return (
      <div className="flex items-center justify-center h-screen">
        <div className="text-center">
          <i className="ri-error-warning-line text-4xl text-red-600"></i>
          <p className="mt-4 text-gray-700">Không tìm thấy thông tin Phật tử</p>
          <button
            onClick={() => navigate('/followers')}
            className="mt-4 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 cursor-pointer"
          >
            Quay lại danh sách
          </button>
        </div>
      </div>
    );
  }

  const family = getFamilyById(follower.family_id);
  const headOfFamily = family ? getFollowerById(family.head_of_family_id) : undefined;

  return (
    <div className="p-6">
      {/* Header */}
      <div className="mb-6 flex items-center justify-between">
        <div className="flex items-center gap-4">
          <button
            onClick={() => navigate('/followers')}
            className="p-2 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
          >
            <i className="ri-arrow-left-line text-xl text-amber-700"></i>
          </button>
          <h1 className="text-2xl font-bold text-amber-900">Chi tiết Phật tử</h1>
        </div>
      </div>

      {/* Thông tin cơ bản */}
      <div className="bg-white rounded-xl shadow-md p-6 mb-6">
        <div className="flex items-start gap-6">
          <div className={`w-24 h-24 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0 ${
            follower.status === 'deceased' 
              ? 'bg-gray-200' 
              : 'bg-amber-100'
          }`}>
            {follower.avatar_url ? (
              <img src={follower.avatar_url} alt={follower.full_name} className="w-full h-full object-cover" />
            ) : (
              <i className={`ri-user-line text-4xl ${follower.status === 'deceased' ? 'text-gray-500' : 'text-amber-600'}`}></i>
            )}
          </div>
          <div className="flex-1">
            <div className="flex items-center gap-3 mb-2">
              <h2 className={`text-2xl font-bold ${follower.status === 'deceased' ? 'text-gray-700' : 'text-amber-900'}`}>
                {follower.full_name}
              </h2>
              <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                follower.status === 'alive' 
                  ? 'bg-green-100 text-green-700' 
                  : 'bg-gray-100 text-gray-600'
              }`}>
                {follower.status === 'alive' ? 'Tại thế' : 'Hương linh'}
              </span>
            </div>
            {follower.dharma_name && (
              <p className="text-amber-700 mb-3">
                <span className="font-medium">Pháp danh:</span> {follower.dharma_name}
              </p>
            )}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div>
                <p className="text-sm text-gray-600">Điện thoại</p>
                <p className="text-amber-800 font-medium">{follower.phone || '-'}</p>
              </div>
              <div>
                <p className="text-sm text-gray-600">Email</p>
                <p className="text-amber-800 font-medium">{follower.email || '-'}</p>
              </div>
              <div>
                <p className="text-sm text-gray-600">Năm sinh (DL)</p>
                <p className="text-amber-800 font-medium">{follower.birth_year_solar}</p>
              </div>
              <div>
                <p className="text-sm text-gray-600">Năm sinh (ÂL)</p>
                <p className="text-amber-800 font-medium">
                  <span className="px-2 py-1 bg-amber-100 rounded-md text-sm">{follower.birth_year_lunar}</span>
                </p>
              </div>
              <div>
                <p className="text-sm text-gray-600">Ngày sinh</p>
                <p className="text-amber-800 font-medium">
                  {follower.birth_date ? new Date(follower.birth_date).toLocaleDateString('vi-VN') : '-'}
                  {follower.birth_date_lunar && <span className="text-sm text-gray-600 ml-1">({follower.birth_date_lunar})</span>}
                </p>
              </div>
              <div>
                <p className="text-sm text-gray-600">Giới tính</p>
                <p className="text-amber-800 font-medium">
                  {follower.gender === 'male' ? 'Nam' : follower.gender === 'female' ? 'Nữ' : '-'}
                </p>
              </div>
              {follower.status === 'deceased' && (
                <>
                  <div className="col-span-2">
                    <p className="text-sm text-gray-600">Ngày mất</p>
                    <p className="text-gray-700 font-medium">
                      {follower.death_date ? (
                        <>
                          <i className="ri-calendar-line mr-1"></i>
                          {new Date(follower.death_date).toLocaleDateString('vi-VN')}
                          {follower.death_date_lunar && (
                            <span className="text-sm text-gray-600 ml-2">
                              <i className="ri-moon-line mr-1"></i>
                              ({follower.death_date_lunar})
                            </span>
                          )}
                        </>
                      ) : '-'}
                    </p>
                  </div>
                </>
              )}
              <div className="col-span-2">
                <p className="text-sm text-gray-600">Gia đình</p>
                <p className="text-amber-800 font-medium">
                  {family ? (
                    <span className="flex items-center gap-2">
                      <i className="ri-home-heart-line text-amber-600"></i>
                      {family.name}
                    </span>
                  ) : '-'}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Tabs */}
      <div className="bg-white rounded-xl shadow-md overflow-hidden">
        <div className="border-b border-gray-200">
          <div className="flex">
            <button
              onClick={() => setActiveTab('info')}
              className={`px-6 py-3 font-medium transition-colors cursor-pointer ${
                activeTab === 'info'
                  ? 'text-amber-700 border-b-2 border-amber-600'
                  : 'text-gray-600 hover:text-amber-700'
              }`}
            >
              <i className="ri-information-line mr-2"></i>
              Thông tin chi tiết
            </button>
            <button
              onClick={() => setActiveTab('events')}
              className={`px-6 py-3 font-medium transition-colors cursor-pointer ${
                activeTab === 'events'
                  ? 'text-amber-700 border-b-2 border-amber-600'
                  : 'text-gray-600 hover:text-amber-700'
              }`}
            >
              <i className="ri-calendar-event-line mr-2"></i>
              Sự kiện cá nhân ({events.length})
            </button>
            <button
              onClick={() => setActiveTab('family')}
              className={`px-6 py-3 font-medium transition-colors cursor-pointer ${
                activeTab === 'family'
                  ? 'text-amber-700 border-b-2 border-amber-600'
                  : 'text-gray-600 hover:text-amber-700'
              }`}
            >
              <i className="ri-group-line mr-2"></i>
              Thành viên gia đình ({familyMembers.length})
            </button>
          </div>
        </div>

        <div className="p-6">
          {/* Tab Thông tin chi tiết */}
          {activeTab === 'info' && (
            <div className="space-y-4">
              <div>
                <p className="text-sm text-gray-600 mb-1">Địa chỉ</p>
                <p className="text-amber-800">{follower.address || '-'}</p>
              </div>
              {follower.status === 'deceased' && (
                <div className="p-4 bg-gray-50 rounded-lg border border-gray-200">
                  <h4 className="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i className="ri-calendar-event-line text-gray-600"></i>
                    Thông tin ngày mất
                  </h4>
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <p className="text-sm text-gray-600 mb-1">Ngày mất (Dương lịch)</p>
                      <p className="text-gray-800 font-medium">
                        {follower.death_date ? new Date(follower.death_date).toLocaleDateString('vi-VN') : 'Chưa cập nhật'}
                      </p>
                    </div>
                    <div>
                      <p className="text-sm text-gray-600 mb-1">Ngày mất (Âm lịch)</p>
                      <p className="text-gray-800 font-medium">
                        {follower.death_date_lunar || 'Chưa cập nhật'}
                      </p>
                    </div>
                  </div>
                </div>
              )}
              {follower.zodiac_info && (
                <div>
                  <p className="text-sm text-gray-600 mb-1">Tử vi</p>
                  <p className="text-amber-800 whitespace-pre-wrap">{follower.zodiac_info}</p>
                </div>
              )}
              {follower.notes && (
                <div>
                  <p className="text-sm text-gray-600 mb-1">Ghi chú</p>
                  <p className="text-amber-800 whitespace-pre-wrap">{follower.notes}</p>
                </div>
              )}
              {family && (
                <div className="mt-6 p-4 bg-amber-50 rounded-lg">
                  <h4 className="font-semibold text-amber-900 mb-2">
                    <i className="ri-home-heart-line mr-2"></i>
                    Thông tin gia đình
                  </h4>
                  <div className="grid grid-cols-2 gap-4 text-sm">
                    <div>
                      <p className="text-gray-600">Tên gia đình</p>
                      <p className="text-amber-800 font-medium">{family.name}</p>
                    </div>
                    <div>
                      <p className="text-gray-600">Chủ hộ</p>
                      <p className="text-amber-800 font-medium">
                        {headOfFamily ? (
                          <span 
                            className="hover:text-amber-900 cursor-pointer hover:underline"
                            onClick={() => navigate(`/followers/${headOfFamily.id}`)}
                          >
                            {headOfFamily.full_name}
                          </span>
                        ) : (
                          'Chưa xác định'
                        )}
                      </p>
                    </div>
                    <div className="col-span-2">
                      <p className="text-gray-600">Địa chỉ</p>
                      <p className="text-amber-800 font-medium">{family.address}</p>
                    </div>
                  </div>
                </div>
              )}
            </div>
          )}

          {/* Tab Sự kiện cá nhân */}
          {activeTab === 'events' && (
            <div>
              <div className="flex justify-between items-center mb-4">
                <h3 className="text-lg font-semibold text-amber-900">Danh sách sự kiện</h3>
                <button
                  onClick={() => {
                    setEditingEvent(null);
                    setShowEventModal(true);
                  }}
                  className="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center gap-2 whitespace-nowrap cursor-pointer"
                >
                  <i className="ri-add-line"></i>
                  Thêm sự kiện
                </button>
              </div>

              {events.length === 0 ? (
                <div className="text-center py-12">
                  <i className="ri-calendar-line text-5xl text-gray-300 mb-4"></i>
                  <p className="text-gray-500">Chưa có sự kiện nào</p>
                </div>
              ) : (
                <div className="space-y-3">
                  {events.map((event) => (
                    <div key={event.id} className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                      <div className="flex items-start justify-between">
                        <div className="flex-1">
                          <div className="flex items-center gap-2 mb-2 flex-wrap">
                            <span className={`px-3 py-1 rounded-full text-xs font-medium ${getEventTypeColor(event.event_type)}`}>
                              {getEventTypeLabel(event.event_type)}
                            </span>
                            {event.is_recurring && (
                              <span className="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs whitespace-nowrap">
                                <i className="ri-repeat-line mr-1"></i>
                                Lặp lại hàng năm
                              </span>
                            )}
                            {event.reminder_enabled && (
                              <span className="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs whitespace-nowrap">
                                <i className="ri-notification-line mr-1"></i>
                                Nhắc nhở
                              </span>
                            )}
                          </div>
                          <h4 className="font-semibold text-amber-900 mb-1">{event.event_name}</h4>
                          <div className="flex items-center gap-4 text-sm text-gray-600 mb-2 flex-wrap">
                            <span className="whitespace-nowrap">
                              <i className="ri-calendar-line mr-1"></i>
                              {event.event_date ? new Date(event.event_date).toLocaleDateString('vi-VN') : '-'}
                            </span>
                            {event.event_date_lunar && (
                              <span className="text-amber-700 whitespace-nowrap">
                                <i className="ri-moon-line mr-1"></i>
                                {event.event_date_lunar}
                              </span>
                            )}
                          </div>
                          {event.notes && (
                            <p className="text-sm text-gray-600">{event.notes}</p>
                          )}
                        </div>
                        <div className="flex items-center gap-2 ml-4">
                          <button
                            onClick={() => {
                              setEditingEvent(event);
                              setShowEventModal(true);
                            }}
                            className="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
                            title="Chỉnh sửa"
                          >
                            <i className="ri-edit-line text-lg"></i>
                          </button>
                          <button
                            onClick={() => handleDeleteEvent(event.id)}
                            className="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                            title="Xóa"
                          >
                            <i className="ri-delete-bin-line text-lg"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* Tab Thành viên gia đình */}
          {activeTab === 'family' && (
            <div>
              <h3 className="text-lg font-semibold text-amber-900 mb-4">Thành viên trong gia đình</h3>
              {familyMembers.length === 0 ? (
                <div className="text-center py-12">
                  <i className="ri-group-line text-5xl text-gray-300 mb-4"></i>
                  <p className="text-gray-500">Không có thành viên khác trong gia đình</p>
                </div>
              ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {familyMembers.map((member) => {
                    const isHeadOfFamily = family && member.id === family.head_of_family_id;
                    
                    return (
                      <div 
                        key={member.id} 
                        className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                        onClick={() => navigate(`/followers/${member.id}`)}
                      >
                        <div className="flex items-center gap-4">
                          <div className={`w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold ${
                            member.status === 'deceased' 
                              ? 'bg-gradient-to-br from-gray-400 to-gray-500' 
                              : 'bg-gradient-to-br from-amber-400 to-orange-500'
                          }`}>
                            {member.full_name.charAt(0)}
                          </div>
                          <div className="flex-1">
                            <div className="flex items-center gap-2 flex-wrap">
                              <h4 className={`font-semibold ${member.status === 'deceased' ? 'text-gray-600' : 'text-amber-900'}`}>
                                {member.full_name}
                              </h4>
                              {isHeadOfFamily && (
                                <span className="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs whitespace-nowrap">
                                  Chủ hộ
                                </span>
                              )}
                              <span className={`px-2 py-0.5 rounded-full text-xs ${
                                member.status === 'alive' 
                                  ? 'bg-green-100 text-green-700' 
                                  : 'bg-gray-100 text-gray-600'
                              }`}>
                                {member.status === 'alive' ? 'Tại thế' : 'Hương linh'}
                              </span>
                            </div>
                            {member.dharma_name && (
                              <p className="text-sm text-amber-700">Pháp danh: {member.dharma_name}</p>
                            )}
                            <p className="text-sm text-gray-600">
                              {member.birth_year_solar} ({member.birth_year_lunar})
                            </p>
                          </div>
                          <i className="ri-arrow-right-s-line text-xl text-gray-400"></i>
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Event Modal */}
      {showEventModal && (
        <EventModal
          followerId={id!}
          event={editingEvent}
          onClose={() => {
            setShowEventModal(false);
            setEditingEvent(null);
          }}
          onSave={(newEvent) => {
            if (editingEvent) {
              setEvents(events.map(e => e.id === newEvent.id ? newEvent : e));
            } else {
              setEvents([...events, newEvent]);
            }
            setShowEventModal(false);
            setEditingEvent(null);
          }}
        />
      )}
    </div>
  );
}

// Event Modal Component
interface EventModalProps {
  followerId: string;
  event: FollowerEvent | null;
  onClose: () => void;
  onSave: (event: FollowerEvent) => void;
}

function EventModal({ followerId, event, onClose, onSave }: EventModalProps) {
  const [formData, setFormData] = useState({
    event_type: event?.event_type || 'other',
    event_name: event?.event_name || '',
    event_date: event?.event_date || '',
    event_date_lunar: event?.event_date_lunar || '',
    is_recurring: event?.is_recurring || false,
    notes: event?.notes || '',
    reminder_enabled: event?.reminder_enabled ?? true,
    reminder_days_before: event?.reminder_days_before || 7,
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    const newEvent: FollowerEvent = {
      id: event?.id || `evt-${Date.now()}`,
      follower_id: followerId,
      ...formData
    };

    onSave(newEvent);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div className="p-6 border-b border-gray-200">
          <h2 className="text-xl font-bold text-amber-900">
            {event ? 'Chỉnh sửa sự kiện' : 'Thêm sự kiện mới'}
          </h2>
        </div>

        <form onSubmit={handleSubmit} className="p-6 space-y-4">
          <div>
            <label className="block text-sm font-medium text-amber-900 mb-2">
              Loại sự kiện <span className="text-red-500">*</span>
            </label>
            <select
              value={formData.event_type}
              onChange={(e) => setFormData({ ...formData, event_type: e.target.value })}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent cursor-pointer"
              required
            >
              <option value="death_anniversary">Ngày giỗ</option>
              <option value="star_worship">Cúng sao</option>
              <option value="zodiac_reading">Xem tử vi</option>
              <option value="birthday">Sinh nhật</option>
              <option value="other">Khác</option>
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-amber-900 mb-2">
              Tên sự kiện <span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              value={formData.event_name}
              onChange={(e) => setFormData({ ...formData, event_name: e.target.value })}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
              placeholder="Ví dụ: Giỗ ông nội, Cúng sao giải hạn..."
              required
            />
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-amber-900 mb-2">
                Ngày dương lịch
              </label>
              <input
                type="date"
                value={formData.event_date}
                onChange={(e) => setFormData({ ...formData, event_date: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-amber-900 mb-2">
                Ngày âm lịch
              </label>
              <input
                type="text"
                value={formData.event_date_lunar}
                onChange={(e) => setFormData({ ...formData, event_date_lunar: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                placeholder="Ví dụ: 15/8"
              />
            </div>
          </div>

          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              id="is_recurring"
              checked={formData.is_recurring}
              onChange={(e) => setFormData({ ...formData, is_recurring: e.target.checked })}
              className="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500 cursor-pointer"
            />
            <label htmlFor="is_recurring" className="text-sm text-amber-900 cursor-pointer">
              Lặp lại hàng năm
            </label>
          </div>

          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              id="reminder_enabled"
              checked={formData.reminder_enabled}
              onChange={(e) => setFormData({ ...formData, reminder_enabled: e.target.checked })}
              className="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500 cursor-pointer"
            />
            <label htmlFor="reminder_enabled" className="text-sm text-amber-900 cursor-pointer">
              Bật nhắc nhở
            </label>
          </div>

          {formData.reminder_enabled && (
            <div>
              <label className="block text-sm font-medium text-amber-900 mb-2">
                Nhắc trước (ngày)
              </label>
              <input
                type="number"
                value={formData.reminder_days_before}
                onChange={(e) => setFormData({ ...formData, reminder_days_before: parseInt(e.target.value) })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                min="1"
                max="365"
              />
            </div>
          )}

          <div>
            <label className="block text-sm font-medium text-amber-900 mb-2">
              Ghi chú
            </label>
            <textarea
              value={formData.notes}
              onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
              rows={3}
              maxLength={500}
              placeholder="Thông tin bổ sung..."
            />
          </div>

          <div className="flex justify-end gap-3 pt-4">
            <button
              type="button"
              onClick={onClose}
              className="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
            >
              Hủy
            </button>
            <button
              type="submit"
              className="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors cursor-pointer"
            >
              {event ? 'Cập nhật' : 'Thêm mới'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
