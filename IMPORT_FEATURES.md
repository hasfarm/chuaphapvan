1. **Thêm nút tải file mẫu Excel** - Thêm nút "Tải file mẫu" trên trang quản lý người dùng
   - File mẫu có format với các cột: fullname, name, email, password, role
   - Route: `GET /admin/users/template` → `downloadTemplate()`

2. **Thêm nút import Excel** - Cho phép upload file Excel
   - Form với file input (accept .xlsx, .xls)
   - Button Import để gửi file
   - Route: `POST /admin/users/import` → `import()`

3. **Import logic**:
   - Validate file (xlsx, xls, max 10MB)
   - Sử dụng `UsersImport` class để xử lý dữ liệu
   - `updateOrCreate` user nếu email trùng
   - Hỗ trợ role bằng ID, name, hoặc display_name
   - Mặc định role = 3 (user bình thường)
   - Error handling với thông báo chi tiết

4. **Template file (users_template.xlsx)**:
   - Dòng 1: Headers (fullname, name, email, password, role)
   - 3 dòng ví dụ:
     - Nguyen Van A | vana | vana@example.com | Passw0rd! | admin
     - Tran Thi B | ttb | ttb@example.com | Passw0rd! | moderator
     - Le Van C | lvc | lvc@example.com | Passw0rd! | 3

5. **Giao diện improvements**:
   - Thêm alert success/error messages trên index page
   - Update placeholder search text bao gồm "họ tên"
