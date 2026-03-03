
import { useState } from 'react';
import MainLayout from '../../components/layout/MainLayout';
import { followersData, familiesData, getFamilyById, Follower } from '../../mocks/followers-data';
import FollowerModal from './components/FollowerModal';
import { useNavigate } from 'react-router-dom';

export default function FollowersPage() {
  const navigate = useNavigate();
  const [followers, setFollowers] = useState<Follower[]>(followersData);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState<'all' | 'alive' | 'deceased'>('all');
  const [filterFamily, setFilterFamily] = useState<string>('all');
  const [showModal, setShowModal] = useState(false);
  const [selectedFollower, setSelectedFollower] = useState<Follower | null>(null);

  const handleAddFollower = () => {
    setSelectedFollower(null);
    setShowModal(true);
  };

  const handleEditFollower = (follower: Follower) => {
    setSelectedFollower(follower);
    setShowModal(true);
  };

  const handleDeleteFollower = (id: string) => {
    if (!confirm('Bạn có chắc chắn muốn xóa Phật tử này?')) return;
    setFollowers(followers.filter(f => f.id !== id));
  };

  const handleSaveFollower = (data: Follower) => {
    if (selectedFollower) {
      setFollowers(followers.map(f => f.id === data.id ? data : f));
    } else {
      setFollowers([data, ...followers]);
    }
    setShowModal(false);
  };

  const filteredFollowers = followers.filter(f => {
    const matchSearch = f.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      f.dharma_name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      f.phone?.includes(searchTerm);
    
    const matchStatus = filterStatus === 'all' || f.status === filterStatus;
    const matchFamily = filterFamily === 'all' || f.family_id === filterFamily;
    
    return matchSearch && matchStatus && matchFamily;
  });

  return (
    <MainLayout title="Quản lý Phật tử" subtitle="Danh sách và thông tin Phật tử">
      <div className="space-y-4 sm:space-y-6">
        {/* Thanh tìm kiếm và bộ lọc - Stack dọc trên mobile */}
        <div className="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 sm:gap-4">
          <div className="flex-1 min-w-full sm:min-w-[280px]">
            <div className="relative">
              <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-amber-500 text-base sm:text-lg"></i>
              <input
                type="text"
                placeholder="Tìm kiếm theo tên, pháp danh, SĐT..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>
          </div>
          
          {/* Bộ lọc - Full width trên mobile */}
          <select
            value={filterStatus}
            onChange={(e) => setFilterStatus(e.target.value as 'all' | 'alive' | 'deceased')}
            className="w-full sm:w-auto px-4 py-2.5 sm:py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-white cursor-pointer"
          >
            <option value="all">Tất cả tình trạng</option>
            <option value="alive">Tại thế</option>
            <option value="deceased">Hương linh</option>
          </select>

          <select
            value={filterFamily}
            onChange={(e) => setFilterFamily(e.target.value)}
            className="w-full sm:w-auto px-4 py-2.5 sm:py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-white cursor-pointer"
          >
            <option value="all">Tất cả gia đình</option>
            {familiesData.map(family => (
              <option key={family.id} value={family.id}>{family.name}</option>
            ))}
          </select>

          <button
            onClick={handleAddFollower}
            className="w-full sm:w-auto px-6 py-2.5 sm:py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg font-medium hover:shadow-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer"
          >
            <i className="ri-user-add-line text-lg"></i>
            Thêm Phật tử
          </button>
        </div>

        {/* Thống kê nhanh - 2x2 grid trên mobile */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
          <div className="bg-white rounded-xl border border-amber-200 p-3 sm:p-4 flex flex-col sm:flex-row items-center sm:items-center gap-2 sm:gap-4">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-group-line text-lg sm:text-xl text-amber-600"></i>
            </div>
            <div className="text-center sm:text-left">
              <p className="text-xs sm:text-sm text-amber-600">Tổng Phật tử</p>
              <p className="text-xl sm:text-2xl font-bold text-amber-900">{followers.length}</p>
            </div>
          </div>
          <div className="bg-white rounded-xl border border-green-200 p-3 sm:p-4 flex flex-col sm:flex-row items-center sm:items-center gap-2 sm:gap-4">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-heart-pulse-line text-lg sm:text-xl text-green-600"></i>
            </div>
            <div className="text-center sm:text-left">
              <p className="text-xs sm:text-sm text-green-600">Tại thế</p>
              <p className="text-xl sm:text-2xl font-bold text-green-700">{followers.filter(f => f.status === 'alive').length}</p>
            </div>
          </div>
          <div className="bg-white rounded-xl border border-gray-200 p-3 sm:p-4 flex flex-col sm:flex-row items-center sm:items-center gap-2 sm:gap-4">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-ghost-line text-lg sm:text-xl text-gray-600"></i>
            </div>
            <div className="text-center sm:text-left">
              <p className="text-xs sm:text-sm text-gray-600">Hương linh</p>
              <p className="text-xl sm:text-2xl font-bold text-gray-700">{followers.filter(f => f.status === 'deceased').length}</p>
            </div>
          </div>
          <div className="bg-white rounded-xl border border-orange-200 p-3 sm:p-4 flex flex-col sm:flex-row items-center sm:items-center gap-2 sm:gap-4">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-home-heart-line text-lg sm:text-xl text-orange-600"></i>
            </div>
            <div className="text-center sm:text-left">
              <p className="text-xs sm:text-sm text-orange-600">Số gia đình</p>
              <p className="text-xl sm:text-2xl font-bold text-orange-700">{familiesData.length}</p>
            </div>
          </div>
        </div>

        {/* Bảng danh sách - Horizontal scroll trên mobile */}
        <div className="bg-white rounded-xl border border-amber-200 shadow-sm overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full min-w-[800px]">
              <thead className="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-200">
                <tr>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900">Họ tên</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900 hidden sm:table-cell">Pháp danh</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900 hidden md:table-cell">Năm sinh (DL)</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900">Năm sinh (ÂL)</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900 hidden lg:table-cell">Giới tính</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900">Tình trạng</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-amber-900 hidden md:table-cell">Gia đình</th>
                  <th className="px-3 sm:px-4 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-amber-900">Thao tác</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-amber-100">
                {filteredFollowers.length === 0 ? (
                  <tr>
                    <td colSpan={8} className="px-6 py-12 text-center text-amber-600">
                      <i className="ri-user-line text-4xl mb-2"></i>
                      <p className="text-sm">Không tìm thấy Phật tử nào</p>
                    </td>
                  </tr>
                ) : (
                  filteredFollowers.map((follower) => {
                    const family = getFamilyById(follower.family_id);
                    return (
                      <tr
                        key={follower.id}
                        className="hover:bg-amber-50 transition-colors cursor-pointer"
                        onClick={() => navigate(`/followers/${follower.id}`)}
                      >
                        <td className="px-3 sm:px-4 py-3 sm:py-4">
                          <div className="flex items-center gap-2 sm:gap-3">
                            <div className={`w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm ${
                              follower.status === 'deceased' 
                                ? 'bg-gradient-to-br from-gray-400 to-gray-500' 
                                : 'bg-gradient-to-br from-amber-400 to-orange-500'
                            }`}>
                              {follower.full_name.charAt(0)}
                            </div>
                            <span className={`font-medium text-xs sm:text-sm ${follower.status === 'deceased' ? 'text-gray-600' : 'text-amber-900'}`}>
                              {follower.full_name}
                            </span>
                          </div>
                        </td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4 text-amber-700 text-xs sm:text-sm hidden sm:table-cell">{follower.dharma_name || '-'}</td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4 text-amber-700 text-xs sm:text-sm hidden md:table-cell">{follower.birth_year_solar}</td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4">
                          <span className="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-xs">
                            {follower.birth_year_lunar}
                          </span>
                        </td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4 text-amber-700 text-xs sm:text-sm hidden lg:table-cell">
                          {follower.gender === 'male' ? 'Nam' : 'Nữ'}
                        </td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4">
                          <span className={`px-2 sm:px-3 py-1 rounded-full text-xs font-medium ${
                            follower.status === 'alive' 
                              ? 'bg-green-100 text-green-700' 
                              : 'bg-gray-100 text-gray-600'
                          }`}>
                            {follower.status === 'alive' ? 'Tại thế' : 'Hương linh'}
                          </span>
                        </td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4 hidden md:table-cell">
                          <span className="text-xs sm:text-sm text-amber-700">{family?.name || '-'}</span>
                        </td>
                        <td className="px-3 sm:px-4 py-3 sm:py-4">
                          <div className="flex items-center justify-center gap-1 sm:gap-2" onClick={(e) => e.stopPropagation()}>
                            <button
                              onClick={() => handleEditFollower(follower)}
                              className="p-1.5 sm:p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
                              title="Chỉnh sửa"
                            >
                              <i className="ri-edit-line text-base sm:text-lg w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                            </button>
                            <button
                              onClick={() => handleDeleteFollower(follower.id)}
                              className="p-1.5 sm:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                              title="Xóa"
                            >
                              <i className="ri-delete-bin-line text-base sm:text-lg w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    );
                  })
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {showModal && (
        <FollowerModal
          follower={selectedFollower}
          onClose={() => setShowModal(false)}
          onSave={handleSaveFollower}
        />
      )}
    </MainLayout>
  );
}
