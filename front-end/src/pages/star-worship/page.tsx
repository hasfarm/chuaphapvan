
import { Suspense, useState, useMemo } from 'react';
import { Link } from 'react-router-dom';
import MainLayout from '../../components/layout/MainLayout';
import Header from '../../components/layout/Header';
import StarWorshipModal from './components/StarWorshipModal';
import { familiesData, followersData, getFollowersByFamily, getFamilyById, getFollowerById } from '../../mocks/followers-data';
import { starWorshipData, calculateStar, type StarWorship } from '../../mocks/star-worship-data';

export default function StarWorshipPage() {
  const [worshipList, setWorshipList] = useState<StarWorship[]>(starWorshipData);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedWorship, setSelectedWorship] = useState<StarWorship | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterYear, setFilterYear] = useState<number>(new Date().getFullYear());
  const [filterFamily, setFilterFamily] = useState<string>('all');
  const [filterStatus, setFilterStatus] = useState<string>('all');
  const [activeTab, setActiveTab] = useState<'list' | 'family'>('list');

  const filteredWorshipList = useMemo(() => {
    return worshipList.filter(w => {
      const follower = getFollowerById(w.follower_id);
      const family = getFamilyById(w.family_id);
      
      const matchesSearch = follower?.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                           follower?.dharma_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                           family?.name.toLowerCase().includes(searchTerm.toLowerCase());
      const matchesYear = w.year === filterYear;
      const matchesFamily = filterFamily === 'all' || w.family_id === filterFamily;
      const matchesStatus = filterStatus === 'all' || w.status === filterStatus;
      
      return matchesSearch && matchesYear && matchesFamily && matchesStatus;
    });
  }, [worshipList, searchTerm, filterYear, filterFamily, filterStatus]);

  const stats = useMemo(() => {
    const yearData = worshipList.filter(w => w.year === filterYear);
    return {
      total: yearData.length,
      completed: yearData.filter(w => w.status === 'completed').length,
      pending: yearData.filter(w => w.status === 'pending').length,
      totalAmount: yearData.filter(w => w.status === 'completed').reduce((sum, w) => sum + w.amount, 0)
    };
  }, [worshipList, filterYear]);

  const handleAddWorship = () => {
    setSelectedWorship(null);
    setIsModalOpen(true);
  };

  const handleEditWorship = (worship: StarWorship) => {
    setSelectedWorship(worship);
    setIsModalOpen(true);
  };

  const handleDeleteWorship = (id: string) => {
    if (!confirm('Bạn có chắc chắn muốn xóa?')) return;
    setWorshipList(prev => prev.filter(w => w.id !== id));
  };

  const handleSaveWorship = (data: Omit<StarWorship, 'id' | 'created_at'>) => {
    if (selectedWorship) {
      setWorshipList(prev => prev.map(w => 
        w.id === selectedWorship.id 
          ? { ...w, ...data }
          : w
      ));
    } else {
      const newWorship: StarWorship = {
        ...data,
        id: `sw-${Date.now()}`,
        created_at: new Date().toISOString().split('T')[0]
      };
      setWorshipList(prev => [...prev, newWorship]);
    }
    setIsModalOpen(false);
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'completed': return 'bg-green-100 text-green-700';
      case 'pending': return 'bg-amber-100 text-amber-700';
      case 'cancelled': return 'bg-red-100 text-red-700';
      default: return 'bg-gray-100 text-gray-700';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'completed': return 'Đã cúng';
      case 'pending': return 'Chờ cúng';
      case 'cancelled': return 'Đã hủy';
      default: return status;
    }
  };

  const getStarTypeColor = (type: string) => {
    switch (type) {
      case 'good': return 'text-green-600 bg-green-50 border-green-200';
      case 'bad': return 'text-red-600 bg-red-50 border-red-200';
      default: return 'text-amber-600 bg-amber-50 border-amber-200';
    }
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
  };

  // Tính sao cho tất cả thành viên còn sống của mỗi gia đình
  const familyStarData = useMemo(() => {
    return familiesData.map(family => {
      const members = getFollowersByFamily(family.id).filter(m => m.status === 'alive');
      const membersWithStar = members.map(member => {
        const star = calculateStar(member.birth_year_solar, filterYear, member.gender);
        const worshipRecord = worshipList.find(
          w => w.follower_id === member.id && w.year === filterYear
        );
        return {
          ...member,
          star,
          worshipRecord
        };
      });
      return {
        family,
        members: membersWithStar
      };
    });
  }, [filterYear, worshipList]);

  return (
    <Suspense fallback={<div>Đang tải...</div>}>
      <MainLayout title="Cúng Sao Giải Hạn" subtitle="Quản lý lịch cúng sao và theo dõi lịch sử cúng sao cho các gia đình Phật tử">
        
        <div className="p-4 sm:p-6 lg:p-8">
          {/* Statistics Cards */}
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
            <div className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-amber-200">
              <div className="flex items-center justify-between">
                <div className="min-w-0">
                  <p className="text-xs sm:text-sm text-amber-600 font-medium truncate">Tổng đăng ký {filterYear}</p>
                  <p className="text-2xl sm:text-3xl font-bold text-amber-700 mt-1 sm:mt-2">{stats.total}</p>
                </div>
                <div className="w-10 h-10 sm:w-12 sm:h-12 bg-amber-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-star-line text-xl sm:text-2xl text-amber-700"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-green-200">
              <div className="flex items-center justify-between">
                <div className="min-w-0">
                  <p className="text-xs sm:text-sm text-green-600 font-medium">Đã cúng</p>
                  <p className="text-2xl sm:text-3xl font-bold text-green-700 mt-1 sm:mt-2">{stats.completed}</p>
                </div>
                <div className="w-10 h-10 sm:w-12 sm:h-12 bg-green-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-checkbox-circle-line text-xl sm:text-2xl text-green-700"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-orange-200">
              <div className="flex items-center justify-between">
                <div className="min-w-0">
                  <p className="text-xs sm:text-sm text-orange-600 font-medium">Chờ cúng</p>
                  <p className="text-2xl sm:text-3xl font-bold text-orange-700 mt-1 sm:mt-2">{stats.pending}</p>
                </div>
                <div className="w-10 h-10 sm:w-12 sm:h-12 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-time-line text-xl sm:text-2xl text-orange-700"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-teal-200 col-span-2 lg:col-span-1">
              <div className="flex items-center justify-between">
                <div className="min-w-0 flex-1">
                  <p className="text-xs sm:text-sm text-teal-600 font-medium">Tổng thu</p>
                  <p className="text-xl sm:text-2xl font-bold text-teal-700 mt-1 sm:mt-2 truncate">{formatCurrency(stats.totalAmount)}</p>
                </div>
                <div className="w-10 h-10 sm:w-12 sm:h-12 bg-teal-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-money-dollar-circle-line text-xl sm:text-2xl text-teal-700"></i>
                </div>
              </div>
            </div>
          </div>

          {/* Tabs */}
          <div className="bg-white rounded-lg shadow-sm border border-amber-100 mb-4 sm:mb-6">
            <div className="flex border-b border-amber-100 overflow-x-auto">
              <button
                onClick={() => setActiveTab('list')}
                className={`flex-1 min-w-[140px] px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium transition-colors whitespace-nowrap ${
                  activeTab === 'list'
                    ? 'text-amber-700 border-b-2 border-amber-600 bg-amber-50'
                    : 'text-amber-500 hover:text-amber-700 hover:bg-amber-50'
                }`}
              >
                <i className="ri-list-check w-4 h-4 sm:w-5 sm:h-5 inline-block mr-1 sm:mr-2 flex items-center justify-center"></i>
                Danh sách cúng sao
              </button>
              <button
                onClick={() => setActiveTab('family')}
                className={`flex-1 min-w-[140px] px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium transition-colors whitespace-nowrap ${
                  activeTab === 'family'
                    ? 'text-amber-700 border-b-2 border-amber-600 bg-amber-50'
                    : 'text-amber-500 hover:text-amber-700 hover:bg-amber-50'
                }`}
              >
                <i className="ri-home-heart-line w-4 h-4 sm:w-5 sm:h-5 inline-block mr-1 sm:mr-2 flex items-center justify-center"></i>
                Theo gia đình
              </button>
            </div>

            {/* Search and Filter Bar */}
            <div className="p-3 sm:p-4 lg:p-6">
              <div className="flex flex-col gap-3 sm:gap-4">
                <div className="w-full">
                  <div className="relative">
                    <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-amber-400 w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                    <input
                      type="text"
                      placeholder="Tìm kiếm theo tên Phật tử, pháp danh, gia đình..."
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                      className="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm"
                    />
                  </div>
                </div>
                
                <div className="grid grid-cols-2 sm:flex gap-2 sm:gap-3">
                  <select
                    value={filterYear}
                    onChange={(e) => setFilterYear(parseInt(e.target.value))}
                    className="px-3 sm:px-4 py-2 sm:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm cursor-pointer whitespace-nowrap"
                  >
                    <option value={2023}>Năm 2023</option>
                    <option value={2024}>Năm 2024</option>
                    <option value={2025}>Năm 2025</option>
                    <option value={2026}>Năm 2026</option>
                  </select>

                  <select
                    value={filterFamily}
                    onChange={(e) => setFilterFamily(e.target.value)}
                    className="px-3 sm:px-4 py-2 sm:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm cursor-pointer whitespace-nowrap"
                  >
                    <option value="all">Tất cả gia đình</option>
                    {familiesData.map(family => (
                      <option key={family.id} value={family.id}>{family.name}</option>
                    ))}
                  </select>

                  {activeTab === 'list' && (
                    <select
                      value={filterStatus}
                      onChange={(e) => setFilterStatus(e.target.value)}
                      className="px-3 sm:px-4 py-2 sm:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm cursor-pointer whitespace-nowrap col-span-2 sm:col-span-1"
                    >
                      <option value="all">Tất cả trạng thái</option>
                      <option value="pending">Chờ cúng</option>
                      <option value="completed">Đã cúng</option>
                      <option value="cancelled">Đã hủy</option>
                    </select>
                  )}

                  <button
                    onClick={handleAddWorship}
                    className="col-span-2 sm:col-span-1 px-4 sm:px-6 py-2 sm:py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap text-xs sm:text-sm cursor-pointer"
                  >
                    <i className="ri-add-line w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                    Đăng ký cúng sao
                  </button>
                </div>
              </div>
            </div>
          </div>

          {/* Content based on active tab */}
          {activeTab === 'list' ? (
            /* List View */
            filteredWorshipList.length === 0 ? (
              <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-8 sm:p-12 text-center">
                <i className="ri-star-line text-5xl sm:text-6xl text-amber-300 w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-3 sm:mb-4"></i>
                <p className="text-amber-600 text-base sm:text-lg">Chưa có đăng ký cúng sao nào</p>
                <button
                  onClick={handleAddWorship}
                  className="mt-4 px-4 sm:px-6 py-2 sm:py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap text-sm cursor-pointer"
                >
                  Đăng ký cúng sao đầu tiên
                </button>
              </div>
            ) : (
              <div className="bg-white rounded-lg shadow-sm border border-amber-100 overflow-hidden">
                <div className="overflow-x-auto">
                  <table className="w-full min-w-[800px]">
                    <thead className="bg-amber-50">
                      <tr>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Phật tử</th>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Gia đình</th>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Sao chiếu mệnh</th>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Ngày cúng</th>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Số tiền</th>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Trạng thái</th>
                        <th className="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-amber-700 uppercase tracking-wider">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-amber-100">
                      {filteredWorshipList.map((worship) => {
                        const follower = getFollowerById(worship.follower_id);
                        const family = getFamilyById(worship.family_id);
                        return (
                          <tr key={worship.id} className="hover:bg-amber-50 transition-colors">
                            <td className="px-3 sm:px-6 py-3 sm:py-4">
                              <Link to={`/followers/${worship.follower_id}`} className="hover:text-amber-600">
                                <p className="font-medium text-amber-900 text-xs sm:text-sm">{follower?.full_name}</p>
                                <p className="text-xs text-amber-600">{follower?.dharma_name}</p>
                              </Link>
                            </td>
                            <td className="px-3 sm:px-6 py-3 sm:py-4">
                              <Link to={`/families`} className="text-xs sm:text-sm text-amber-700 hover:text-amber-600">
                                {family?.name}
                              </Link>
                            </td>
                            <td className="px-3 sm:px-6 py-3 sm:py-4">
                              <span className={`inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full text-xs font-medium border ${getStarTypeColor(worship.star_type)}`}>
                                <i className="ri-star-fill w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center"></i>
                                {worship.star_name}
                              </span>
                            </td>
                            <td className="px-3 sm:px-6 py-3 sm:py-4">
                              <p className="text-xs sm:text-sm text-amber-900">{formatDate(worship.worship_date)}</p>
                              {worship.worship_date_lunar && (
                                <p className="text-xs text-amber-600">{worship.worship_date_lunar}</p>
                              )}
                            </td>
                            <td className="px-3 sm:px-6 py-3 sm:py-4">
                              <p className="text-xs sm:text-sm font-medium text-amber-900">{formatCurrency(worship.amount)}</p>
                            </td>
                            <td className="px-3 sm:px-6 py-3 sm:py-4">
                              <span className={`px-2 sm:px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap ${getStatusColor(worship.status)}`}>
                                {getStatusText(worship.status)}
                              </span>
                            </td>
                            <td className="px-3 sm:px-6 py-3 sm:py-4 text-right">
                              <div className="flex items-center justify-end gap-1 sm:gap-2">
                                <button
                                  onClick={() => handleEditWorship(worship)}
                                  className="p-1.5 sm:p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
                                  title="Chỉnh sửa"
                                >
                                  <i className="ri-edit-line w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                                </button>
                                <button
                                  onClick={() => handleDeleteWorship(worship.id)}
                                  className="p-1.5 sm:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                  title="Xóa"
                                >
                                  <i className="ri-delete-bin-line w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        );
                      })}
                    </tbody>
                  </table>
                </div>
              </div>
            )
          ) : (
            /* Family View */
            <div className="space-y-4 sm:space-y-6">
              {familyStarData
                .filter(fd => filterFamily === 'all' || fd.family.id === filterFamily)
                .map(({ family, members }) => (
                <div key={family.id} className="bg-white rounded-lg shadow-sm border border-amber-100 overflow-hidden">
                  <div className="bg-gradient-to-r from-amber-50 to-orange-50 px-4 sm:px-6 py-3 sm:py-4 border-b border-amber-100">
                    <div className="flex items-center justify-between gap-3">
                      <div className="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                        <div className="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                          <i className="ri-home-heart-line text-white text-base sm:text-lg"></i>
                        </div>
                        <div className="min-w-0">
                          <h3 className="font-bold text-amber-900 text-sm sm:text-base truncate">{family.name}</h3>
                          <p className="text-xs sm:text-sm text-amber-600">{members.length} thành viên còn sống</p>
                        </div>
                      </div>
                      <Link 
                        to="/families" 
                        className="text-xs sm:text-sm text-amber-600 hover:text-amber-700 flex items-center gap-1 whitespace-nowrap flex-shrink-0"
                      >
                        Xem chi tiết
                        <i className="ri-arrow-right-line w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center"></i>
                      </Link>
                    </div>
                  </div>
                  
                  <div className="p-3 sm:p-4 lg:p-6">
                    {members.length === 0 ? (
                      <p className="text-center text-amber-500 py-4 text-xs sm:text-sm">Không có thành viên còn sống</p>
                    ) : (
                      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
                        {members.map(member => (
                          <div 
                            key={member.id} 
                            className={`p-3 sm:p-4 rounded-lg border ${
                              member.worshipRecord?.status === 'completed' 
                                ? 'bg-green-50 border-green-200' 
                                : member.worshipRecord?.status === 'pending'
                                ? 'bg-amber-50 border-amber-200'
                                : 'bg-gray-50 border-gray-200'
                            }`}
                          >
                            <div className="flex items-start justify-between mb-2 sm:mb-3 gap-2">
                              <div className="min-w-0 flex-1">
                                <Link 
                                  to={`/followers/${member.id}`}
                                  className="font-medium text-amber-900 hover:text-amber-600 text-xs sm:text-sm block truncate"
                                >
                                  {member.full_name}
                                </Link>
                                <p className="text-xs text-amber-600 truncate">{member.dharma_name}</p>
                                <p className="text-xs text-amber-500 mt-1">
                                  Tuổi {member.birth_year_lunar} ({member.birth_year_solar})
                                </p>
                              </div>
                              <span className={`px-2 py-0.5 sm:py-1 rounded text-xs font-medium whitespace-nowrap flex-shrink-0 ${
                                member.gender === 'male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'
                              }`}>
                                {member.gender === 'male' ? 'Nam' : 'Nữ'}
                              </span>
                            </div>
                            
                            <div className={`p-2 sm:p-3 rounded-lg border ${getStarTypeColor(member.star.type)}`}>
                              <div className="flex items-center gap-1 sm:gap-2">
                                <i className="ri-star-fill w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center"></i>
                                <span className="font-bold text-xs sm:text-sm">{member.star.name}</span>
                              </div>
                              <p className="text-xs mt-1 opacity-80 line-clamp-2">{member.star.description}</p>
                            </div>

                            <div className="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-amber-100">
                              {member.worshipRecord ? (
                                <div className="flex items-center justify-between gap-2">
                                  <span className={`px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap ${getStatusColor(member.worshipRecord.status)}`}>
                                    {getStatusText(member.worshipRecord.status)}
                                  </span>
                                  <button
                                    onClick={() => handleEditWorship(member.worshipRecord!)}
                                    className="text-xs text-amber-600 hover:text-amber-700 flex items-center gap-1 cursor-pointer whitespace-nowrap"
                                  >
                                    <i className="ri-edit-line w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center"></i>
                                    Sửa
                                  </button>
                                </div>
                              ) : (
                                <button
                                  onClick={() => {
                                    setSelectedWorship(null);
                                    setIsModalOpen(true);
                                  }}
                                  className="w-full px-3 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-xs sm:text-sm font-medium whitespace-nowrap cursor-pointer"
                                >
                                  <i className="ri-add-line w-3 h-3 sm:w-4 sm:h-4 inline-block mr-1"></i>
                                  Đăng ký cúng sao
                                </button>
                              )}
                            </div>
                          </div>
                        ))}
                      </div>
                    )}
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {isModalOpen && (
          <StarWorshipModal
            worship={selectedWorship}
            onClose={() => setIsModalOpen(false)}
            onSave={handleSaveWorship}
          />
        )}
      </MainLayout>
    </Suspense>
  );
}
