# Hướng Dẫn Import Dữ Liệu Audit

## Tổng Quan
Chức năng Import Excel cho phép bạn nhập hàng loạt dữ liệu kiểm soát chất lượng từ file Excel vào hệ thống.

## Các Bước Thực Hiện

### 1. Tải Template Excel
- Truy cập trang Lịch Sử Kiểm Soát
- Click nút **"Import Excel"**
- Trong modal hiển thị, click **"Tải Template Excel Mẫu"**
- File template sẽ được tải xuống với tên: `Audits_Import_Template_[ngày].xlsx`

### 2. Chuẩn Bị Dữ Liệu

#### Cấu trúc file Excel:
File template có các cột sau (thứ tự phải giữ nguyên):

| Cột | Tên Tiếng Anh | Mô Tả | Bắt Buộc | Định Dạng |
|-----|---------------|-------|----------|-----------|
| 1 | date | Ngày kiểm tra | Có | YYYY-MM-DD (VD: 2026-01-22) |
| 2 | greenhouse_id | Mã nhà kính | Có | Phải tồn tại trong hệ thống |
| 3 | qc_name | Tên QC | Có | Chuỗi văn bản |
| 4 | picker_code | Mã Picker | Có | Chuỗi văn bản |
| 5 | worker_name | Tên công nhân | Có | Chuỗi văn bản |
| 6 | variety_name | Giống | Có | Chuỗi văn bản |
| 7 | plot_code | Mã lượng | Có | WW.YYYY (VD: 01.2026) |
| 8 | bag_weight | Trọng lượng túi | Không | Số thập phân |
| 9 | qty | Số lượng | Không | Số nguyên |
| 10 | uniformity_qty | Đồng đều | Không | Số nguyên |
| 11 | urc_weight_qty | Trọng lượng URC | Không | Số thập phân |
| 12 | length_qty | Chiều dài | Không | Số nguyên |
| 13 | damaged_qty | Hỏng | Không | Số nguyên |
| 14 | leaf_burn_qty | Cháy lá | Không | Số nguyên |
| 15 | yellow_spot_qty | Đốm vàng | Không | Số nguyên |
| 16 | wooden_qty | Gỗ hóa | Không | Số nguyên |
| 17 | dirty_qty | Bẩn | Không | Số nguyên |
| 18 | wrong_label_qty | Nhãn sai | Không | Số nguyên |
| 19 | pest_disease_qty | Sâu bệnh | Không | Số nguyên |
| 20 | total_points | Tổng điểm | Không | Số thập phân |

#### Lưu Ý Quan Trọng:
1. **KHÔNG XÓA DÒNG TIÊU ĐỀ** (dòng đầu tiên)
2. **Định dạng ngày**: Phải theo chuẩn YYYY-MM-DD (VD: 2026-01-22)
3. **Mã nhà kính**: Phải tồn tại trong hệ thống (kiểm tra trước khi import)
4. **Mã lượng**: Phải theo định dạng WW.YYYY (VD: 01.2026, 27.2025)
5. **File Excel**: Chỉ chấp nhận .xlsx hoặc .xls (KHÔNG dùng CSV)
6. **Kích thước file**: Tối đa 10MB

### 3. Upload và Import

1. Điền đầy đủ dữ liệu vào file template
2. Lưu file Excel
3. Quay lại trang Lịch Sử Kiểm Soát
4. Click nút **"Import Excel"**
5. Click **"Chọn File Excel"** và chọn file đã chuẩn bị
6. Click **"Bắt Đầu Import"**
7. Chờ hệ thống xử lý và hiển thị kết quả

## Kết Quả Import

Sau khi import, hệ thống sẽ hiển thị:
- ✅ **Số bản ghi tạo mới thành công**
- ⚠️ **Số bản ghi bị bỏ qua** (nếu có lỗi)
- ❌ **Chi tiết lỗi** (nếu có)

### Các Lỗi Thường Gặp:

1. **"Không tìm thấy nhà kính với mã..."**
   - Nguyên nhân: Mã nhà kính không tồn tại trong hệ thống
   - Giải pháp: Kiểm tra và sửa mã nhà kính

2. **"Định dạng ngày không hợp lệ"**
   - Nguyên nhân: Ngày không đúng định dạng YYYY-MM-DD
   - Giải pháp: Sửa lại định dạng ngày

3. **"Validation failed..."**
   - Nguyên nhân: Thiếu dữ liệu bắt buộc hoặc sai định dạng
   - Giải pháo: Kiểm tra và điền đầy đủ các trường bắt buộc

## Ví Dụ Dữ Liệu Mẫu

```
date        | greenhouse_id | qc_name      | picker_code | worker_name  | variety_name    | plot_code | ...
2026-01-22  | GH001        | Nguyen Van A | P001        | Tran Van B   | Rosa Hybrid Tea | 01.2026   | ...
2026-01-22  | GH002        | Le Thi C     | P002        | Pham Van D   | Chrysanthemum   | 02.2026   | ...
2026-01-23  | GH001        | Nguyen Van A | P003        | Hoang Thi E  | Carnation       | 01.2026   | ...
```

## Khuyến Nghị

1. **Kiểm tra dữ liệu**: Trước khi import, kiểm tra kỹ dữ liệu trong file Excel
2. **Import từng phần**: Với lượng dữ liệu lớn, nên chia nhỏ thành nhiều file
3. **Backup**: Nên backup dữ liệu hiện tại trước khi import
4. **Test với dữ liệu mẫu**: Thử import với vài dòng dữ liệu trước

## Hỗ Trợ

Nếu gặp vấn đề trong quá trình import, vui lòng liên hệ bộ phận hỗ trợ kỹ thuật.
