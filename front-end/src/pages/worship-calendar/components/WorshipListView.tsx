import { Link } from 'react-router-dom';
import { type WorshipEvent } from '../../../mocks/worship-calendar-data';
import { getFollowerById, getFamilyById } from '../../../mocks/followers-data';

interface WorshipListViewProps {
  events: WorshipEvent[];
  onUpdateStatus: (id: string, status: 'upcoming' | 'completed' | 'overdue') => void;
}

export default function WorshipListView({ events, onUpdateStatus }: WorshipListViewProps) {
  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'completed': return 'bg-green-100 text-green-700';
      case 'upcoming': return 'bg-amber-100 text-amber-700';
      case 'overdue': return 'bg-red-100 text-red-700';
      default: return 'bg-gray-100 text-gray-700';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'completed': return 'Đã cúng';
      case 'upcoming': return 'Sắp tới';
      case 'overdue': return 'Quá hạn';
      default: return status;
    }
  };

  const getStarTypeColor = (type?: string) => {
    switch (type) {
      case 'good': return 'text-green-700 bg-green-50 border-green-200';
      case 'bad': return 'text-red-700 bg-red-50 border-red-200';
      case 'neutral': return 'text-amber-700 bg-amber-50 border-amber-200';
      default: return '';
    }
  };

  if (events.length === 0) {
    return (
      <div className="bg-white rounded-lg border border-amber-100 shadow-sm p-12 text-center">
        <i className="ri-calendar-line text-6xl text-amber-200"></i>
        <p className="text-amber-600 text-lg mt-4">Không tìm thấy sự kiện nào</p>
        <p className="text-sm text-amber-500 mt-1">Thử thay đổi bộ lọc để xem thêm</p>
      </div>
    );
  }

  return (
    <div className="bg-white rounded-lg border border-amber-100 shadow-sm overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-amber-50">
            <tr>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Loại</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Phật tử</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Gia đình</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Sao / Giỗ</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Ngày cúng</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Âm lịch</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Số tiền</th>
              <th className="px-5 py-3.5 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Trạng thái</th>
              <th className="px-5 py-3.5 text-right text-xs font-semibold text-amber-700 uppercase tracking-wider">Thao tác</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-amber-50">
            {events.map(e => {
              const follower = getFollowerById(e.follower_id);
              const family = getFamilyById(e.family_id);
              return (
                <tr key={e.id} className="hover:bg-amber-50/50 transition-colors">
                  <td className="px-5 py-4">
                    <span className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ${
                      e.type === 'gio' ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-700'
                    }`}>
                      <i className={`${e.type === 'gio' ? 'ri-ghost-smile-line' : 'ri-star-fill'} w-3 h-3 flex items-center justify-center`}></i>
                      {e.type === 'gio' ? 'Giỗ' : 'Sao'}
                    </span>
                  </td>
                  <td className="px-5 py-4">
                    <Link to={`/followers/${e.follower_id}`} className="hover:text-amber-600 cursor-pointer">
                      <p className="font-medium text-amber-900 text-sm">{follower?.full_name}</p>
                      <p className="text-xs text-amber-600">{follower?.dharma_name}</p>
                    </Link>
                  </td>
                  <td className="px-5 py-4">
                    <span className="text-sm text-amber-700">{family?.name || '-'}</span>
                  </td>
                  <td className="px-5 py-4">
                    {e.type === 'sao' && e.star_name ? (
                      <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border ${getStarTypeColor(e.star_type)}`}>
                        <i className="ri-star-fill w-3 h-3 flex items-center justify-center"></i>
                        {e.star_name}
                      </span>
                    ) : (
                      <span className="text-sm text-gray-600 flex items-center gap-1">
                        <i className="ri-fire-line w-4 h-4 flex items-center justify-center text-gray-400"></i>
                        Ngày giỗ
                      </span>
                    )}
                  </td>
                  <td className="px-5 py-4">
                    <p className="text-sm text-amber-900">{new Date(e.date_solar).toLocaleDateString('vi-VN')}</p>
                  </td>
                  <td className="px-5 py-4">
                    <p className="text-sm text-amber-700">{e.date_lunar}</p>
                  </td>
                  <td className="px-5 py-4">
                    <p className="text-sm font-medium text-amber-900">{formatCurrency(e.amount)}</p>
                  </td>
                  <td className="px-5 py-4">
                    <span className={`px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap ${getStatusColor(e.status)}`}>
                      {getStatusText(e.status)}
                    </span>
                  </td>
                  <td className="px-5 py-4 text-right">
                    <div className="flex items-center justify-end gap-1">
                      {e.status === 'upcoming' && (
                        <button
                          onClick={() => onUpdateStatus(e.id, 'completed')}
                          className="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors cursor-pointer"
                          title="Đánh dấu đã cúng"
                        >
                          <i className="ri-checkbox-circle-line w-5 h-5 flex items-center justify-center"></i>
                        </button>
                      )}
                      {e.status === 'completed' && (
                        <button
                          onClick={() => onUpdateStatus(e.id, 'upcoming')}
                          className="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
                          title="Đánh dấu chưa cúng"
                        >
                          <i className="ri-arrow-go-back-line w-5 h-5 flex items-center justify-center"></i>
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {/* Tổng kết */}
      <div className="px-5 py-3 bg-amber-50 border-t border-amber-100 flex items-center justify-between">
        <span className="text-sm text-amber-700">
          Tổng: <strong>{events.length}</strong> sự kiện
        </span>
        <span className="text-sm text-amber-700">
          Tổng tiền đã cúng: <strong>{formatCurrency(events.filter(e => e.status === 'completed').reduce((s, e) => s + e.amount, 0))}</strong>
        </span>
      </div>
    </div>
  );
}
