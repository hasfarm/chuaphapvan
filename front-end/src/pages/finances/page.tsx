import { Suspense, useState, useEffect } from 'react';
import MainLayout from '../../components/layout/MainLayout';
import Header from '../../components/layout/Header';
import FinanceModal from './components/FinanceModal';
import { supabase, type Finance, type BuddhistFollower } from '../../lib/supabase';

interface FinanceWithFollower extends Finance {
  follower?: BuddhistFollower;
}

export default function FinancesPage() {
  const [finances, setFinances] = useState<FinanceWithFollower[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedFinance, setSelectedFinance] = useState<Finance | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterType, setFilterType] = useState<string>('all');
  const [filterCategory, setFilterCategory] = useState<string>('all');
  const [dateRange, setDateRange] = useState({ start: '', end: '' });

  useEffect(() => {
    fetchFinances();
  }, []);

  const fetchFinances = async () => {
    try {
      setLoading(true);
      const { data: financesData, error: financesError } = await supabase
        .from('finances')
        .select('*')
        .order('transaction_date', { ascending: false });

      if (financesError) throw financesError;

      const { data: followersData, error: followersError } = await supabase
        .from('buddhist_followers')
        .select('id, full_name, dharma_name, avatar_url');

      if (followersError) throw followersError;

      const financesWithFollowers = (financesData || []).map(finance => ({
        ...finance,
        follower: followersData?.find(f => f.id === finance.follower_id)
      }));

      setFinances(financesWithFollowers);
    } catch (error) {
      console.error('Error fetching finances:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddFinance = () => {
    setSelectedFinance(null);
    setIsModalOpen(true);
  };

  const handleEditFinance = (finance: Finance) => {
    setSelectedFinance(finance);
    setIsModalOpen(true);
  };

  const handleDeleteFinance = async (id: string) => {
    if (!confirm('Bạn có chắc chắn muốn xóa giao dịch này?')) return;

    try {
      const { error } = await supabase
        .from('finances')
        .delete()
        .eq('id', id);

      if (error) throw error;
      fetchFinances();
    } catch (error) {
      console.error('Error deleting finance:', error);
      alert('Có lỗi xảy ra khi xóa giao dịch');
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(amount);
  };

  const getTransactionTypeText = (type: string) => {
    switch (type) {
      case 'income':
        return 'Thu';
      case 'expense':
        return 'Chi';
      default:
        return type;
    }
  };

  const getTransactionTypeColor = (type: string) => {
    return type === 'income' ? 'text-green-600' : 'text-red-600';
  };

  const getCategoryText = (category: string) => {
    const categories: Record<string, string> = {
      'donation': 'Cúng dường',
      'ceremony': 'Lễ hội',
      'maintenance': 'Bảo trì',
      'utilities': 'Tiện ích',
      'salary': 'Lương',
      'supplies': 'Vật phẩm',
      'other': 'Khác'
    };
    return categories[category] || category;
  };

  const getCategoryColor = (category: string) => {
    const colors: Record<string, string> = {
      'donation': 'bg-amber-100 text-amber-700',
      'ceremony': 'bg-purple-100 text-purple-700',
      'maintenance': 'bg-blue-100 text-blue-700',
      'utilities': 'bg-cyan-100 text-cyan-700',
      'salary': 'bg-green-100 text-green-700',
      'supplies': 'bg-orange-100 text-orange-700',
      'other': 'bg-gray-100 text-gray-700'
    };
    return colors[category] || 'bg-gray-100 text-gray-700';
  };

  const filteredFinances = finances.filter(finance => {
    const matchesSearch = finance.description?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         finance.follower?.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         finance.follower?.dharma_name?.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesType = filterType === 'all' || finance.transaction_type === filterType;
    const matchesCategory = filterCategory === 'all' || finance.category === filterCategory;
    
    let matchesDate = true;
    if (dateRange.start && dateRange.end) {
      const transactionDate = new Date(finance.transaction_date);
      const startDate = new Date(dateRange.start);
      const endDate = new Date(dateRange.end);
      matchesDate = transactionDate >= startDate && transactionDate <= endDate;
    }
    
    return matchesSearch && matchesType && matchesCategory && matchesDate;
  });

  const totalIncome = filteredFinances
    .filter(f => f.transaction_type === 'income')
    .reduce((sum, f) => sum + f.amount, 0);
  
  const totalExpense = filteredFinances
    .filter(f => f.transaction_type === 'expense')
    .reduce((sum, f) => sum + f.amount, 0);
  
  const balance = totalIncome - totalExpense;
  
  const donationCount = filteredFinances.filter(f => f.category === 'donation').length;

  return (
    <Suspense fallback={<div>Đang tải...</div>}>
      <MainLayout>
        <Header 
          title="Quản lý Tài chính & Cúng dường" 
          subtitle="Theo dõi thu chi và các khoản cúng dường của chùa"
        />
        
        <div className="p-4 md:p-8">
          {/* Search and Filter Bar */}
          <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-4 md:p-6 mb-4 md:mb-6">
            <div className="flex flex-col gap-3 md:gap-4">
              <div className="w-full">
                <div className="relative">
                  <i className="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-amber-400 w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
                  <input
                    type="text"
                    placeholder="Tìm kiếm giao dịch hoặc Phật tử..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-9 md:pl-10 pr-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm"
                  />
                </div>
              </div>
              
              <div className="grid grid-cols-2 md:flex gap-2 md:gap-3">
                <select
                  value={filterType}
                  onChange={(e) => setFilterType(e.target.value)}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value="all">Tất cả loại</option>
                  <option value="income">Thu</option>
                  <option value="expense">Chi</option>
                </select>

                <select
                  value={filterCategory}
                  onChange={(e) => setFilterCategory(e.target.value)}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm cursor-pointer whitespace-nowrap"
                >
                  <option value="all">Tất cả danh mục</option>
                  <option value="donation">Cúng dường</option>
                  <option value="ceremony">Lễ hội</option>
                  <option value="maintenance">Bảo trì</option>
                  <option value="utilities">Tiện ích</option>
                  <option value="salary">Lương</option>
                  <option value="supplies">Vật phẩm</option>
                  <option value="other">Khác</option>
                </select>

                <input
                  type="date"
                  value={dateRange.start}
                  onChange={(e) => setDateRange({ ...dateRange, start: e.target.value })}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm"
                  placeholder="Từ ngày"
                />

                <input
                  type="date"
                  value={dateRange.end}
                  onChange={(e) => setDateRange({ ...dateRange, end: e.target.value })}
                  className="px-3 md:px-4 py-2 md:py-2.5 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs md:text-sm"
                  placeholder="Đến ngày"
                />

                <button
                  onClick={handleAddFinance}
                  className="col-span-2 md:col-span-1 px-4 md:px-6 py-2 md:py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap text-xs md:text-sm"
                >
                  <i className="ri-add-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
                  Thêm giao dịch
                </button>
              </div>
            </div>
          </div>

          {/* Statistics Cards */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-4 md:mb-6">
            <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 md:p-6 border border-green-200">
              <div className="flex items-center justify-between">
                <div className="min-w-0">
                  <p className="text-xs md:text-sm text-green-600 font-medium">Tổng thu</p>
                  <p className="text-base md:text-2xl font-bold text-green-700 mt-1 md:mt-2 truncate">{formatCurrency(totalIncome)}</p>
                </div>
                <div className="w-10 h-10 md:w-12 md:h-12 bg-green-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-arrow-down-line text-xl md:text-2xl text-green-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 md:p-6 border border-red-200">
              <div className="flex items-center justify-between">
                <div className="min-w-0">
                  <p className="text-xs md:text-sm text-red-600 font-medium">Tổng chi</p>
                  <p className="text-base md:text-2xl font-bold text-red-700 mt-1 md:mt-2 truncate">{formatCurrency(totalExpense)}</p>
                </div>
                <div className="w-10 h-10 md:w-12 md:h-12 bg-red-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-arrow-up-line text-xl md:text-2xl text-red-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 md:p-6 border border-blue-200">
              <div className="flex items-center justify-between">
                <div className="min-w-0">
                  <p className="text-xs md:text-sm text-blue-600 font-medium">Số dư</p>
                  <p className={`text-base md:text-2xl font-bold mt-1 md:mt-2 truncate ${balance >= 0 ? 'text-blue-700' : 'text-red-700'}`}>
                    {formatCurrency(balance)}
                  </p>
                </div>
                <div className="w-10 h-10 md:w-12 md:h-12 bg-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-wallet-line text-xl md:text-2xl text-blue-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 md:p-6 border border-amber-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-xs md:text-sm text-amber-600 font-medium">Cúng dường</p>
                  <p className="text-2xl md:text-3xl font-bold text-amber-700 mt-1 md:mt-2">{donationCount}</p>
                </div>
                <div className="w-10 h-10 md:w-12 md:h-12 bg-amber-200 rounded-full flex items-center justify-center flex-shrink-0">
                  <i className="ri-hand-heart-line text-xl md:text-2xl text-amber-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
                </div>
              </div>
            </div>
          </div>

          {/* Finances List */}
          {loading ? (
            <div className="text-center py-12">
              <div className="inline-block animate-spin rounded-full h-10 w-10 md:h-12 md:w-12 border-4 border-amber-200 border-t-amber-600"></div>
              <p className="text-amber-600 mt-4 text-sm md:text-base">Đang tải dữ liệu...</p>
            </div>
          ) : filteredFinances.length === 0 ? (
            <div className="bg-white rounded-lg shadow-sm border border-amber-100 p-8 md:p-12 text-center">
              <i className="ri-wallet-line text-5xl md:text-6xl text-amber-300 w-12 h-12 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-4"></i>
              <p className="text-amber-600 text-base md:text-lg">Chưa có giao dịch nào</p>
              <button
                onClick={handleAddFinance}
                className="mt-4 px-4 md:px-6 py-2 md:py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap text-xs md:text-sm"
              >
                Thêm giao dịch đầu tiên
              </button>
            </div>
          ) : (
            <div className="bg-white rounded-lg shadow-sm border border-amber-100 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full min-w-[800px]">
                  <thead className="bg-amber-50 border-b border-amber-100">
                    <tr>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold text-amber-900">Ngày giao dịch</th>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold text-amber-900">Loại</th>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold text-amber-900">Danh mục</th>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold text-amber-900">Mô tả</th>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-left text-xs md:text-sm font-semibold text-amber-900">Phật tử</th>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-right text-xs md:text-sm font-semibold text-amber-900">Số tiền</th>
                      <th className="px-3 md:px-6 py-3 md:py-4 text-right text-xs md:text-sm font-semibold text-amber-900">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-amber-100">
                    {filteredFinances.map((finance) => (
                      <tr key={finance.id} className="hover:bg-amber-50/50 transition-colors">
                        <td className="px-3 md:px-6 py-3 md:py-4 text-xs md:text-sm text-amber-700">
                          {formatDate(finance.transaction_date)}
                        </td>
                        <td className="px-3 md:px-6 py-3 md:py-4">
                          <span className={`font-semibold text-xs md:text-sm ${getTransactionTypeColor(finance.transaction_type)}`}>
                            {getTransactionTypeText(finance.transaction_type)}
                          </span>
                        </td>
                        <td className="px-3 md:px-6 py-3 md:py-4">
                          <span className={`px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-medium whitespace-nowrap ${getCategoryColor(finance.category || '')}`}>
                            {getCategoryText(finance.category || '')}
                          </span>
                        </td>
                        <td className="px-3 md:px-6 py-3 md:py-4">
                          <div className="text-xs md:text-sm text-amber-900 max-w-xs line-clamp-2">
                            {finance.description || '—'}
                          </div>
                        </td>
                        <td className="px-3 md:px-6 py-3 md:py-4">
                          {finance.follower ? (
                            <div className="flex items-center gap-2">
                              <div className="w-7 h-7 md:w-8 md:h-8 bg-amber-100 rounded-full flex items-center justify-center overflow-hidden">
                                {finance.follower.avatar_url ? (
                                  <img 
                                    src={finance.follower.avatar_url} 
                                    alt={finance.follower.full_name}
                                    className="w-full h-full object-cover"
                                  />
                                ) : (
                                  <i className="ri-user-line text-amber-600 w-3.5 h-3.5 md:w-4 md:h-4 flex items-center justify-center"></i>
                                )}
                              </div>
                              <div className="text-xs md:text-sm text-amber-900">{finance.follower.full_name}</div>
                            </div>
                          ) : (
                            <span className="text-xs md:text-sm text-amber-500">—</span>
                          )}
                        </td>
                        <td className="px-3 md:px-6 py-3 md:py-4 text-right">
                          <span className={`font-bold text-xs md:text-sm ${getTransactionTypeColor(finance.transaction_type)}`}>
                            {finance.transaction_type === 'income' ? '+' : '-'}{formatCurrency(finance.amount)}
                          </span>
                        </td>
                        <td className="px-3 md:px-6 py-3 md:py-4">
                          <div className="flex items-center justify-end gap-1 md:gap-2">
                            <button
                              onClick={() => handleEditFinance(finance)}
                              className="p-1.5 md:p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                              title="Chỉnh sửa"
                            >
                              <i className="ri-edit-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
                            </button>
                            <button
                              onClick={() => handleDeleteFinance(finance.id)}
                              className="p-1.5 md:p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                              title="Xóa"
                            >
                              <i className="ri-delete-bin-line w-4 h-4 md:w-5 md:h-5 flex items-center justify-center"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}
        </div>

        {isModalOpen && (
          <FinanceModal
            finance={selectedFinance}
            onClose={() => setIsModalOpen(false)}
            onSuccess={() => {
              fetchFinances();
              setIsModalOpen(false);
            }}
          />
        )}
      </MainLayout>
    </Suspense>
  );
}
