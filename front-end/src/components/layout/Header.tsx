
interface HeaderProps {
  title: string;
  subtitle?: string;
  onMenuClick: () => void;
}

export default function Header({ title, subtitle, onMenuClick }: HeaderProps) {
  return (
    <header className="bg-white border-b border-amber-100 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 sticky top-0 z-20">
      <div className="flex items-center justify-between gap-4">
        <div className="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
          {/* Hamburger menu for mobile */}
          <button 
            onClick={onMenuClick}
            className="lg:hidden p-2 text-amber-700 hover:bg-amber-50 rounded-lg transition-colors flex-shrink-0"
          >
            <i className="ri-menu-line text-xl w-6 h-6 flex items-center justify-center"></i>
          </button>
          
          <div className="min-w-0">
            <h1 className="text-lg sm:text-xl lg:text-2xl font-bold text-amber-900 truncate">{title}</h1>
            {subtitle && <p className="text-xs sm:text-sm text-amber-600 mt-0.5 sm:mt-1 truncate">{subtitle}</p>}
          </div>
        </div>
        
        <div className="flex items-center gap-2 sm:gap-3 lg:gap-4 flex-shrink-0">
          <button className="relative p-1.5 sm:p-2 text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
            <i className="ri-notification-3-line text-lg sm:text-xl w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center"></i>
            <span className="absolute top-0.5 right-0.5 sm:top-1 sm:right-1 w-2 h-2 bg-orange-500 rounded-full"></span>
          </button>
          <button className="p-1.5 sm:p-2 text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
            <i className="ri-settings-3-line text-lg sm:text-xl w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center"></i>
          </button>
        </div>
      </div>
    </header>
  );
}
