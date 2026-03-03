<?php

namespace App\Imports;

use App\Models\Audit;
use App\Models\Greenhouse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AuditsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public int $createdCount = 0;
    public int $updatedCount = 0;
    public int $skippedCount = 0;
    public array $errors = [];

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because Excel is 1-based and we have header row

            try {
                // Map Excel columns to database fields
                // Handle headers with extra text like "date (YYYY-MM-DD)"
                $data = [
                    'date' => $row['date'] ?? $row['date_yyyy_mm_dd'] ?? $row['ngay'] ?? null,
                    'greenhouse_id' => $row['greenhouse_id'] ?? $row['ma_nha_kinh'] ?? null,
                    'qc_name' => $row['qc_name'] ?? $row['ten_qc'] ?? null,
                    'picker_code' => $row['picker_code'] ?? $row['ma_picker'] ?? null,
                    'worker_name' => $row['worker_name'] ?? $row['ten_cong_nhan'] ?? null,
                    'variety_name' => $row['variety_name'] ?? $row['giong'] ?? null,
                    'plot_code' => $row['plot_code'] ?? $row['ma_luong'] ?? null,
                    'bag_weight' => $row['bag_weight'] ?? $row['trong_luong_tui'] ?? 0,
                    'qty' => $row['qty'] ?? $row['so_luong'] ?? 0,
                    'uniformity_qty' => $row['uniformity_qty'] ?? $row['dong_deu'] ?? 0,
                    'urc_weight_qty' => $row['urc_weight_qty'] ?? $row['trong_luong_urc'] ?? 0,
                    'length_qty' => $row['length_qty'] ?? $row['chieu_dai'] ?? 0,
                    'damaged_qty' => $row['damaged_qty'] ?? $row['hong'] ?? 0,
                    'leaf_burn_qty' => $row['leaf_burn_qty'] ?? $row['chay_la'] ?? 0,
                    'yellow_spot_qty' => $row['yellow_spot_qty'] ?? $row['dom_vang'] ?? 0,
                    'wooden_qty' => $row['wooden_qty'] ?? $row['go_hoa'] ?? 0,
                    'dirty_qty' => $row['dirty_qty'] ?? $row['ban'] ?? 0,
                    'wrong_label_qty' => $row['wrong_label_qty'] ?? $row['nhan_sai'] ?? 0,
                    'pest_disease_qty' => $row['pest_disease_qty'] ?? $row['sau_benh'] ?? 0,
                    'total_points' => $row['total_points'] ?? $row['tong_diem'] ?? 0,
                ];

                // Debug: Log actual row keys to understand the issue
                if ($rowNumber == 2) {
                    \Log::info('Row 2 keys: ' . implode(', ', array_keys($row->toArray())));
                    \Log::info('Row 2 date value: ' . json_encode($row->toArray()));
                }

                // Skip empty rows - check if row has any meaningful data
                // Filter out null, empty strings, and whitespace-only values
                $hasData = false;
                foreach ($data as $key => $value) {
                    // Skip checking numeric fields for row detection
                    if (in_array($key, [
                        'bag_weight',
                        'qty',
                        'uniformity_qty',
                        'urc_weight_qty',
                        'length_qty',
                        'damaged_qty',
                        'leaf_burn_qty',
                        'yellow_spot_qty',
                        'wooden_qty',
                        'dirty_qty',
                        'wrong_label_qty',
                        'pest_disease_qty',
                        'total_points'
                    ])) {
                        continue;
                    }

                    // Check if value is not null and not empty after trimming
                    if ($value !== null && trim((string)$value) !== '') {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) {
                    // Silently skip this empty row
                    continue;
                }

                // Validate the data (skip date validation here, will validate after conversion)
                $validator = Validator::make($data, [
                    'greenhouse_id' => 'required|string',
                    'qc_name' => 'required|string|max:255',
                    'picker_code' => 'required|string|max:255',
                    'worker_name' => 'required|string|max:255',
                    'variety_name' => 'required|string|max:255',
                    'plot_code' => 'required|string|max:255',
                    'bag_weight' => 'nullable|numeric',
                    'qty' => 'nullable|integer',
                    'uniformity_qty' => 'nullable|integer',
                    'urc_weight_qty' => 'nullable|numeric',
                    'length_qty' => 'nullable|integer',
                    'damaged_qty' => 'nullable|integer',
                    'leaf_burn_qty' => 'nullable|integer',
                    'yellow_spot_qty' => 'nullable|integer',
                    'wooden_qty' => 'nullable|integer',
                    'dirty_qty' => 'nullable|integer',
                    'wrong_label_qty' => 'nullable|integer',
                    'pest_disease_qty' => 'nullable|integer',
                    'total_points' => 'nullable|numeric',
                ]);

                if ($validator->fails()) {
                    $messages = implode('; ', $validator->errors()->all());
                    $this->errors[] = "Dòng {$rowNumber}: {$messages}";
                    $this->skippedCount++;
                    continue;
                }

                // Convert and validate date format
                try {
                    if (empty($data['date'])) {
                        throw new \Exception("Ngày không được để trống");
                    }

                    if ($data['date'] instanceof \PhpOffice\PhpSpreadsheet\Shared\Date) {
                        $data['date'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['date'])->format('Y-m-d');
                    } elseif (is_numeric($data['date']) && $data['date'] > 0) {
                        // Excel date serial number
                        $data['date'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['date'])->format('Y-m-d');
                    } elseif (is_string($data['date'])) {
                        // Try to parse various date formats
                        $originalDate = trim($data['date']);
                        $parsedDate = null;

                        // Pattern matching for different date formats
                        // Extract date part if there's extra text (e.g., "19/01/2025 LH_A2" -> "19/01/2025")
                        // YYYY-MM-DD with optional text after (2026-01-22 or 2026-01-22 LH_A2)
                        if (preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})/', $originalDate, $matches)) {
                            $year = (int)$matches[1];
                            $month = (int)$matches[2];
                            $day = (int)$matches[3];
                            $parsedDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        }
                        // DD/MM/YYYY with optional text after (22/01/2026 or 22/01/2026 LH_A2)
                        elseif (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $originalDate, $matches)) {
                            $day = (int)$matches[1];
                            $month = (int)$matches[2];
                            $year = (int)$matches[3];
                            $parsedDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        }
                        // DD-MM-YYYY with optional text after (22-01-2026 or 22-01-2026 LH_A2)
                        elseif (preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})/', $originalDate, $matches)) {
                            $day = (int)$matches[1];
                            $month = (int)$matches[2];
                            $year = (int)$matches[3];
                            $parsedDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        }
                        // DD.MM.YYYY with optional text after (22.01.2026 or 22.01.2026 LH_A2)
                        elseif (preg_match('/(\d{1,2})\.(\d{1,2})\.(\d{4})/', $originalDate, $matches)) {
                            $day = (int)$matches[1];
                            $month = (int)$matches[2];
                            $year = (int)$matches[3];
                            $parsedDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        }
                        // YYYY/MM/DD with optional text after (2026/01/22 or 2026/01/22 LH_A2)
                        elseif (preg_match('/(\d{4})\/(\d{1,2})\/(\d{1,2})/', $originalDate, $matches)) {
                            $year = (int)$matches[1];
                            $month = (int)$matches[2];
                            $day = (int)$matches[3];
                            $parsedDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        } else {
                            // Try Carbon parse as last resort
                            try {
                                $carbonDate = \Carbon\Carbon::parse($originalDate);
                                $parsedDate = $carbonDate->format('Y-m-d');
                            } catch (\Exception $carbonError) {
                                throw new \Exception("Không nhận dạng được định dạng ngày. Vui lòng sử dụng: YYYY-MM-DD, DD/MM/YYYY, hoặc DD-MM-YYYY");
                            }
                        }

                        // Validate the parsed date
                        if ($parsedDate) {
                            $dateParts = explode('-', $parsedDate);
                            if (count($dateParts) === 3 && checkdate((int)$dateParts[1], (int)$dateParts[2], (int)$dateParts[0])) {
                                $data['date'] = $parsedDate;
                            } else {
                                throw new \Exception("Ngày không hợp lệ: {$originalDate}");
                            }
                        } else {
                            throw new \Exception("Không thể chuyển đổi ngày: {$originalDate}");
                        }
                    } else {
                        throw new \Exception("Định dạng ngày không được hỗ trợ");
                    }
                } catch (\Exception $e) {
                    $this->errors[] = "Dòng {$rowNumber}: Lỗi ngày tháng - " . $e->getMessage() . " (Giá trị gốc: '" . ($row['date'] ?? $row['ngay'] ?? 'N/A') . "')";
                    $this->skippedCount++;
                    continue;
                }

                // Get greenhouse_name from greenhouse_id
                $greenhouse = Greenhouse::where('greenhouse_code', $data['greenhouse_id'])->first();
                if ($greenhouse) {
                    $data['greenhouse_name'] = $greenhouse->greenhouse_name;
                } else {
                    $this->errors[] = "Dòng {$rowNumber}: Không tìm thấy nhà kính với mã '{$data['greenhouse_id']}'";
                    $this->skippedCount++;
                    continue;
                }

                // Set user_id to current authenticated user
                $data['user_id'] = auth()->id();

                // Create audit record
                Audit::create($data);
                $this->createdCount++;
            } catch (\Exception $e) {
                $this->errors[] = "Dòng {$rowNumber}: {$e->getMessage()}";
                $this->skippedCount++;
            }
        }
    }

    public function getStats(): array
    {
        return [
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'skipped' => $this->skippedCount,
            'errors' => $this->errors,
        ];
    }
}
