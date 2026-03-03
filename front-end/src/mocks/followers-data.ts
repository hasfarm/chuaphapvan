// Dữ liệu demo Phật tử
export interface Family {
  id: string;
  name: string;
  address: string;
  head_of_family_id: string; // ID của Phật tử làm chủ hộ
}

export interface Follower {
  id: string;
  full_name: string;
  dharma_name: string;
  phone: string;
  email: string;
  address: string;
  birth_year_solar: number; // Năm sinh dương lịch
  birth_year_lunar: string; // Năm sinh âm lịch (Can Chi)
  birth_date: string;
  birth_date_lunar: string;
  gender: 'male' | 'female';
  status: 'alive' | 'deceased'; // Tình trạng: tại thế / hương linh
  death_date?: string; // Ngày mất dương lịch
  death_date_lunar?: string; // Ngày mất âm lịch
  family_id: string; // Thuộc gia đình nào
  avatar_url: string;
  zodiac_info: string;
  notes: string;
  created_at: string;
}

// Hàm quy đổi năm dương lịch sang năm âm lịch (Can Chi)
export const convertToLunarYear = (solarYear: number): string => {
  const can = ['Canh', 'Tân', 'Nhâm', 'Quý', 'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ'];
  const chi = ['Thân', 'Dậu', 'Tuất', 'Hợi', 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi'];
  
  const canIndex = solarYear % 10;
  const chiIndex = solarYear % 12;
  
  return `${can[canIndex]} ${chi[chiIndex]}`;
};

// Dữ liệu demo gia đình
export const familiesData: Family[] = [
  {
    id: 'fam-001',
    name: 'Gia đình Nguyễn Văn',
    address: '123 Đường Lê Lợi, Quận 1, TP.HCM',
    head_of_family_id: 'pt-001' // Nguyễn Văn An
  },
  {
    id: 'fam-002',
    name: 'Gia đình Trần Thị',
    address: '456 Đường Nguyễn Huệ, Quận 3, TP.HCM',
    head_of_family_id: 'pt-003' // Trần Văn Bình
  },
  {
    id: 'fam-003',
    name: 'Gia đình Lê Hoàng',
    address: '789 Đường Pasteur, Quận 5, TP.HCM',
    head_of_family_id: 'pt-009' // Lê Hoàng Minh
  },
  {
    id: 'fam-004',
    name: 'Gia đình Phạm Ngọc',
    address: '321 Đường Hai Bà Trưng, Quận Bình Thạnh, TP.HCM',
    head_of_family_id: 'pt-005' // Phạm Ngọc Hải
  },
  {
    id: 'fam-005',
    name: 'Gia đình Võ Minh',
    address: '654 Đường Cách Mạng Tháng 8, Quận 10, TP.HCM',
    head_of_family_id: 'pt-006' // Võ Minh Tuấn
  }
];

// Dữ liệu demo Phật tử
export const followersData: Follower[] = [
  {
    id: 'pt-001',
    full_name: 'Nguyễn Văn An',
    dharma_name: 'Thiện Tâm',
    phone: '0901234567',
    email: 'nguyenvanan@email.com',
    address: '123 Đường Lê Lợi, Quận 1, TP.HCM',
    birth_year_solar: 1965,
    birth_year_lunar: convertToLunarYear(1965),
    birth_date: '1965-03-15',
    birth_date_lunar: '15/02/Ất Tỵ',
    gender: 'male',
    status: 'alive',
    family_id: 'fam-001',
    avatar_url: '',
    zodiac_info: 'Tuổi Ất Tỵ - Mệnh Hỏa',
    notes: 'Phật tử thuần thành, tham gia nhiều hoạt động chùa',
    created_at: '2024-01-15'
  },
  {
    id: 'pt-002',
    full_name: 'Nguyễn Thị Bích',
    dharma_name: 'Diệu Hạnh',
    phone: '0912345678',
    email: 'nguyenthibich@email.com',
    address: '123 Đường Lê Lợi, Quận 1, TP.HCM',
    birth_year_solar: 1968,
    birth_year_lunar: convertToLunarYear(1968),
    birth_date: '1968-08-20',
    birth_date_lunar: '25/07/Mậu Thân',
    gender: 'female',
    status: 'alive',
    family_id: 'fam-001',
    avatar_url: '',
    zodiac_info: 'Tuổi Mậu Thân - Mệnh Thổ',
    notes: 'Vợ của Nguyễn Văn An',
    created_at: '2024-01-15'
  },
  {
    id: 'pt-003',
    full_name: 'Trần Văn Bình',
    dharma_name: 'Minh Đức',
    phone: '0923456789',
    email: 'tranvanbinh@email.com',
    address: '456 Đường Nguyễn Huệ, Quận 3, TP.HCM',
    birth_year_solar: 1958,
    birth_year_lunar: convertToLunarYear(1958),
    birth_date: '1958-12-10',
    birth_date_lunar: '01/11/Mậu Tuất',
    gender: 'male',
    status: 'alive',
    family_id: 'fam-002',
    avatar_url: '',
    zodiac_info: 'Tuổi Mậu Tuất - Mệnh Mộc',
    notes: 'Trưởng ban hộ tự',
    created_at: '2024-02-01'
  },
  {
    id: 'pt-004',
    full_name: 'Lê Thị Cúc',
    dharma_name: 'Thanh Tịnh',
    phone: '0934567890',
    email: 'lethicuc@email.com',
    address: '789 Đường Pasteur, Quận 5, TP.HCM',
    birth_year_solar: 1945,
    birth_year_lunar: convertToLunarYear(1945),
    birth_date: '1945-05-05',
    birth_date_lunar: '24/03/Ất Dậu',
    gender: 'female',
    status: 'deceased',
    death_date: '2023-02-10',
    death_date_lunar: '21/01/Quý Mão',
    family_id: 'fam-003',
    avatar_url: '',
    zodiac_info: 'Tuổi Ất Dậu - Mệnh Thủy',
    notes: 'Đã mất ngày 10/02/2023',
    created_at: '2024-01-10'
  },
  {
    id: 'pt-005',
    full_name: 'Phạm Ngọc Hải',
    dharma_name: 'Quảng Trí',
    phone: '0945678901',
    email: 'phamngochai@email.com',
    address: '321 Đường Hai Bà Trưng, Quận Bình Thạnh, TP.HCM',
    birth_year_solar: 1972,
    birth_year_lunar: convertToLunarYear(1972),
    birth_date: '1972-09-18',
    birth_date_lunar: '12/08/Nhâm Tý',
    gender: 'male',
    status: 'alive',
    family_id: 'fam-004',
    avatar_url: '',
    zodiac_info: 'Tuổi Nhâm Tý - Mệnh Mộc',
    notes: 'Thường xuyên cúng dường',
    created_at: '2024-02-20'
  },
  {
    id: 'pt-006',
    full_name: 'Võ Minh Tuấn',
    dharma_name: 'Huệ Đăng',
    phone: '0956789012',
    email: 'vominhtuan@email.com',
    address: '654 Đường Cách Mạng Tháng 8, Quận 10, TP.HCM',
    birth_year_solar: 1980,
    birth_year_lunar: convertToLunarYear(1980),
    birth_date: '1980-01-25',
    birth_date_lunar: '09/12/Kỷ Mùi',
    gender: 'male',
    status: 'alive',
    family_id: 'fam-005',
    avatar_url: '',
    zodiac_info: 'Tuổi Canh Thân - Mệnh Mộc',
    notes: 'Phật tử trẻ, nhiệt tình',
    created_at: '2024-03-01'
  },
  {
    id: 'pt-007',
    full_name: 'Nguyễn Văn Đức',
    dharma_name: 'Thiện Phúc',
    phone: '0967890123',
    email: 'nguyenvanduc@email.com',
    address: '123 Đường Lê Lợi, Quận 1, TP.HCM',
    birth_year_solar: 1940,
    birth_year_lunar: convertToLunarYear(1940),
    birth_date: '1940-06-12',
    birth_date_lunar: '08/05/Canh Thìn',
    gender: 'male',
    status: 'deceased',
    death_date: '2020-08-15',
    death_date_lunar: '26/06/Canh Tý',
    family_id: 'fam-001',
    avatar_url: '',
    zodiac_info: 'Tuổi Canh Thìn - Mệnh Kim',
    notes: 'Cha của Nguyễn Văn An, đã mất năm 2020',
    created_at: '2024-01-15'
  },
  {
    id: 'pt-008',
    full_name: 'Trần Thị Mai',
    dharma_name: 'Diệu Liên',
    phone: '0978901234',
    email: 'tranthimai@email.com',
    address: '456 Đường Nguyễn Huệ, Quận 3, TP.HCM',
    birth_year_solar: 1962,
    birth_year_lunar: convertToLunarYear(1962),
    birth_date: '1962-04-08',
    birth_date_lunar: '05/03/Nhâm Dần',
    gender: 'female',
    status: 'alive',
    family_id: 'fam-002',
    avatar_url: '',
    zodiac_info: 'Tuổi Nhâm Dần - Mệnh Kim',
    notes: 'Vợ của Trần Văn Bình',
    created_at: '2024-02-01'
  },
  {
    id: 'pt-009',
    full_name: 'Lê Hoàng Minh',
    dharma_name: 'Quảng Minh',
    phone: '0989012345',
    email: 'lehoangminh@email.com',
    address: '789 Đường Pasteur, Quận 5, TP.HCM',
    birth_year_solar: 1975,
    birth_year_lunar: convertToLunarYear(1975),
    birth_date: '1975-11-22',
    birth_date_lunar: '20/10/Ất Mão',
    gender: 'male',
    status: 'alive',
    family_id: 'fam-003',
    avatar_url: '',
    zodiac_info: 'Tuổi Ất Mão - Mệnh Thủy',
    notes: 'Con trai của Lê Thị Cúc',
    created_at: '2024-01-10'
  },
  {
    id: 'pt-010',
    full_name: 'Phạm Thị Hồng',
    dharma_name: 'Diệu Tâm',
    phone: '0990123456',
    email: 'phamthihong@email.com',
    address: '321 Đường Hai Bà Trưng, Quận Bình Thạnh, TP.HCM',
    birth_year_solar: 1978,
    birth_year_lunar: convertToLunarYear(1978),
    birth_date: '1978-07-30',
    birth_date_lunar: '26/06/Mậu Ngọ',
    gender: 'female',
    status: 'alive',
    family_id: 'fam-004',
    avatar_url: '',
    zodiac_info: 'Tuổi Mậu Ngọ - Mệnh Hỏa',
    notes: 'Vợ của Phạm Ngọc Hải',
    created_at: '2024-02-20'
  }
];

// Hàm lấy tên gia đình theo ID
export const getFamilyById = (familyId: string): Family | undefined => {
  return familiesData.find(f => f.id === familyId);
};

// Hàm lấy danh sách Phật tử theo gia đình
export const getFollowersByFamily = (familyId: string): Follower[] => {
  return followersData.filter(f => f.family_id === familyId);
};

// Hàm lấy Phật tử theo ID
export const getFollowerById = (followerId: string): Follower | undefined => {
  return followersData.find(f => f.id === followerId);
};

// Hàm lấy chủ hộ của gia đình
export const getHeadOfFamily = (familyId: string): Follower | undefined => {
  const family = getFamilyById(familyId);
  if (!family) return undefined;
  return getFollowerById(family.head_of_family_id);
};
