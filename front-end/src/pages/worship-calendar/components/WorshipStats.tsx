interface WorshipStatsProps {
  stats: {
    totalGio: number;
    totalSao: number;
    completed: number;
    upcoming: number;
    overdue: number;
    totalAmount: number;
    saoBad: number;
    saoGood: number;
  };
  filterYear: number;
}

export default function WorshipStats({ stats, filterYear }: WorshipStatsProps) {
  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  return (
    <div className="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
      <div className="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 md:p-6 border border-gray-200">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs md:text-sm text-gray-600 font-medium">Cúng giỗ {filterYear}</p>
            <p className="text-xl md:text-2xl font-bold text-gray-700 mt-1 md:mt-2">{stats.totalGio}</p>
          </div>
          <div className="w-10 h-10 md:w-12 md:h-12 bg-gray-200 rounded-full flex items-center justify-center">
            <i className="ri-ghost-smile-line text-xl md:text-2xl text-gray-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
          </div>
        </div>
      </div>

      <div className="bg-gradient-to-br from-amber-50 to-orange-100 rounded-lg p-4 md:p-6 border border-amber-200">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs md:text-sm text-amber-600 font-medium">Cúng sao {filterYear}</p>
            <p className="text-xl md:text-2xl font-bold text-amber-700 mt-1 md:mt-2">{stats.totalSao}</p>
          </div>
          <div className="w-10 h-10 md:w-12 md:h-12 bg-amber-200 rounded-full flex items-center justify-center">
            <i className="ri-star-smile-line text-xl md:text-2xl text-amber-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
          </div>
        </div>
      </div>

      <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 md:p-6 border border-green-200">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs md:text-sm text-green-600 font-medium">Đã hoàn thành</p>
            <p className="text-xl md:text-2xl font-bold text-green-700 mt-1 md:mt-2">{stats.completed}</p>
          </div>
          <div className="w-10 h-10 md:w-12 md:h-12 bg-green-200 rounded-full flex items-center justify-center">
            <i className="ri-checkbox-circle-line text-xl md:text-2xl text-green-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
          </div>
        </div>
      </div>

      <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 md:p-6 border border-blue-200">
        <div className="flex items-center justify-between">
          <div className="min-w-0">
            <p className="text-xs md:text-sm text-blue-600 font-medium">Tổng thu</p>
            <p className="text-sm md:text-xl font-bold text-blue-700 mt-1 md:mt-2 truncate">{formatCurrency(stats.totalAmount)}</p>
          </div>
          <div className="w-10 h-10 md:w-12 md:h-12 bg-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
            <i className="ri-wallet-line text-xl md:text-2xl text-blue-700 w-5 h-5 md:w-6 md:h-6 flex items-center justify-center"></i>
          </div>
        </div>
      </div>
    </div>
  );
}
