<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AuditsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithMapping
{
    protected $audits;
    protected $filters;

    public function __construct($audits, $filters = [])
    {
        $this->audits = $audits;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->audits;
    }

    public function map($audit): array
    {
        $totalDefect = $audit->leaf_burn_qty + $audit->yellow_spot_qty + $audit->wooden_qty +
            $audit->dirty_qty + $audit->wrong_label_qty + $audit->pest_disease_qty;

        return [
            $audit->date->format('d/m/Y'),
            $audit->greenhouse_id,
            $audit->qc_name,
            $audit->picker_code ?? '',
            $audit->worker_name,
            $audit->variety_name,
            $audit->plot_code,
            number_format($audit->bag_weight, 2),
            $audit->qty,
            $audit->uniformity_qty,
            number_format($audit->urc_weight_qty, 0),
            $audit->length_qty,
            $audit->damaged_qty,
            $audit->leaf_burn_qty,
            $audit->yellow_spot_qty,
            $audit->wooden_qty,
            $audit->dirty_qty,
            $audit->wrong_label_qty,
            $audit->pest_disease_qty,
            $totalDefect,
            $audit->total_points,
        ];
    }

    public function headings(): array
    {
        $filterText = $this->buildFilterText();

        return [
            ['BÁO CÁO KIỂM SOÁT CHẤT LƯỢNG'],
            [$filterText],
            [],
            [
                'Ngày',
                'Mã NH Kính',
                'Tên QC',
                'Mã CN',
                'Tên CN',
                'Giống',
                'Plot',
                'TL Bịch (kg)',
                'QTY',
                'Uniformity QTY',
                'TL ngọn',
                'Ngắn Dài',
                'Damaged QTY',
                'Cháy lá',
                'Đốm vàng',
                'Xơ (Wooden)',
                'Bẩn (Dirty)',
                'Nhãn sai',
                'Sâu bệnh',
                'Tổng lỗi',
                'Tổng điểm',
            ]
        ];
    }

    protected function buildFilterText(): string
    {
        $parts = [];

        if (!empty($this->filters['search'])) {
            $parts[] = 'Tìm kiếm: ' . $this->filters['search'];
        }
        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $parts[] = 'Từ ngày: ' . date('d/m/Y', strtotime($this->filters['date_from'])) .
                ' đến ' . date('d/m/Y', strtotime($this->filters['date_to']));
        } elseif (!empty($this->filters['date_from'])) {
            $parts[] = 'Từ ngày: ' . date('d/m/Y', strtotime($this->filters['date_from']));
        } elseif (!empty($this->filters['date_to'])) {
            $parts[] = 'Đến ngày: ' . date('d/m/Y', strtotime($this->filters['date_to']));
        }
        if (!empty($this->filters['greenhouse_id'])) {
            $parts[] = 'NH Kính: ' . $this->filters['greenhouse_id'];
        }
        if (!empty($this->filters['qc_name'])) {
            $parts[] = 'QC: ' . $this->filters['qc_name'];
        }
        if (!empty($this->filters['worker_name'])) {
            $parts[] = 'CN: ' . $this->filters['worker_name'];
        }
        if (!empty($this->filters['variety_name'])) {
            $parts[] = 'Giống: ' . $this->filters['variety_name'];
        }

        return empty($parts) ? 'Tất cả dữ liệu' : 'Bộ lọc: ' . implode(' | ', $parts);
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells cho tiêu đề
        $sheet->mergeCells('A1:U1');
        $sheet->mergeCells('A2:U2');

        // Style cho tiêu đề chính
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '10b981']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Style cho bộ lọc
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 11,
                'color' => ['rgb' => '6b7280']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Style cho header
        $sheet->getStyle('A4:U4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10b981']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style cho data rows
        $lastRow = $this->audits->count() + 4;
        $sheet->getStyle('A5:U' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Center align cho các cột số
        $sheet->getStyle('H5:U' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Màu cho cột tổng điểm
        foreach ($this->audits as $index => $audit) {
            $row = $index + 5;
            $points = $audit->total_points;
            $color = $points == 0 ? '10b981' : ($points <= 5 ? 'f59e0b' : 'ef4444');

            $sheet->getStyle('U' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => $color]
                ]
            ]);
        }

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(4)->setRowHeight(25);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Ngày
            'B' => 12,  // Mã NH Kính
            'C' => 15,  // Tên QC
            'D' => 10,  // Mã CN
            'E' => 20,  // Tên CN
            'F' => 15,  // Giống
            'G' => 10,  // Plot
            'H' => 12,  // TL Bịch
            'I' => 10,  // QTY
            'J' => 14,  // Uniformity QTY
            'K' => 10,  // TL ngọn
            'L' => 12,  // Ngắn Dài
            'M' => 12,  // Damaged QTY
            'N' => 10,  // Cháy lá
            'O' => 10,  // Đốm vàng
            'P' => 12,  // Xơ (Wooden)
            'Q' => 12,  // Bẩn (Dirty)
            'R' => 10,  // Nhãn sai
            'S' => 10,  // Sâu bệnh
            'T' => 10,  // Tổng lỗi
            'U' => 12,  // Tổng điểm
        ];
    }

    public function title(): string
    {
        return 'Kiểm Soát Chất Lượng';
    }
}
