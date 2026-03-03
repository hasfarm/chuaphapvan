import { useState, useEffect } from 'react';
import { supabase, type BuddhistFollower } from '../../../lib/supabase';

interface FollowerModalProps {
  follower: BuddhistFollower | null;
  onClose: () => void;
  onSuccess: () => void;
}

export default function FollowerModal({ follower, onClose, onSuccess }: FollowerModalProps) {
  const [formData, setFormData] = useState({
    full_name: '',
    dharma_name: '',
    birth_year_solar: '',
    birth_year_lunar: '',
    gender: 'male',
    phone: '',
    email: '',
    address: '',
    status: 'alive',
    death_date_solar: '',
    death_date_lunar: '',
    notes: ''
  });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (follower) {
      setFormData({
        full_name: follower.full_name || '',
        dharma_name: follower.dharma_name || '',
        birth_year_solar: follower.birth_year_solar || '',
        birth_year_lunar: follower.birth_year_lunar || '',
        gender: follower.gender || 'male',
        phone: follower.phone || '',
        email: follower.email || '',
        address: follower.address || '',
        status: follower.status || 'alive',
        death_date_solar: follower.death_date_solar || '',
        death_date_lunar: follower.death_date_lunar || '',
        notes: follower.notes || ''
      });
    }
  }, [follower]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      if (follower) {
        const { error } = await supabase
          .from('buddhist_followers')
          .update({
            ...formData,
            updated_at: new Date().toISOString()
          })
          .eq('id', follower.id);

        if (error) throw error;
      } else {
        const { error } = await supabase
          .from('buddhist_followers')
          .insert([formData]);

        if (error) throw error;
      }

      onSuccess();
    } catch (error) {
      console.error('Error saving follower:', error);
      alert('Có lỗi xảy ra khi lưu thông tin Phật tử');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-0 md:p-4">
      <div className="bg-white rounded-none md:rounded-lg shadow-xl w-full h-full md:max-w-3xl md:w-full md:max-h-[90vh] md:h-auto overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-amber-100 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between z-10">
          <h2 className="text-lg md:text-xl font-bold text-amber-900">
            {follower ? 'Chỉnh sửa Phật tử' : 'Thêm Phật tử mới'}
          </h2>
          <button
            onClick={onClose}
            className="p-2 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
          >
            <i className="ri-close-line text-xl text-amber-700 w-6 h-6 flex items-center justify-center"></i>
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-4 md:p-6">
          <div className="space-y-4 md:space-y-5">
            {/* Thông tin cơ bản */}
            <div>
              <h3 className="text-sm md:text-base font-semibold text-amber-900 mb-3 md:mb-4">Thông tin cơ bản</h3>
              <div className="space-y-3 md:space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Họ và tên <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      required
                      value={formData.full_name}
                      onChange={(e) => setFormData({ ...formData, full_name: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      placeholder="Nhập họ và tên"
                    />
                  </div>

                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Pháp danh
                    </label>
                    <input
                      type="text"
                      value={formData.dharma_name}
                      onChange={(e) => setFormData({ ...formData, dharma_name: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      placeholder="Nhập pháp danh"
                    />
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Năm sinh (Dương lịch)
                    </label>
                    <input
                      type="text"
                      value={formData.birth_year_solar}
                      onChange={(e) => setFormData({ ...formData, birth_year_solar: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      placeholder="VD: 1990"
                    />
                  </div>

                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Năm sinh (Âm lịch)
                    </label>
                    <input
                      type="text"
                      value={formData.birth_year_lunar}
                      onChange={(e) => setFormData({ ...formData, birth_year_lunar: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      placeholder="VD: Canh Ngọ"
                    />
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Giới tính
                    </label>
                    <select
                      value={formData.gender}
                      onChange={(e) => setFormData({ ...formData, gender: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                    >
                      <option value="male">Nam</option>
                      <option value="female">Nữ</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Tình trạng
                    </label>
                    <select
                      value={formData.status}
                      onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                    >
                      <option value="alive">Tại thế</option>
                      <option value="deceased">Hương linh</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {/* Thông tin ngày mất - chỉ hiện khi status là deceased */}
            {formData.status === 'deceased' && (
              <div className="border-t border-amber-100 pt-4 md:pt-5">
                <h3 className="text-sm md:text-base font-semibold text-amber-900 mb-3 md:mb-4">Thông tin ngày mất</h3>
                <div className="space-y-3 md:space-y-4">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                    <div>
                      <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                        Ngày mất (Dương lịch)
                      </label>
                      <input
                        type="date"
                        value={formData.death_date_solar}
                        onChange={(e) => setFormData({ ...formData, death_date_solar: e.target.value })}
                        className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      />
                    </div>

                    <div>
                      <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                        Ngày mất (Âm lịch)
                      </label>
                      <input
                        type="text"
                        value={formData.death_date_lunar}
                        onChange={(e) => setFormData({ ...formData, death_date_lunar: e.target.value })}
                        className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                        placeholder="VD: 15/01/Quý Mão"
                      />
                    </div>
                  </div>
                  <p className="text-xs text-amber-600">
                    <i className="ri-information-line mr-1"></i>
                    Thông tin ngày mất sẽ được dùng để tạo lịch cúng giỗ tự động
                  </p>
                </div>
              </div>
            )}

            {/* Thông tin liên hệ */}
            <div className="border-t border-amber-100 pt-4 md:pt-5">
              <h3 className="text-sm md:text-base font-semibold text-amber-900 mb-3 md:mb-4">Thông tin liên hệ</h3>
              <div className="space-y-3 md:space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Số điện thoại
                    </label>
                    <input
                      type="tel"
                      value={formData.phone}
                      onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      placeholder="Nhập số điện thoại"
                    />
                  </div>

                  <div>
                    <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                      Email
                    </label>
                    <input
                      type="email"
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                      className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                      placeholder="Nhập email"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                    Địa chỉ
                  </label>
                  <input
                    type="text"
                    value={formData.address}
                    onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                    className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                    placeholder="Nhập địa chỉ"
                  />
                </div>
              </div>
            </div>

            {/* Ghi chú */}
            <div className="border-t border-amber-100 pt-4 md:pt-5">
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Ghi chú
              </label>
              <textarea
                value={formData.notes}
                onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                rows={3}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm resize-none"
                placeholder="Nhập ghi chú thêm về Phật tử"
              />
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
              {loading ? 'Đang lưu...' : follower ? 'Cập nhật' : 'Thêm mới'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
