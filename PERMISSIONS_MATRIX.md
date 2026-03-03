# Bảng Phân Quyền Hệ Thống chuaphapvan QC

## Mô Tả Roles

| Role | Mã | Mô Tả |
|------|-----|-------|
| Quản Trị Viên | admin | Toàn quyền quản lý hệ thống |
| Người Quản Lý | moderator | Quản lý nội dung chính, không quản lý users/roles |
| Người Dùng | user | Chỉ xem và nhập dữ liệu của chính mình |

---

## Bảng Phân Quyền Chi Tiết

### 1. Quản Lý Người Dùng (Users)

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Xem danh sách users | ✅ | ❌ | ❌ |
| Tạo user mới | ✅ | ❌ | ❌ |
| Chỉnh sửa user | ✅ | ❌ | ❌ (chỉ chính mình) |
| Xóa user | ✅ | ❌ | ❌ |
| Phân công role | ✅ | ❌ | ❌ |

### 2. Quản Lý Vai Trò (Roles)

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Xem danh sách roles | ✅ | ❌ | ❌ |
| Tạo role mới | ✅ | ❌ | ❌ |
| Chỉnh sửa role | ✅ | ❌ | ❌ |
| Xóa role | ✅ | ❌ | ❌ |

### 3. Quản Lý Trang Trại (Farms)

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Xem danh sách trang trại | ✅ | ✅ | ❌ |
| Tạo trang trại mới | ✅ | ✅ | ❌ |
| Chỉnh sửa trang trại | ✅ | ✅ | ❌ |
| Xóa trang trại | ✅ | ❌ | ❌ |
| Xem chi tiết | ✅ | ✅ | ❌ |

### 4. Quản Lý Nhà Kính (Greenhouses)

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Xem danh sách nhà kính | ✅ | ✅ | ❌ |
| Tạo nhà kính mới | ✅ | ✅ | ❌ |
| Chỉnh sửa nhà kính | ✅ | ✅ | ❌ |
| Xóa nhà kính | ✅ | ❌ | ❌ |
| Xem chi tiết | ✅ | ✅ | ❌ |

### 5. Quản Lý Sản Phẩm (Products)

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Xem danh sách sản phẩm | ✅ | ✅ | ❌ |
| Tạo sản phẩm mới | ✅ | ✅ | ❌ |
| Chỉnh sửa sản phẩm | ✅ | ✅ | ❌ |
| Xóa sản phẩm | ✅ | ❌ | ❌ |
| Xem chi tiết | ✅ | ✅ | ❌ |

### 6. Kiểm Soát Chất Lượng (Audits)

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Xem tất cả audits | ✅ | ✅ | ❌ |
| Xem audits của chính mình | ✅ | ✅ | ✅ |
| Tạo audit mới | ✅ | ✅ | ✅ |
| Chỉnh sửa audit của chính mình | ✅ | ✅ | ✅ |
| Chỉnh sửa audit của người khác | ✅ | ✅ | ❌ |
| Xóa audit của chính mình | ✅ | ✅ | ✅ |
| Xóa audit của người khác | ✅ | ❌ | ❌ |
| Xem báo cáo audits | ✅ | ✅ | ❌ |

### 7. Admin Panel

| Chức Năng | Admin | Moderator | User |
|-----------|:-----:|:---------:|:----:|
| Truy cập Admin Panel | ✅ | ❌ | ❌ |
| Xem Dashboard Admin | ✅ | ❌ | ❌ |
| Quản lý Users | ✅ | ❌ | ❌ |
| Quản lý Roles | ✅ | ❌ | ❌ |
| Quản lý Farms | ✅ | ❌ | ❌ |
| Quản lý Greenhouses | ✅ | ❌ | ❌ |
| Quản lý Products | ✅ | ❌ | ❌ |

---

## Ghi Chú Thực Hiện

### Hiện Tại Đã Implement:
- ✅ Middleware `IsAdmin` - Chỉ admin mới truy cập `/admin`
- ✅ Audit filter theo user_id - User chỉ thấy dữ liệu của chính mình
- ✅ Login redirect → Audits index (chỉ dữ liệu của user)

### Cần Implement Thêm:
- [ ] Middleware check role cho Moderator (để truy cập một số tính năng)
- [ ] Authorization policies cho Audit (edit/delete riêng mình)
- [ ] Authorization policies cho admin panel (nếu cần cho moderator sau)
- [ ] Audit log để tracking người dùng thay đổi dữ liệu
- [ ] API responses tuân theo phân quyền

---

## Hướng Dẫn Cài Đặt Phân Quyền

### Cho Moderator (Nếu Cần Sau):
```php
// Route middleware
Route::middleware(['auth', 'role:admin,moderator'])->group(function () {
    Route::resource('farms', FarmController::class);
    Route::resource('greenhouses', GreenhouseController::class);
    Route::resource('products', ProductController::class);
});
```

### Cho User (Authorization Policies):
```php
// Audit policy
public function update(User $user, Audit $audit)
{
    return $user->id === $audit->user_id || $user->isAdmin();
}

public function delete(User $user, Audit $audit)
{
    return $user->id === $audit->user_id || $user->isAdmin();
}
```
