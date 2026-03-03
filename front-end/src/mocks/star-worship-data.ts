
// Dữ liệu demo cúng sao giải hạn
export interface StarWorship {
  id: string;
  family_id: string;
  follower_id: string; // Người được cúng sao
  year: number; // Năm cúng
  star_name: string; // Tên sao chiếu mệnh
  star_type: 'good' | 'bad' | 'neutral'; // Loại sao: tốt, xấu, trung bình
  worship_date: string; // Ngày cúng
  worship_date_lunar: string; // Ngày âm lịch
  amount: number; // Số tiền cúng
  status: 'pending' | 'completed' | 'cancelled'; // Trạng thái
  notes: string;
  created_at: string;
}

// 9 sao chiếu mệnh theo chu kỳ 9 năm
export const starsList = [
  { name: 'La Hầu', type: 'bad', description: 'Sao xấu, cần cúng giải hạn' },
  { name: 'Thổ Tú', type: 'neutral', description: 'Sao trung bình' },
  { name: 'Thủy Diệu', type: 'good', description: 'Sao tốt, may mắn' },
  { name: 'Thái Bạch', type: 'bad', description: 'Sao xấu, cần cúng giải hạn' },
  { name: 'Thái Dương', type: 'good', description: 'Sao tốt, thịnh vượng' },
  { name: 'Vân Hớn', type: 'neutral', description: 'Sao trung bình' },
  { name: 'Kế Đô', type: 'bad', description: 'Sao xấu, cần cúng giải hạn' },
  { name: 'Thái Âm', type: 'good', description: 'Sao tốt, bình an' },
  { name: 'Mộc Đức', type: 'good', description: 'Sao tốt, phát triển' }
];

// Tính sao chiếu mệnh theo tuổi và năm
export const calculateStar = (birthYear: number, currentYear: number, gender: 'male' | 'female'): typeof starsList[0] => {
  // Công thức tính sao theo tuổi âm lịch
  const age = currentYear - birthYear + 1;
  
  // Nam và nữ có cách tính khác nhau
  let starIndex: number;
  if (gender === 'male') {
    // Nam: bắt đầu từ Thái Dương (index 4), đi ngược
    starIndex = (4 - ((age - 1) % 9) + 9) % 9;
  } else {
    // Nữ: bắt đầu từ Thái Âm (index 7), đi xuôi
    starIndex = (7 + (age - 1)) % 9;
  }
  
  return starsList[starIndex];
};

// Dữ liệu demo lịch sử cúng sao
export const starWorshipData: StarWorship[] = [
  {
    id: 'sw-001',
    family_id: 'fam-001',
    follower_id: 'pt-001',
    year: 2024,
    star_name: 'La Hầu',
    star_type: 'bad',
    worship_date: '2024-02-10',
    worship_date_lunar: '01/01/Giáp Thìn',
    amount: 500000,
    status: 'completed',
    notes: 'Đã cúng đầu năm',
    created_at: '2024-02-10'
  },
  {
    id: 'sw-002',
    family_id: 'fam-001',
    follower_id: 'pt-002',
    year: 2024,
    star_name: 'Thái Bạch',
    star_type: 'bad',
    worship_date: '2024-02-10',
    worship_date_lunar: '01/01/Giáp Thìn',
    amount: 500000,
    status: 'completed',
    notes: 'Đã cúng đầu năm',
    created_at: '2024-02-10'
  },
  {
    id: 'sw-003',
    family_id: 'fam-002',
    follower_id: 'pt-003',
    year: 2024,
    star_name: 'Kế Đô',
    star_type: 'bad',
    worship_date: '2024-02-15',
    worship_date_lunar: '06/01/Giáp Thìn',
    amount: 500000,
    status: 'completed',
    notes: 'Cúng rằm tháng giêng',
    created_at: '2024-02-15'
  },
  {
    id: 'sw-004',
    family_id: 'fam-003',
    follower_id: 'pt-009',
    year: 2024,
    star_name: 'Thủy Diệu',
    star_type: 'good',
    worship_date: '2024-02-10',
    worship_date_lunar: '01/01/Giáp Thìn',
    amount: 300000,
    status: 'completed',
    notes: 'Sao tốt, cúng cầu an',
    created_at: '2024-02-10'
  },
  {
    id: 'sw-005',
    family_id: 'fam-004',
    follower_id: 'pt-005',
    year: 2024,
    star_name: 'La Hầu',
    star_type: 'bad',
    worship_date: '2024-03-01',
    worship_date_lunar: '21/01/Giáp Thìn',
    amount: 500000,
    status: 'pending',
    notes: 'Đã đăng ký, chờ cúng',
    created_at: '2024-02-25'
  },
  {
    id: 'sw-006',
    family_id: 'fam-005',
    follower_id: 'pt-006',
    year: 2024,
    star_name: 'Thái Dương',
    star_type: 'good',
    worship_date: '2024-02-10',
    worship_date_lunar: '01/01/Giáp Thìn',
    amount: 300000,
    status: 'completed',
    notes: 'Sao tốt',
    created_at: '2024-02-10'
  },
  {
    id: 'sw-007',
    family_id: 'fam-001',
    follower_id: 'pt-001',
    year: 2023,
    star_name: 'Kế Đô',
    star_type: 'bad',
    worship_date: '2023-01-22',
    worship_date_lunar: '01/01/Quý Mão',
    amount: 450000,
    status: 'completed',
    notes: 'Đã cúng năm 2023',
    created_at: '2023-01-22'
  },
  {
    id: 'sw-008',
    family_id: 'fam-002',
    follower_id: 'pt-008',
    year: 2024,
    star_name: 'Mộc Đức',
    star_type: 'good',
    worship_date: '2024-02-15',
    worship_date_lunar: '06/01/Giáp Thìn',
    amount: 300000,
    status: 'completed',
    notes: 'Sao tốt, cầu bình an',
    created_at: '2024-02-15'
  }
];

// Lấy lịch sử cúng sao theo gia đình
export const getStarWorshipByFamily = (familyId: string): StarWorship[] => {
  return starWorshipData.filter(sw => sw.family_id === familyId);
};

// Lấy lịch sử cúng sao theo Phật tử
export const getStarWorshipByFollower = (followerId: string): StarWorship[] => {
  return starWorshipData.filter(sw => sw.follower_id === followerId);
};

// Lấy lịch sử cúng sao theo năm
export const getStarWorshipByYear = (year: number): StarWorship[] => {
  return starWorshipData.filter(sw => sw.year === year);
};
