import { useState, useEffect } from 'react';

interface StarWorshipModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSave: (data: any) => void;
  worship: any;
  families: any[];
}

export default function StarWorshipModal({ isOpen, onClose, onSave, worship, families }: StarWorshipModalProps) {
  const [formData, setFormData] = useState({
    family_id: '',
    follower_id: '',
    worship_date_solar: '',
    worship_date_lunar: '',
    amount: 0,
    status: 'pending',
    notes: ''
  });

  const [selectedFamily, setSelectedFamily] = useState<any>(null);

  useEffect(() => {
    if (worship) {
      setFormData({
        family_id: worship.family_id || '',
        follower_id: worship.follower_id || '',
        worship_date_solar: worship.worship_date_solar?.split('T')[0] || '',
        worship_date_lunar: worship.worship_date_lunar || '',
        amount: worship.amount || 0,
        status: worship.status || 'pending',
        notes: worship.notes || ''
      });
      
      const family = families.find(f => f.id === worship.family_id);
      setSelectedFamily(family);
    } else {
      setFormData({
        family_id: '',
        follower_id: '',
        worship_date_solar: '',
        worship_date_lunar: '',
        amount: 0,
        status: 'pending',
        notes: ''
      });
      setSelectedFamily(null);
    }
  }, [worship, families, isOpen]);

  const handleFamilyChange = (familyId: string) => {
    const family = families.find(f => f.id === familyId);
    setSelectedFamily(family);
    setFormData({ ...formData, family_id: familyId, follower_id: '' });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSave(formData);
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-0 md:p-4">
      <div className="bg-white rounded-none md:rounded-lg shadow-xl w-full h-full md:max-w-2xl md:w-full md:max-h-[90vh] md:h-auto overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-amber-100 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
          <h2 className="text-lg md:text-xl font-bold text-amber-900">
            {worship ? 'Chỉnh sửa đăng ký cúng sao' : 'Đăng ký cúng sao mới'}
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
                Chọn gia đình <span className="text-red-500">*</span>
              </label>
              <select
                required
                value={formData.family_id}
                onChange={(e) => handleFamilyChange(e.target.value)}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
              >
                <option value="">-- Chọn gia đình --</option>
                {families.map(family => (
                  <option key={family.id} value={family.id}>
                    {family.name}
                  </option>
                ))}
              </select>
            </div>

            {selectedFamily && (
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Chọn Phật tử <span className="text-red-500">*</span>
                </label>
                <select
                  required
                  value={formData.follower_id}
                  onChange={(e) => setFormData({ ...formData, follower_id: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="">-- Chọn Phật tử --</option>
                  {selectedFamily.members?.map((member: any) => {
                    const starInfo = member.star_info;
                    return (
                      <option key={member.id} value={member.id}>
                        {member.full_name} ({member.dharma_name}) - Sao {starInfo?.star_name}
                      </option>
                    );
                  })}
                </select>
              </div>
            )}

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Ngày cúng (Dương lịch) <span className="text-red-500">*</span>
                </label>
                <input
                  type="date"
                  required
                  value={formData.worship_date_solar}
                  onChange={(e) => setFormData({ ...formData, worship_date_solar: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                />
              </div>

              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Ngày cúng (Âm lịch)
                </label>
                <input
                  type="text"
                  value={formData.worship_date_lunar}
                  onChange={(e) => setFormData({ ...formData, worship_date_lunar: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="VD: 15/01/2024"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Số tiền cúng <span className="text-red-500">*</span>
                </label>
                <input
                  type="number"
                  required
                  min="0"
                  step="10000"
                  value={formData.amount}
                  onChange={(e) => setFormData({ ...formData, amount: parseFloat(e.target.value) || 0 })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="Nhập số tiền"
                />
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
                  <option value="pending">Chờ cúng</option>
                  <option value="completed">Đã cúng</option>
                  <option value="cancelled">Đã hủy</option>
                </select>
              </div>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Ghi chú
              </label>
              <textarea
                value={formData.notes}
                onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                rows={3}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm resize-none"
                placeholder="Nhập ghi chú thêm"
              />
            </div>

            <div className="bg-amber-50 border border-amber-200 rounded-lg p-3 md:p-4">
              <div className="flex items-start gap-2 md:gap-3">
                <i className="ri-information-line text-amber-600 text-base md:text-lg mt-0.5 flex-shrink-0"></i>
                <div className="text-xs md:text-sm text-amber-800">
                  <p className="font-medium mb-1">Lưu ý về cúng sao:</p>
                  <ul className="space-y-0.5 ml-4 list-disc">
                    <li>Sao chiếu mệnh được tính dựa trên tuổi âm lịch và giới tính</li>
                    <li>Nên cúng sao vào đầu năm hoặc ngày sinh nhật âm lịch</li>
                    <li>Sao xấu cần giải hạn để tránh vận xui</li>
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
              {worship ? 'Cập nhật' : 'Đăng ký'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
