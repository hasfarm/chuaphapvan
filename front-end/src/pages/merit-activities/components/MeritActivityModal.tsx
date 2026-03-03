import { useState, useEffect } from 'react';

interface MeritActivity {
  id: string;
  follower_id: string;
  activity_type: string;
  description: string;
  merit_points: number;
  activity_date: string;
  created_at: string;
}

interface MeritActivityModalProps {
  activity: MeritActivity | null;
  onClose: () => void;
  onSave: (activityData: Partial<MeritActivity>) => void;
}

export default function MeritActivityModal({ activity, onClose, onSave }: MeritActivityModalProps) {
  const [formData, setFormData] = useState({
    activity_type: 'donation',
    description: '',
    merit_points: 0,
    activity_date: new Date().toISOString().split('T')[0]
  });

  useEffect(() => {
    if (activity) {
      setFormData({
        activity_type: activity.activity_type,
        description: activity.description,
        merit_points: activity.merit_points,
        activity_date: activity.activity_date.split('T')[0]
      });
    }
  }, [activity]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-0 md:p-4">
      <div className="bg-white rounded-none md:rounded-lg shadow-xl w-full h-full md:max-w-2xl md:w-full md:max-h-[90vh] md:h-auto overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-amber-100 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
          <h2 className="text-lg md:text-xl font-bold text-amber-900">
            {activity ? 'Chỉnh sửa hoạt động' : 'Thêm hoạt động công đức'}
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
                Loại hoạt động <span className="text-red-500">*</span>
              </label>
              <select
                required
                value={formData.activity_type}
                onChange={(e) => setFormData({ ...formData, activity_type: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
              >
                <option value="donation">Cúng dường</option>
                <option value="volunteer">Công quả</option>
                <option value="ceremony">Tham gia lễ hội</option>
                <option value="meditation">Thiền định</option>
                <option value="dharma_study">Học pháp</option>
                <option value="other">Khác</option>
              </select>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Mô tả chi tiết <span className="text-red-500">*</span>
              </label>
              <textarea
                required
                value={formData.description}
                onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                rows={3}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm resize-none"
                placeholder="Nhập mô tả chi tiết về hoạt động"
              />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Điểm công đức <span className="text-red-500">*</span>
                </label>
                <input
                  type="number"
                  required
                  min="0"
                  value={formData.merit_points}
                  onChange={(e) => setFormData({ ...formData, merit_points: parseInt(e.target.value) || 0 })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="Nhập điểm công đức"
                />
              </div>

              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Ngày thực hiện <span className="text-red-500">*</span>
                </label>
                <input
                  type="date"
                  required
                  value={formData.activity_date}
                  onChange={(e) => setFormData({ ...formData, activity_date: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                />
              </div>
            </div>

            <div className="bg-amber-50 border border-amber-200 rounded-lg p-3 md:p-4">
              <div className="flex items-start gap-2 md:gap-3">
                <i className="ri-information-line text-amber-600 text-base md:text-lg mt-0.5 flex-shrink-0"></i>
                <div className="text-xs md:text-sm text-amber-800">
                  <p className="font-medium mb-1">Hướng dẫn tính điểm công đức:</p>
                  <ul className="space-y-0.5 ml-4 list-disc">
                    <li>Cúng dường: 10-100 điểm tùy giá trị</li>
                    <li>Công quả: 20-50 điểm/lần</li>
                    <li>Tham gia lễ hội: 30 điểm/lần</li>
                    <li>Thiền định: 15 điểm/buổi</li>
                    <li>Học pháp: 25 điểm/buổi</li>
                  </ul>
                </div>
              </div>
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
              className="w-full md:flex-1 px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap text-sm cursor-pointer"
            >
              {activity ? 'Cập nhật' : 'Thêm mới'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
