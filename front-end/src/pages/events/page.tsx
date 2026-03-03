
import { Suspense, useState, useEffect } from 'react';
import MainLayout from '../../components/layout/MainLayout';
import Header from '../../components/layout/Header';
import EventModal from './components/EventModal';
import { supabase, type Event } from '../../lib/supabase';

export default function EventsPage() {
  const [events, setEvents] = useState<Event[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedEvent, setSelectedEvent] = useState<Event | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState<string>('all');
  const [filterType, setFilterType] = useState<string>('all');

  useEffect(() => {
    fetchEvents();
  }, []);

  const fetchEvents = async () => {
    try {
      setLoading(true);
      const { data, error } = await supabase
        .from('events')
        .select('*')
        .order('event_date', { ascending: true });

      if (error) throw error;
      setEvents(data || []);
    } catch (error) {
      console.error('Error fetching events:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddEvent = () => {
    setSelectedEvent(null);
    setIsModalOpen(true);
  };

  const handleEditEvent = (event: Event) => {
    setSelectedEvent(event);
    setIsModalOpen(true);
  };

  const handleDeleteEvent = async (id: string) => {
    if (!confirm('Bạn có chắc chắn muốn xóa sự kiện này?')) return;

    try {
      const { error } = await supabase
        .from('events')
        .delete()
        .eq('id', id);

      if (error) throw error;
      fetchEvents();
    } catch (error) {
      console.error('Error deleting event:', error);
      alert('Có lỗi xảy ra khi xóa sự kiện');
    }
  };

  const handleSendNotification = async (event: Event) => {
    if (!confirm(`Gửi thông báo về sự kiện "${event.title}" đến tất cả Phật tử?`)) return;

    try {
      const { error } = await supabase
        .from('notifications')
        .insert({
          title: event.title,
          message: `Sự kiện: ${event.title}\nThời gian: ${formatDate(event.event_date)}\nĐịa điểm: ${event.location || 'Chưa xác định'}`,
          notification_type: 'event',
          target_audience: 'all',
          scheduled_date: new Date().toISOString(),
          status: 'sent',
          sent_date: new Date().toISOString()
        });

      if (error) throw error;

      await supabase
        .from('events')
        .update({ notify_followers: true })
        .eq('id', event.id);

      alert('Đã gửi thông báo thành công!');
      fetchEvents();
    } catch (error) {
      console.error('Error sending notification:', error);
      alert('Có lỗi xảy ra khi gửi thông báo');
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      weekday: 'long'
    });
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'upcoming':
        return 'bg-blue-100 text-blue-700';
      case 'ongoing':
        return 'bg-green-100 text-green-700';
      case 'completed':
        return 'bg-gray-100 text-gray-700';
      case 'cancelled':
        return 'bg-red-100 text-red-700';
      default:
        return 'bg-gray-100 text-gray-700';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'upcoming':
        return 'Sắp diễn ra';
      case 'ongoing':
        return 'Đang diễn ra';
      case 'completed':
        return 'Đã hoàn thành';
      case 'cancelled':
        return 'Đã hủy';
      default:
        return status;
    }
  };

  const filteredEvents = events.filter(event => {
    const matchesSearch = event.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         event.description?.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = filterStatus === 'all' || event.status === filterStatus;
    const matchesType = filterType === 'all' || event.event_type === filterType;
    return matchesSearch && matchesStatus && matchesType;
  });

  const upcomingEvents = filteredEvents.filter(e => e.status === 'upcoming');
  const ongoingEvents = filteredEvents.filter(e => e.status === 'ongoing');
  const completedEvents = filteredEvents.filter(e => e.status === 'completed');

  return (
    <Suspense fallback={<div>Đang tải...</div>}>
      <MainLayout>
        <Header 
          title="Quản lý Sự kiện & Lễ hội" 
          subtitle="Quản lý các sự kiện, lễ hội và gửi thông báo đến Phật tử"
        />
        
        <div className="p-8">
          {/* Search and Filter Bar */}
          <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-4 md:p-6 mb-6">
            <div className="flex flex-col gap-3 md:gap-4">
              <div className="w-full">
                <div className="relative">
                  <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-amber-400 w-5 h-5 flex items-center justify-center"></i>
                  <input
                    type="text"
                    placeholder="Tìm kiếm sự kiện..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-10 pr-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  />
                </div>
              </div>
              
              <div className="flex flex-col sm:flex-row gap-2 md:gap-3">
                <select
                  value={filterStatus}
                  onChange={(e) => setFilterStatus(e.target.value)}
                  className="flex-1 px-3 md:px-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="all">Tất cả trạng thái</option>
                  <option value="upcoming">Sắp diễn ra</option>
                  <option value="ongoing">Đang diễn ra</option>
                  <option value="completed">Đã hoàn thành</option>
                  <option value="cancelled">Đã hủy</option>
                </select>

                <select
                  value={filterType}
                  onChange={(e) => setFilterType(e.target.value)}
                  className="flex-1 px-3 md:px-4 py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="all">Tất cả loại</option>
                  <option value="ceremony">Lễ hội</option>
                  <option value="dharma_talk">Thuyết pháp</option>
                  <option value="meditation">Thiền định</option>
                  <option value="volunteer">Công quả</option>
                  <option value="other">Khác</option>
                </select>

                <button
                  onClick={handleAddEvent}
                  className="w-full sm:w-auto px-4 md:px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap"
                >
                  <i className="ri-add-line w-5 h-5 flex items-center justify-center"></i>
                  <span>Thêm sự kiện</span>
                </button>
              </div>
            </div>
          </div>

          {/* Statistics Cards */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-blue-600 font-medium">Sắp diễn ra</p>
                  <p className="text-3xl font-bold text-blue-700 mt-2">{upcomingEvents.length}</p>
                </div>
                <div className="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                  <i className="ri-calendar-event-line text-2xl text-blue-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-green-600 font-medium">Đang diễn ra</p>
                  <p className="text-3xl font-bold text-green-700 mt-2">{ongoingEvents.length}</p>
                </div>
                <div className="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                  <i className="ri-live-line text-2xl text-green-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 border border-gray-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600 font-medium">Đã hoàn thành</p>
                  <p className="text-3xl font-bold text-gray-700 mt-2">{completedEvents.length}</p>
                </div>
                <div className="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                  <i className="ri-checkbox-circle-line text-2xl text-gray-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-6 border border-amber-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-amber-600 font-medium">Tổng sự kiện</p>
                  <p className="text-3xl font-bold text-amber-700 mt-2">{events.length}</p>
                </div>
                <div className="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center">
                  <i className="ri-calendar-2-line text-2xl text-amber-700 w-6 h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>
          </div>

          {/* Events List */}
          {loading ? (
            <div className="text-center py-12">
              <div className="inline-block animate-spin rounded-full h-12 w-12 border-4 border-amber-200 border-t-amber-600"></div>
              <p className="text-amber-600 mt-4">Đang tải dữ liệu...</p>
            </div>
          ) : filteredEvents.length === 0 ? (
            <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-12 text-center">
              <i className="ri-calendar-line text-6xl text-amber-300 w-16 h-16 flex items-center justify-center mx-auto mb-4"></i>
              <p className="text-amber-600 text-lg">Chưa có sự kiện nào</p>
              <button
                onClick={handleAddEvent}
                className="mt-4 px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap"
              >
                Thêm sự kiện đầu tiên
              </button>
            </div>
          ) : (
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {filteredEvents.map((event) => (
                <div
                  key={event.id}
                  className="bg-white rounded-lg shadow-sm border border-amber-100 overflow-hidden hover:shadow-md transition-shadow"
                >
                  <div className="p-6">
                    <div className="flex items-start justify-between mb-4">
                      <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                          <h3 className="text-lg font-bold text-amber-900">{event.title}</h3>
                          <span className={`px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap ${getStatusColor(event.status)}`}>
                            {getStatusText(event.status)}
                          </span>
                        </div>
                        {event.event_type && (
                          <span className="inline-block px-2 py-1 bg-amber-50 text-amber-700 text-xs rounded">
                            {event.event_type === 'ceremony' && 'Lễ hội'}
                            {event.event_type === 'dharma_talk' && 'Thuyết pháp'}
                            {event.event_type === 'meditation' && 'Thiền định'}
                            {event.event_type === 'volunteer' && 'Công quả'}
                            {event.event_type === 'other' && 'Khác'}
                          </span>
                        )}
                      </div>
                    </div>

                    {event.description && (
                      <p className="text-sm text-amber-700 mb-4 line-clamp-2">{event.description}</p>
                    )}

                    <div className="space-y-2 mb-4">
                      <div className="flex items-center gap-2 text-sm text-amber-600">
                        <i className="ri-calendar-line w-4 h-4 flex items-center justify-center"></i>
                        <span>{formatDate(event.event_date)}</span>
                      </div>
                      {event.event_date_lunar && (
                        <div className="flex items-center gap-2 text-sm text-amber-600">
                          <i className="ri-calendar-2-line w-4 h-4 flex items-center justify-center"></i>
                          <span>Âm lịch: {event.event_date_lunar}</span>
                        </div>
                      )}
                      {event.location && (
                        <div className="flex items-center gap-2 text-sm text-amber-600">
                          <i className="ri-map-pin-line w-4 h-4 flex items-center justify-center"></i>
                          <span>{event.location}</span>
                        </div>
                      )}
                      {event.organizer && (
                        <div className="flex items-center gap-2 text-sm text-amber-600">
                          <i className="ri-user-line w-4 h-4 flex items-center justify-center"></i>
                          <span>Tổ chức: {event.organizer}</span>
                        </div>
                      )}
                    </div>

                    <div className="flex items-center gap-2 pt-4 border-t border-amber-100">
                      <button
                        onClick={() => handleEditEvent(event)}
                        className="flex-1 px-4 py-2 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition-colors text-sm font-medium whitespace-nowrap"
                      >
                        <i className="ri-edit-line w-4 h-4 flex items-center justify-center inline-block mr-1"></i>
                        Chỉnh sửa
                      </button>
                      <button
                        onClick={() => handleSendNotification(event)}
                        disabled={event.notify_followers}
                        className={`flex-1 px-4 py-2 rounded-lg transition-colors text-sm font-medium whitespace-nowrap ${
                          event.notify_followers
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-green-50 text-green-700 hover:bg-green-100'
                        }`}
                      >
                        <i className="ri-notification-line w-4 h-4 flex items-center justify-center inline-block mr-1"></i>
                        {event.notify_followers ? 'Đã gửi' : 'Gửi TB'}
                      </button>
                      <button
                        onClick={() => handleDeleteEvent(event.id)}
                        className="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors whitespace-nowrap"
                      >
                        <i className="ri-delete-bin-line w-4 h-4 flex items-center justify-center"></i>
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {isModalOpen && (
          <EventModal
            event={selectedEvent}
            onClose={() => setIsModalOpen(false)}
            onSuccess={() => {
              fetchEvents();
              setIsModalOpen(false);
            }}
          />
        )}
      </MainLayout>
    </Suspense>
  );
}
