
import { useState, useEffect } from 'react';
import { supabase, type Event } from '../../../lib/supabase';

interface EventModalProps {
  event: Event | null;
  onClose: () => void;
  onSuccess: () => void;
}

export default function EventModal({ event, onClose, onSuccess }: EventModalProps) {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    event_type: 'ceremony',
    event_date: '',
    event_date_lunar: '',
    location: '',
    organizer: '',
    status: 'upcoming',
    notify_followers: false
  });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (event) {
      setFormData({
        title: event.title || '',
        description: event.description || '',
        event_type: event.event_type || 'ceremony',
        event_date: event.event_date ? event.event_date.split('T')[0] : '',
        event_date_lunar: event.event_date_lunar || '',
        location: event.location || '',
        organizer: event.organizer || '',
        status: event.status || 'upcoming',
        notify_followers: event.notify_followers || false
      });
    }
  }, [event]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      if (event) {
        const { error } = await supabase
          .from('events')
          .update({
            ...formData,
            updated_at: new Date().toISOString()
          })
          .eq('id', event.id);

        if (error) throw error;
      } else {
        const { error } = await supabase
          .from('events')
          .insert([formData]);

        if (error) throw error;
      }

      onSuccess();
    } catch (error) {
      console.error('Error saving event:', error);
      alert('Có lỗi xảy ra khi lưu sự kiện');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-0 md:p-4">
      <div className="bg-white rounded-none md:rounded-lg shadow-xl w-full h-full md:max-w-2xl md:w-full md:max-h-[90vh] md:h-auto overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-amber-100 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
          <h2 className="text-lg md:text-xl font-bold text-amber-900">
            {event ? 'Chỉnh sửa sự kiện' : 'Thêm sự kiện mới'}
          </h2>
          <button
            onClick={onClose}
            className="p-2 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
          >
            <i className="ri-close-line text-xl text-amber-700 w-6 h-6 flex items-center justify-center"></i>
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-4 md:p-6">
          <div className="space-y-3 md:space-y-4">
            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Tên sự kiện <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                required
                value={formData.title}
                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                placeholder="Nhập tên sự kiện"
              />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Loại sự kiện
                </label>
                <select
                  value={formData.event_type}
                  onChange={(e) => setFormData({ ...formData, event_type: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="ceremony">Lễ hội</option>
                  <option value="dharma_talk">Thuyết pháp</option>
                  <option value="meditation">Thiền định</option>
                  <option value="volunteer">Công quả</option>
                  <option value="other">Khác</option>
                </select>
              </div>

              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Trạng thái
                </label>
                <select
                  value={formData.status}
                  onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="upcoming">Sắp diễn ra</option>
                  <option value="ongoing">Đang diễn ra</option>
                  <option value="completed">Đã hoàn thành</option>
                  <option value="cancelled">Đã hủy</option>
                </select>
              </div>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Mô tả
              </label>
              <textarea
                value={formData.description}
                onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                rows={3}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm resize-none"
                placeholder="Nhập mô tả chi tiết về sự kiện"
              />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Ngày diễn ra (Dương lịch) <span className="text-red-500">*</span>
                </label>
                <input
                  type="date"
                  required
                  value={formData.event_date}
                  onChange={(e) => setFormData({ ...formData, event_date: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                />
              </div>

              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Ngày âm lịch
                </label>
                <input
                  type="text"
                  value={formData.event_date_lunar}
                  onChange={(e) => setFormData({ ...formData, event_date_lunar: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="VD: 15/01/2024"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Địa điểm
              </label>
              <input
                type="text"
                value={formData.location}
                onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                placeholder="Nhập địa điểm tổ chức"
              />
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Người tổ chức
              </label>
              <input
                type="text"
                value={formData.organizer}
                onChange={(e) => setFormData({ ...formData, organizer: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                placeholder="Nhập tên người/ban tổ chức"
              />
            </div>

            <div className="flex items-center gap-2 md:gap-3 p-3 md:p-4 bg-amber-50 rounded-lg">
              <input
                type="checkbox"
                id="notify_followers"
                checked={formData.notify_followers}
                onChange={(e) => setFormData({ ...formData, notify_followers: e.target.checked })}
                className="w-4 h-4 md:w-5 md:h-5 text-amber-600 border-amber-300 rounded focus:ring-amber-500 cursor-pointer flex-shrink-0"
              />
              <label htmlFor="notify_followers" className="text-xs md:text-sm text-amber-900 cursor-pointer">
                Gửi thông báo đến tất cả Phật tử khi tạo sự kiện
              </label>
            </div>
          </div>

          <div className="flex flex-col md:flex-row gap-2 md:gap-3 mt-4 md:mt-6 pt-4 md:pt-6 border-t border-amber-100">
            <button
              type="button"
              onClick={onClose}
              className="w-full md:flex-1 px-6 py-2.5 border border-amber-300 text-amber-700 rounded-lg hover:bg-amber-50 transition-colors whitespace-nowrap text-sm cursor-pointer"
            >
              Hủy
            </button>
            <button
              type="submit"
              disabled={loading}
              className="w-full md:flex-1 px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap text-sm cursor-pointer"
            >
              {loading ? 'Đang lưu...' : event ? 'Cập nhật' : 'Thêm mới'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
