import { useState, useEffect } from 'react';
import { Family, Follower } from '../../../mocks/followers-data';

interface FamilyModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSave: (familyData: Partial<Family>, selectedMembers: string[]) => void;
  family: Family | null;
  currentMembers: Follower[];
  allFollowers: Follower[];
}

export default function FamilyModal({
  isOpen,
  onClose,
  onSave,
  family,
  currentMembers,
  allFollowers
}: FamilyModalProps) {
  const [formData, setFormData] = useState({
    name: '',
    address: '',
    head_of_family_id: ''
  });
  const [selectedMembers, setSelectedMembers] = useState<string[]>([]);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    if (family) {
      setFormData({
        name: family.name,
        address: family.address,
        head_of_family_id: family.head_of_family_id
      });
      setSelectedMembers(currentMembers.map(m => m.id));
    } else {
      setFormData({
        name: '',
        address: '',
        head_of_family_id: ''
      });
      setSelectedMembers([]);
    }
    setSearchTerm('');
  }, [family, currentMembers, isOpen]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    // Kiểm tra chủ hộ có trong danh sách thành viên không
    if (formData.head_of_family_id && !selectedMembers.includes(formData.head_of_family_id)) {
      alert('Chủ hộ phải là thành viên trong gia đình');
      return;
    }
    
    onSave(formData, selectedMembers);
  };

  const toggleMember = (followerId: string) => {
    setSelectedMembers(prev => {
      if (prev.includes(followerId)) {
        // Nếu bỏ chọn chủ hộ, xóa chủ hộ
        if (followerId === formData.head_of_family_id) {
          setFormData(prev => ({ ...prev, head_of_family_id: '' }));
        }
        return prev.filter(id => id !== followerId);
      } else {
        return [...prev, followerId];
      }
    });
  };

  const availableFollowers = allFollowers.filter(f =>
    f.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    f.dharma_name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  // Danh sách Phật tử đã chọn để chọn chủ hộ
  const selectedFollowersList = allFollowers.filter(f => selectedMembers.includes(f.id));

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-0 md:p-4">
      <div className="bg-white rounded-none md:rounded-xl shadow-xl w-full h-full md:max-w-4xl md:w-full md:max-h-[90vh] md:h-auto overflow-y-auto">
        <div className="p-4 md:p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
          <div className="flex items-center justify-between">
            <h2 className="text-lg md:text-xl font-bold text-gray-900">
              {family ? 'Chỉnh sửa gia đình' : 'Thêm gia đình mới'}
            </h2>
            <button
              onClick={onClose}
              className="p-2 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer"
            >
              <i className="ri-close-line text-xl text-gray-700 w-6 h-6 flex items-center justify-center"></i>
            </button>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="p-4 md:p-6">
          {/* Thông tin gia đình */}
          <div className="space-y-3 md:space-y-4 mb-4 md:mb-6">
            <div>
              <label className="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                Tên gia đình <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"
                placeholder="Ví dụ: Gia đình Nguyễn Văn"
                required
              />
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                Địa chỉ
              </label>
              <input
                type="text"
                value={formData.address}
                onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"
                placeholder="Địa chỉ gia đình"
              />
            </div>

            <div>
              <label className="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                Chủ hộ
              </label>
              <select
                value={formData.head_of_family_id}
                onChange={(e) => setFormData({ ...formData, head_of_family_id: e.target.value })}
                className="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 cursor-pointer text-sm"
                disabled={selectedFollowersList.length === 0}
              >
                <option value="">-- Chọn chủ hộ --</option>
                {selectedFollowersList.map(follower => (
                  <option key={follower.id} value={follower.id}>
                    {follower.full_name} ({follower.dharma_name})
                  </option>
                ))}
              </select>
              {selectedFollowersList.length === 0 && (
                <p className="text-xs text-gray-500 mt-1">Vui lòng chọn thành viên trước</p>
              )}
            </div>
          </div>

          {/* Chọn thành viên */}
          <div className="border-t border-gray-200 pt-4 md:pt-6">
            <h3 className="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">
              Thành viên gia đình ({selectedMembers.length})
            </h3>

            {/* Tìm kiếm */}
            <div className="relative mb-3 md:mb-4">
              <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Tìm kiếm Phật tử..."
                className="w-full pl-9 md:pl-10 pr-3 md:pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"
              />
            </div>

            {/* Danh sách Phật tử */}
            <div className="max-h-64 md:max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
              {availableFollowers.length === 0 ? (
                <div className="p-6 md:p-8 text-center text-gray-500 text-sm">
                  Không tìm thấy Phật tử nào
                </div>
              ) : (
                <div className="divide-y divide-gray-100">
                  {availableFollowers.map(follower => {
                    const isSelected = selectedMembers.includes(follower.id);
                    const isHeadOfFamily = follower.id === formData.head_of_family_id;
                    
                    return (
                      <div
                        key={follower.id}
                        className={`p-2.5 md:p-3 flex items-center gap-2 md:gap-3 hover:bg-gray-50 cursor-pointer transition-colors ${
                          isSelected ? 'bg-amber-50' : ''
                        }`}
                        onClick={() => toggleMember(follower.id)}
                      >
                        <input
                          type="checkbox"
                          checked={isSelected}
                          onChange={() => {}}
                          className="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500 cursor-pointer flex-shrink-0"
                        />
                        <div className={`w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center flex-shrink-0 ${
                          follower.status === 'alive' 
                            ? 'bg-gradient-to-br from-amber-400 to-orange-500' 
                            : 'bg-gray-300'
                        }`}>
                          <span className="text-white text-xs md:text-sm font-medium">
                            {follower.full_name.charAt(0)}
                          </span>
                        </div>
                        <div className="flex-1 min-w-0">
                          <div className="flex items-center gap-1.5 md:gap-2 flex-wrap">
                            <p className="text-xs md:text-sm font-medium text-gray-900 truncate">
                              {follower.full_name}
                            </p>
                            {isHeadOfFamily && (
                              <span className="px-1.5 md:px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-[10px] md:text-xs whitespace-nowrap">
                                Chủ hộ
                              </span>
                            )}
                            <span className={`px-1.5 md:px-2 py-0.5 rounded-full text-[10px] md:text-xs whitespace-nowrap ${
                              follower.status === 'alive'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-600'
                            }`}>
                              {follower.status === 'alive' ? 'Tại thế' : 'Hương linh'}
                            </span>
                          </div>
                          <p className="text-[10px] md:text-xs text-gray-500 truncate mt-0.5">
                            PD: {follower.dharma_name} • {follower.birth_year_solar} ({follower.birth_year_lunar})
                          </p>
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}
            </div>
          </div>

          {/* Buttons */}
          <div className="flex flex-col md:flex-row justify-end gap-2 md:gap-3 mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
            <button
              type="button"
              onClick={onClose}
              className="w-full md:w-auto px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer whitespace-nowrap text-sm"
            >
              Hủy
            </button>
            <button
              type="submit"
              className="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 transition-colors cursor-pointer whitespace-nowrap text-sm"
            >
              {family ? 'Cập nhật' : 'Thêm mới'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
