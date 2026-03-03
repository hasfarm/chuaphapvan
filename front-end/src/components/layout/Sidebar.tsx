
import { Link, useLocation } from 'react-router-dom';

interface SidebarProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function Sidebar({ isOpen, onClose }: SidebarProps) {
  const location = useLocation();

  const menuItems = [
    { icon: 'ri-dashboard-line', label: 'Tổng quan', path: '/' },
    { icon: 'ri-user-heart-line', label: 'Phật tử', path: '/followers' },
    { icon: 'ri-home-heart-line', label: 'Gia đình', path: '/families' },
    { icon: 'ri-star-smile-line', label: 'Cúng sao', path: '/star-worship' },
    { icon: 'ri-calendar-check-line', label: 'Lịch cúng', path: '/worship-calendar' },
    { icon: 'ri-calendar-event-line', label: 'Sự kiện', path: '/events' },
  ];

  return (
    <aside className={`fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-amber-50 to-orange-50 border-r border-amber-200 overflow-y-auto z-40 transition-transform duration-300 ${
      isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    }`}>
      <div className="p-4 sm:p-6">
        <div className="flex items-center justify-between mb-6 sm:mb-8">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center">
              <i className="ri-ancient-pavilion-line text-xl sm:text-2xl text-white"></i>
            </div>
            <div>
              <h1 className="text-base sm:text-lg font-bold text-amber-900">Quản lý Chùa</h1>
              <p className="text-xs text-amber-700">Hệ thống quản lý</p>
            </div>
          </div>
          
          {/* Close button for mobile */}
          <button 
            onClick={onClose}
            className="lg:hidden p-2 text-amber-800 hover:bg-amber-100 rounded-lg transition-colors"
          >
            <i className="ri-close-line text-xl w-5 h-5 flex items-center justify-center"></i>
          </button>
        </div>

        <nav className="space-y-1">
          {menuItems.map((item) => {
            const isActive = location.pathname === item.path;
            return (
              <Link
                key={item.path}
                to={item.path}
                onClick={onClose}
                className={`flex items-center gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg transition-all whitespace-nowrap ${
                  isActive
                    ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-md'
                    : 'text-amber-800 hover:bg-amber-100'
                }`}
              >
                <i className={`${item.icon} text-lg sm:text-xl w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center`}></i>
                <span className="font-medium text-sm">{item.label}</span>
              </Link>
            );
          })}
        </nav>
      </div>

      <div className="absolute bottom-0 left-0 right-0 p-4 sm:p-6 bg-gradient-to-t from-amber-100 to-transparent">
        <div className="bg-white rounded-lg p-3 sm:p-4 shadow-sm border border-amber-200">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
              <i className="ri-user-line text-white text-base sm:text-lg"></i>
            </div>
            <div className="flex-1 min-w-0">
              <p className="text-xs sm:text-sm font-semibold text-amber-900 truncate">Thầy Chủ Trì</p>
              <p className="text-xs text-amber-600">Quản trị viên</p>
            </div>
          </div>
        </div>
      </div>
    </aside>
  );
}
