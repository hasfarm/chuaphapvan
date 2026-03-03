import { useState, useEffect } from 'react';
import { supabase, type Finance, type BuddhistFollower } from '../../../lib/supabase';

interface FinanceModalProps {
  finance: Finance | null;
  onClose: () => void;
  onSuccess: () => void;
}

export default function FinanceModal({ finance, onClose, onSuccess }: FinanceModalProps) {
  const [formData, setFormData] = useState({
    follower_id: '',
    transaction_type: 'income',
    category: 'donation',
    amount: 0,
    transaction_date: '',
    description: '',
    payment_method: 'cash',
    receipt_url: ''
  });
  const [loading, setLoading] = useState(false);
  const [followers, setFollowers] = useState<BuddhistFollower[]>([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [showDropdown, setShowDropdown] = useState(false);

  useEffect(() => {
    fetchFollowers();
    if (finance) {
      setFormData({
        follower_id: finance.follower_id || '',
        transaction_type: finance.transaction_type || 'income',
        category: finance.category || 'donation',
        amount: finance.amount || 0,
        transaction_date: finance.transaction_date ? finance.transaction_date.split('T')[0] : '',
        description: finance.description || '',
        payment_method: finance.payment_method || 'cash',
        receipt_url: finance.receipt_url || ''
      });
    }
  }, [finance]);

  const fetchFollowers = async () => {
    try {
      const { data, error } = await supabase
        .from('buddhist_followers')
        .select('id, full_name, dharma_name')
        .order('full_name', { ascending: true });

      if (error) throw error;
      setFollowers(data || []);
    } catch (error) {
      console.error('Error fetching followers:', error);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const dataToSave = {
        ...formData,
        follower_id: formData.follower_id || null
      };

      if (finance) {
        const { error } = await supabase
          .from('finances')
          .update(dataToSave)
          .eq('id', finance.id);

        if (error) throw error;
      } else {
        const { error } = await supabase
          .from('finances')
          .insert([dataToSave]);

        if (error) throw error;
      }

      onSuccess();
    } catch (error) {
      console.error('Error saving finance:', error);
      alert('Có lỗi xảy ra khi lưu giao dịch');
    } finally {
      setLoading(false);
    }
  };

  const filteredFollowers = followers.filter(f => 
    f.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    f.dharma_name?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const selectedFollower = followers.find(f => f.id === formData.follower_id);

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-0 md:p-4">
      <div className="bg-white rounded-none md:rounded-lg shadow-xl w-full h-full md:max-w-2xl md:w-full md:max-h-[90vh] md:h-auto overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-amber-100 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
          <h2 className="text-lg md:text-xl font-bold text-amber-900">
            {finance ? 'Chỉnh sửa giao dịch' : 'Thêm giao dịch mới'}
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
            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Loại giao dịch <span className="text-red-500">*</span>
                </label>
                <select
                  required
                  value={formData.transaction_type}
                  onChange={(e) => setFormData({ ...formData, transaction_type: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="income">Thu</option>
                  <option value="expense">Chi</option>
                </select>
              </div>

              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Danh mục <span className="text-red-500">*</span>
                </label>
                <select
                  required
                  value={formData.category}
                  onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
                >
                  <option value="donation">Cúng dường</option>
                  <option value="ceremony">Lễ hội</option>
                  <option value="maintenance">Bảo trì</option>
                  <option value="utilities">Tiện ích</option>
                  <option value="salary">Lương</option>
                  <option value="supplies">Vật phẩm</option>
                  <option value="other">Khác</option>
                </select>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Số tiền <span className="text-red-500">*</span>
                </label>
                <input
                  type="number"
                  required
                  min="0"
                  step="1000"
                  value={formData.amount}
                  onChange={(e) => setFormData({ ...formData, amount: parseFloat(e.target.value) || 0 })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="Nhập số tiền"
                />
              </div>

              <div>
                <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                  Ngày giao dịch <span className="text-red-500">*</span>
                </label>
                <input
                  type="date"
                  required
                  value={formData.transaction_date}
                  onChange={(e) => setFormData({ ...formData, transaction_date: e.target.value })}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Phật tử (tùy chọn)
              </label>
              <div className="relative">
                <input
                  type="text"
                  value={selectedFollower ? `${selectedFollower.full_name}${selectedFollower.dharma_name ? ` (${selectedFollower.dharma_name})` : ''}` : ''}
                  onChange={(e) => {
                    setSearchTerm(e.target.value);
                    setShowDropdown(true);
                    if (!e.target.value) {
                      setFormData({ ...formData, follower_id: '' });
                    }
                  }}
                  onFocus={() => setShowDropdown(true)}
                  className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                  placeholder="Tìm kiếm Phật tử (nếu có)..."
                />
                {showDropdown && (
                  <div className="absolute z-10 w-full mt-1 bg-white border border-amber-200 rounded-lg shadow-lg max-h-48 md:max-h-60 overflow-y-auto">
                    {filteredFollowers.length > 0 ? (
                      filteredFollowers.map((follower) => (
                        <button
                          key={follower.id}
                          type="button"
                          onClick={() => {
                            setFormData({ ...formData, follower_id: follower.id });
                            setShowDropdown(false);
                            setSearchTerm('');
                          }}
                          className="w-full px-3 md:px-4 py-2 md:py-2.5 text-left hover:bg-amber-50 transition-colors text-sm"
                        >
                          <div className="font-medium text-amber-900">{follower.full_name}</div>
                          {follower.dharma_name && (
                            <div className="text-xs text-amber-600">Pháp danh: {follower.dharma_name}</div>
                          )}
                        </button>
                      ))
                    ) : (
                      <div className="px-3 md:px-4 py-2 md:py-2.5 text-sm text-amber-600">Không tìm thấy Phật tử</div>
                    )}
                  </div>
                )}
              </div>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Phương thức thanh toán
              </label>
              <select
                value={formData.payment_method}
                onChange={(e) => setFormData({ ...formData, payment_method: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm cursor-pointer"
              >
                <option value="cash">Tiền mặt</option>
                <option value="bank_transfer">Chuyển khoản</option>
                <option value="momo">MoMo</option>
                <option value="zalopay">ZaloPay</option>
                <option value="other">Khác</option>
              </select>
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Mô tả chi tiết
              </label>
              <textarea
                value={formData.description}
                onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                rows={3}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm resize-none"
                placeholder="Nhập mô tả chi tiết về giao dịch"
              />
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-amber-900 mb-1 md:mb-2">
                Link biên lai (tùy chọn)
              </label>
              <input
                type="url"
                value={formData.receipt_url}
                onChange={(e) => setFormData({ ...formData, receipt_url: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                placeholder="https://..."
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
              {loading ? 'Đang lưu...' : finance ? 'Cập nhật' : 'Thêm mới'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
