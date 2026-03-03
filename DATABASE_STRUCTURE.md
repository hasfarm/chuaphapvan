# Cấu Trúc CSDL - Bảng Users & Roles

## 📋 Tổng Quan

Dự án sử dụng hệ thống Role-Based Access Control (RBAC) với các tiêu chuẩn bảo mật của Laravel.

---

## 🔐 Bảng `roles`

Lưu trữ các role (vai trò) trong hệ thống.

### Cột và Kiểu Dữ Liệu:

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| `id` | BIGINT | Khóa chính, tự tăng |
| `name` | VARCHAR(255) | Tên role (unique): `admin`, `moderator`, `user` |
| `display_name` | VARCHAR(255) | Tên hiển thị (ví dụ: "Quản Trị Viên") |
| `description` | TEXT | Mô tả về quyền hạn của role |
| `created_at` | TIMESTAMP | Thời gian tạo |
| `updated_at` | TIMESTAMP | Thời gian cập nhật |
| `deleted_at` | TIMESTAMP | Thời gian xóa mềm (soft delete) |

### Dữ Liệu Mặc Định:

```
1. admin (Quản Trị Viên)
   - Có quyền truy cập đầy đủ đến tất cả các tính năng

2. moderator (Điều Hành Viên)
   - Có quyền quản lý nội dung và người dùng

3. user (Người Dùng Thường)
   - Quyền truy cập cơ bản
```

---

## 👤 Bảng `users`

Lưu trữ thông tin người dùng với các trường bảo mật.

### Cột và Kiểu Dữ Liệu:

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| `id` | BIGINT | Khóa chính, tự tăng |
| `role_id` | BIGINT (FK) | Khóa ngoại tới bảng roles (default: 3 = user) |
| `name` | VARCHAR(255) | Tên đầy đủ của người dùng |
| `email` | VARCHAR(255) | Email duy nhất (unique) |
| `email_verified_at` | TIMESTAMP | Thời gian xác thực email |
| `password` | VARCHAR(255) | Mật khẩu đã hash (bcrypt) |
| `phone` | VARCHAR(255) | Số điện thoại (nullable) |
| `avatar` | VARCHAR(255) | Đường dẫn ảnh đại diện (nullable) |
| `status` | ENUM | Trạng thái: `active`, `inactive`, `banned` |
| `is_verified` | BOOLEAN | Đã xác minh tài khoản? (default: false) |
| `remember_token` | VARCHAR(100) | Token "remember me" |
| `last_login_at` | TIMESTAMP | Thời gian đăng nhập cuối cùng |
| `last_login_ip` | VARCHAR(255) | Địa chỉ IP đăng nhập cuối cùng |
| `created_at` | TIMESTAMP | Thời gian tạo tài khoản |
| `updated_at` | TIMESTAMP | Thời gian cập nhật |
| `deleted_at` | TIMESTAMP | Thời gian xóa mềm (soft delete) |

### Constraints:

- `role_id` -> `roles.id` (ON DELETE: SET DEFAULT, ON UPDATE: CASCADE)
- `email` UNIQUE
- Indexes trên: `email`, `status`, `created_at`, `role_id`

---

## 📊 Quan Hệ Giữa Các Bảng

```
roles (1) ──── (N) users
  |
  └─ hasMany(User)

users (N) ──── (1) roles
  |
  └─ belongsTo(Role)
```

---

## 🔒 Tiêu Chuẩn Bảo Mật

### 1. **Mật Khẩu**
   - Hash với bcrypt (tự động qua Laravel)
   - Không bao giờ lưu trữ plaintext

### 2. **Soft Deletes**
   - Dữ liệu không bị xóa vĩnh viễn
   - Giữ lại để audit trail và recovery
   - Implement qua `SoftDeletes` trait

### 3. **Email Verification**
   - `email_verified_at` để xác minh email
   - `is_verified` boolean flag

### 4. **Status Control**
   - `status` enum: active/inactive/banned
   - Kiểm soát truy cập dễ dàng

### 5. **Login Tracking**
   - Ghi lại `last_login_at` và `last_login_ip`
   - Phát hiện hoạt động bất thường

### 6. **Role-Based Access**
   - `role_id` với foreign key constraint
   - Mặc định user thường (role_id = 3)
   - Set default khi xóa role

### 7. **Timestamps & Audit Trail**
   - `created_at`, `updated_at`, `deleted_at`
   - Theo dõi lịch sử thay đổi

---

## 💻 Sử Dụng Trong Code

### Model Relationships

```php
// Lấy role của user
$user->role;

// Lấy tất cả users của 1 role
$role->users;
```

### Role Checking

```php
// Kiểm tra role
$user->isAdmin();      // Kiểm tra admin
$user->isModerator();  // Kiểm tra moderator
$user->isActive();     // Kiểm tra active

// Kiểm tra role
$role->isAdmin();      // Kiểm tra role là admin
$role->isModerator();  // Kiểm tra role là moderator
```

### Cập Nhật Login Info

```php
// Ghi lại lần đăng nhập cuối
$user->recordLogin(request()->ip());
```

### Mass Fillable

```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'phone' => '0123456789',
    'avatar' => '/avatars/john.jpg',
    'status' => 'active',
    'is_verified' => true,
    'role_id' => 1, // admin
]);
```

---

## 🗄️ Lệnh Hữu Ích

```bash
# Chạy migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Seed database
php artisan db:seed

# Tạo user test
php artisan tinker
>>> \App\Models\User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => bcrypt('password')])
```

---

## 🔄 Soft Deletes

Soft deletes cho phép xóa mà vẫn giữ dữ liệu:

```php
// Xóa mềm (chỉ set deleted_at)
$user->delete();

// Khôi phục dữ liệu đã xóa
$user->restore();

// Xóa vĩnh viễn
$user->forceDelete();

// Lấy cả dữ liệu đã xóa
User::withTrashed()->get();

// Lấy chỉ dữ liệu đã xóa
User::onlyTrashed()->get();
```

---

**Cập nhật lần cuối:** 2025-12-12
