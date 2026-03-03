
// Dữ liệu demo lịch cúng giỗ & cúng sao tổng hợp

export interface WorshipEvent {
  id: string;
  type: 'gio' | 'sao'; // cúng giỗ hoặc cúng sao
  follower_id: string;
  family_id: string;
  title: string;
  date_solar: string; // ngày dương lịch
  date_lunar: string; // ngày âm lịch
  star_name?: string; // tên sao (nếu là cúng sao)
  star_type?: 'good' | 'bad' | 'neutral';
  year: number;
  amount: number;
  status: 'upcoming' | 'completed' | 'overdue';
  notes: string;
}

// Lịch cúng giỗ cho Phật tử đã mất
export const memorialEvents: WorshipEvent[] = [
  {
    id: 'gio-001',
    type: 'gio',
    follower_id: 'pt-004',
    family_id: 'fam-003',
    title: 'Giỗ bà Lê Thị Cúc',
    date_solar: '2025-02-10',
    date_lunar: '13/01/Ất Tỵ',
    year: 2025,
    amount: 1000000,
    status: 'upcoming',
    notes: 'Ngày giỗ hàng năm - Mất ngày 21/01 Âm lịch'
  },
  {
    id: 'gio-002',
    type: 'gio',
    follower_id: 'pt-007',
    family_id: 'fam-001',
    title: 'Giỗ ông Nguyễn Văn Đức',
    date_solar: '2025-08-20',
    date_lunar: '26/06/Ất Tỵ',
    year: 2025,
    amount: 1200000,
    status: 'upcoming',
    notes: 'Ngày giỗ hàng năm - Mất ngày 26/06 Âm lịch'
  },
  {
    id: 'gio-003',
    type: 'gio',
    follower_id: 'pt-004',
    family_id: 'fam-003',
    title: 'Giỗ bà Lê Thị Cúc',
    date_solar: '2024-02-10',
    date_lunar: '21/01/Giáp Thìn',
    year: 2024,
    amount: 800000,
    status: 'completed',
    notes: 'Đã cúng giỗ năm 2024'
  },
  {
    id: 'gio-004',
    type: 'gio',
    follower_id: 'pt-007',
    family_id: 'fam-001',
    title: 'Giỗ ông Nguyễn Văn Đức',
    date_solar: '2024-08-15',
    date_lunar: '26/06/Giáp Thìn',
    year: 2024,
    amount: 1000000,
    status: 'completed',
    notes: 'Đã cúng giỗ năm 2024'
  }
];

// Lịch cúng sao cho tất cả Phật tử còn sống
export const starWorshipEvents: WorshipEvent[] = [
  {
    id: 'sao-001',
    type: 'sao',
    follower_id: 'pt-001',
    family_id: 'fam-001',
    title: 'Cúng sao Nguyễn Văn An',
    date_solar: '2025-02-12',
    date_lunar: '15/01/Ất Tỵ',
    star_name: 'Thái Bạch',
    star_type: 'bad',
    year: 2025,
    amount: 500000,
    status: 'upcoming',
    notes: 'Sao xấu, cần giải hạn đầu năm'
  },
  {
    id: 'sao-002',
    type: 'sao',
    follower_id: 'pt-002',
    family_id: 'fam-001',
    title: 'Cúng sao Nguyễn Thị Bích',
    date_solar: '2025-02-12',
    date_lunar: '15/01/Ất Tỵ',
    star_name: 'Thủy Diệu',
    star_type: 'good',
    year: 2025,
    amount: 300000,
    status: 'completed',
    notes: 'Sao tốt, cầu bình an'
  },
  {
    id: 'sao-003',
    type: 'sao',
    follower_id: 'pt-003',
    family_id: 'fam-002',
    title: 'Cúng sao Trần Văn Bình',
    date_solar: '2025-02-12',
    date_lunar: '15/01/Ất Tỵ',
    star_name: 'La Hầu',
    star_type: 'bad',
    year: 2025,
    amount: 500000,
    status: 'upcoming',
    notes: 'Sao xấu, cần giải hạn'
  },
  {
    id: 'sao-004',
    type: 'sao',
    follower_id: 'pt-005',
    family_id: 'fam-004',
    title: 'Cúng sao Phạm Ngọc Hải',
    date_solar: '2025-02-12',
    date_lunar: '15/01/Ất Tỵ',
    star_name: 'Kế Đô',
    star_type: 'bad',
    year: 2025,
    amount: 500000,
    status: 'upcoming',
    notes: 'Sao xấu, cần giải hạn'
  },
  {
    id: 'sao-005',
    type: 'sao',
    follower_id: 'pt-006',
    family_id: 'fam-005',
    title: 'Cúng sao Võ Minh Tuấn',
    date_solar: '2025-03-01',
    date_lunar: '02/02/Ất Tỵ',
    star_name: 'Mộc Đức',
    star_type: 'good',
    year: 2025,
    amount: 300000,
    status: 'upcoming',
    notes: 'Sao tốt, cầu phát triển'
  },
  {
    id: 'sao-006',
    type: 'sao',
    follower_id: 'pt-008',
    family_id: 'fam-002',
    title: 'Cúng sao Trần Thị Mai',
    date_solar: '2025-02-12',
    date_lunar: '15/01/Ất Tỵ',
    star_name: 'Thái Âm',
    star_type: 'good',
    year: 2025,
    amount: 300000,
    status: 'completed',
    notes: 'Sao tốt, cầu bình an'
  },
  {
    id: 'sao-007',
    type: 'sao',
    follower_id: 'pt-009',
    family_id: 'fam-003',
    title: 'Cúng sao Lê Hoàng Minh',
    date_solar: '2025-02-12',
    date_lunar: '15/01/Ất Tỵ',
    star_name: 'Vân Hớn',
    star_type: 'neutral',
    year: 2025,
    amount: 400000,
    status: 'upcoming',
    notes: 'Sao trung bình'
  },
  {
    id: 'sao-008',
    type: 'sao',
    follower_id: 'pt-010',
    family_id: 'fam-004',
    title: 'Cúng sao Phạm Thị Hồng',
    date_solar: '2025-03-15',
    date_lunar: '16/02/Ất Tỵ',
    star_name: 'Thổ Tú',
    star_type: 'neutral',
    year: 2025,
    amount: 400000,
    status: 'upcoming',
    notes: 'Sao trung bình'
  },
  {
    id: 'sao-009',
    type: 'sao',
    follower_id: 'pt-001',
    family_id: 'fam-001',
    title: 'Cúng sao Nguyễn Văn An',
    date_solar: '2024-02-10',
    date_lunar: '01/01/Giáp Thìn',
    star_name: 'La Hầu',
    star_type: 'bad',
    year: 2024,
    amount: 500000,
    status: 'completed',
    notes: 'Đã cúng đầu năm 2024'
  },
  {
    id: 'sao-010',
    type: 'sao',
    follower_id: 'pt-003',
    family_id: 'fam-002',
    title: 'Cúng sao Trần Văn Bình',
    date_solar: '2024-02-15',
    date_lunar: '06/01/Giáp Thìn',
    star_name: 'Kế Đô',
    star_type: 'bad',
    year: 2024,
    amount: 500000,
    status: 'completed',
    notes: 'Đã cúng rằm tháng giêng 2024'
  }
];

// Tổng hợp tất cả sự kiện
export const allWorshipEvents: WorshipEvent[] = [...memorialEvents, ...starWorshipEvents];
