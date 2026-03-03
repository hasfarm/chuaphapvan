import { useState } from 'react';
import MainLayout from '../../components/layout/MainLayout';
import FamilyModal from './components/FamilyModal';
import { Family, Follower, familiesData, followersData, getFollowersByFamily, getFollowerById } from '../../mocks/followers-data';
import { useNavigate } from 'react-router-dom';

export default function FamiliesPage() {
  const navigate = useNavigate();
  const [families, setFamilies] = useState<Family[]>(familiesData);
  const [followers, setFollowers] = useState<Follower[]>(followersData);
  const [searchTerm, setSearchTerm] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingFamily, setEditingFamily] = useState<Family | null>(null);
  const [expandedFamily, setExpandedFamily] = useState<string | null>(null);

  const filteredFamilies = families.filter(family =>
    family.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    getFollowerById(family.head_of_family_id)?.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    family.address.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const getMembersByFamily = (familyId: string) => {
    return followers.filter(f => f.family_id === familyId);
  };

  const handleAddFamily = () => {
    setEditingFamily(null);
    setIsModalOpen(true);
  };

  const handleEditFamily = (family: Family) => {
    setEditingFamily(family);
    setIsModalOpen(true);
  };

  const handleDeleteFamily = (familyId: string) => {
    if (window.confirm('Bạn có chắc muốn xóa gia đình này? Các Phật tử trong gia đình sẽ không còn thuộc gia đình nào.')) {
      setFamilies(prev => prev.filter(f => f.id !== familyId));
      setFollowers(prev => prev.map(f => 
        f.family_id === familyId ? { ...f, family_id: '' } : f
      ));
    }
  };

  const handleSaveFamily = (familyData: Partial<Family>, selectedMembers: string[]) => {
    if (editingFamily) {
      // Cập nhật gia đình
      setFamilies(prev => prev.map(f => 
        f.id === editingFamily.id ? { ...f, ...familyData } : f
      ));
      // Cập nhật thành viên
      setFollowers(prev => prev.map(f => {
        if (selectedMembers.includes(f.id)) {
          return { ...f, family_id: editingFamily.id };
        } else if (f.family_id === editingFamily.id) {
          return { ...f, family_id: '' };
        }
        return f;
      }));
    } else {
      // Thêm gia đình mới
      const newFamily: Family = {
        id: `fam-${Date.now()}`,
        name: familyData.name || '',
        address: familyData.address || '',
        head_of_family_id: familyData.head_of_family_id || ''
      };
      setFamilies(prev => [...prev, newFamily]);
      // Cập nhật thành viên
      setFollowers(prev => prev.map(f => 
        selectedMembers.includes(f.id) ? { ...f, family_id: newFamily.id } : f
      ));
    }
    setIsModalOpen(false);
  };

  const toggleExpand = (familyId: string) => {
    setExpandedFamily(prev => prev === familyId ? null : familyId);
  };

  const totalMembers = followers.filter(f => f.family_id).length;
  const aliveMembers = followers.filter(f => f.family_id && f.status === 'alive').length;

  return (
    <MainLayout title="Quản lý Gia đình" subtitle="Quản lý danh sách các gia đình Phật tử">
      <div className="p-4 sm:p-6">
        {/* Stats */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
          <div className="bg-white rounded-xl p-3 sm:p-4 border border-gray-100 shadow-sm">
            <div className="flex items-center gap-2 sm:gap-3">
              <div className="w-8 h-8 sm:w-10 sm:h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i className="ri-home-heart-line text-lg sm:text-xl text-amber-600"></i>
              </div>
              <div className="min-w-0">
                <p className="text-xl sm:text-2xl font-bold text-gray-900">{families.length}</p>
                <p className="text-xs text-gray-500 truncate">Tổng gia đình</p>
              </div>
            </div>
          </div>
          <div className="bg-white rounded-xl p-3 sm:p-4 border border-gray-100 shadow-sm">
            <div className="flex items-center gap-2 sm:gap-3">
              <div className="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i className="ri-user-heart-line text-lg sm:text-xl text-green-600"></i>
              </div>
              <div className="min-w-0">
                <p className="text-xl sm:text-2xl font-bold text-gray-900">{totalMembers}</p>
                <p className="text-xs text-gray-500 truncate">Tổng thành viên</p>
              </div>
            </div>
          </div>
          <div className="bg-white rounded-xl border border-green-200 p-3 sm:p-4 flex items-center gap-2 sm:gap-4">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-heart-pulse-line text-lg sm:text-xl text-green-600"></i>
            </div>
            <div className="min-w-0">
              <p className="text-xs sm:text-sm text-green-600">Tại thế</p>
              <p className="text-xl sm:text-2xl font-bold text-green-700">
                {followersData.filter(f => f.status === 'alive').length}
              </p>
            </div>
          </div>
          <div className="bg-white rounded-xl border border-gray-200 p-3 sm:p-4 flex items-center gap-2 sm:gap-4">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-ghost-line text-lg sm:text-xl text-gray-600"></i>
            </div>
            <div className="min-w-0">
              <p className="text-xs sm:text-sm text-gray-600">Hương linh</p>
              <p className="text-xl sm:text-2xl font-bold text-gray-700">
                {followersData.filter(f => f.status === 'deceased').length}
              </p>
            </div>
          </div>
        </div>

        {/* Search & Add */}
        <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-6">
          <div className="relative flex-1">
            <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="text"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              placeholder="Tìm kiếm gia đình..."
              className="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"
            />
          </div>
          <button
            onClick={handleAddFamily}
            className="w-full sm:w-auto px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 transition-colors text-sm font-medium flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer"
          >
            <i className="ri-add-line text-lg"></i>
            Thêm gia đình
          </button>
        </div>

        {/* Family List */}
        <div className="space-y-3 sm:space-y-4">
          {filteredFamilies.length === 0 ? (
            <div className="bg-white rounded-xl p-8 sm:p-12 text-center border border-gray-100">
              <div className="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i className="ri-home-line text-2xl sm:text-3xl text-gray-400"></i>
              </div>
              <p className="text-sm sm:text-base text-gray-500">Không tìm thấy gia đình nào</p>
            </div>
          ) : (
            filteredFamilies.map(family => {
              const members = getMembersByFamily(family.id);
              const aliveCount = members.filter(m => m.status === 'alive').length;
              const isExpanded = expandedFamily === family.id;
              const headOfFamily = getFollowerById(family.head_of_family_id);

              return (
                <div key={family.id} className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                  {/* Family Header */}
                  <div 
                    className="p-3 sm:p-4 flex items-center gap-3 sm:gap-4 cursor-pointer hover:bg-gray-50 transition-colors"
                    onClick={() => toggleExpand(family.id)}
                  >
                    <div className="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                      <i className="ri-home-heart-line text-xl sm:text-2xl text-white"></i>
                    </div>
                    <div className="flex-1 min-w-0">
                      <h3 className="font-semibold text-sm sm:text-base text-gray-900 truncate">{family.name}</h3>
                      <p className="text-xs sm:text-sm text-gray-500 truncate">
                        <i className="ri-map-pin-line mr-1"></i>
                        {family.address || 'Chưa có địa chỉ'}
                      </p>
                    </div>
                    <div className="flex items-center gap-3 sm:gap-6">
                      <div className="text-center hidden sm:block">
                        <p className="text-base sm:text-lg font-bold text-amber-600">{members.length}</p>
                        <p className="text-xs text-gray-500">Thành viên</p>
                      </div>
                      <div className="text-center hidden sm:block">
                        <p className="text-base sm:text-lg font-bold text-green-600">{aliveCount}</p>
                        <p className="text-xs text-gray-500">Còn sống</p>
                      </div>
                      <div className="flex items-center gap-1 sm:gap-2">
                        <button
                          onClick={(e) => { e.stopPropagation(); handleEditFamily(family); }}
                          className="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
                        >
                          <i className="ri-edit-line text-base sm:text-lg"></i>
                        </button>
                        <button
                          onClick={(e) => { e.stopPropagation(); handleDeleteFamily(family.id); }}
                          className="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                        >
                          <i className="ri-delete-bin-line text-base sm:text-lg"></i>
                        </button>
                        <div className="w-8 h-8 flex items-center justify-center">
                          <i className={`ri-arrow-${isExpanded ? 'up' : 'down'}-s-line text-lg sm:text-xl text-gray-400`}></i>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Mobile Stats - Show when collapsed */}
                  {!isExpanded && (
                    <div className="sm:hidden px-3 pb-3 flex gap-4">
                      <div className="text-center flex-1">
                        <p className="text-base font-bold text-amber-600">{members.length}</p>
                        <p className="text-xs text-gray-500">Thành viên</p>
                      </div>
                      <div className="text-center flex-1">
                        <p className="text-base font-bold text-green-600">{aliveCount}</p>
                        <p className="text-xs text-gray-500">Còn sống</p>
                      </div>
                    </div>
                  )}

                  {/* Family Members */}
                  {isExpanded && (
                    <div className="border-t border-gray-100 bg-gray-50 p-3 sm:p-4">
                      <div className="flex items-center justify-between mb-3">
                        <p className="text-xs sm:text-sm font-medium text-gray-700">
                          Chủ hộ: {headOfFamily ? (
                            <span 
                              className="text-amber-600 hover:text-amber-700 cursor-pointer hover:underline"
                              onClick={() => navigate(`/followers/${headOfFamily.id}`)}
                            >
                              {headOfFamily.full_name}
                            </span>
                          ) : (
                            <span className="text-gray-500">Chưa xác định</span>
                          )}
                        </p>
                      </div>
                      
                      {members.length === 0 ? (
                        <p className="text-xs sm:text-sm text-gray-500 text-center py-4">
                          Chưa có thành viên nào trong gia đình
                        </p>
                      ) : (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                          {members.map(member => (
                            <div 
                              key={member.id}
                              className="bg-white rounded-lg p-2.5 sm:p-3 flex items-center gap-2 sm:gap-3 border border-gray-100 hover:shadow-md transition-shadow cursor-pointer"
                              onClick={() => navigate(`/followers/${member.id}`)}
                            >
                              <div className={`w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center flex-shrink-0 ${
                                member.status === 'alive' 
                                  ? 'bg-gradient-to-br from-amber-400 to-orange-500' 
                                  : 'bg-gray-300'
                              }`}>
                                <span className="text-white text-xs sm:text-sm font-medium">
                                  {member.full_name.charAt(0)}
                                </span>
                              </div>
                              <div className="flex-1 min-w-0">
                                <p className="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                  {member.full_name}
                                  {member.id === family.head_of_family_id && (
                                    <span className="ml-1 sm:ml-2 px-1.5 sm:px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs">Chủ hộ</span>
                                  )}
                                </p>
                                <p className="text-xs text-gray-500 truncate">
                                  PD: {member.dharma_name}
                                </p>
                              </div>
                              <span className={`px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap ${
                                member.status === 'alive'
                                  ? 'bg-green-100 text-green-700'
                                  : 'bg-gray-100 text-gray-600'
                              }`}>
                                {member.status === 'alive' ? 'Tại thế' : 'Hương linh'}
                              </span>
                            </div>
                          ))}
                        </div>
                      )}
                    </div>
                  )}
                </div>
              );
            })
          )}
        </div>
      </div>

      {/* Modal */}
      <FamilyModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onSave={handleSaveFamily}
        family={editingFamily}
        currentMembers={editingFamily ? getMembersByFamily(editingFamily.id) : []}
        allFollowers={followers}
      />
    </MainLayout>
  );
}
