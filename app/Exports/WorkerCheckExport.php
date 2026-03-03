<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class WorkerCheckExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $workers;
    protected $date;

    public function __construct($workers, $date)
    {
        $this->workers = $workers;
        $this->date = $date;
    }

    public function collection()
    {
        return $this->workers->map(function ($worker, $index) {
            return [
                'stt' => $index + 1,
                'picker_code' => $worker->picker_code,
                'worker_name' => $worker->worker_name,
                'check_count' => $worker->check_count,
                'status' => $worker->meets_requirement ? 'Đạt yêu cầu' : 'Chưa đủ (' . (6 - $worker->check_count) . ' lần)',
            ];
        });
    }

    public function headings(): array
    {
        $dateFormatted = Carbon::parse($this->date)->format('d/m/Y');

        return [
            ['BÁO CÁO CÔNG NHÂN ĐƯỢC KIỂM TRA'],
            ['Ngày: ' . $dateFormatted],
            [],
            ['STT', 'Mã Picker', 'Tên Công Nhân', 'Số lần kiểm tra', 'Trạng thái']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells cho tiêu đề
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');

        // Style cho tiêu đề chính
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '7c3aed']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Style cho ngày
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Style cho header
        $sheet->getStyle('A4:E4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '7c3aed']
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
        $lastRow = $this->workers->count() + 4;
        $sheet->getStyle('A5:E' . $lastRow)->applyFromArray([
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
        $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D5:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Màu cho status
        foreach ($this->workers as $index => $worker) {
            $row = $index + 5;
            $color = $worker->meets_requirement ? '10b981' : 'f59e0b';
            $sheet->getStyle('E' . $row)->applyFromArray([
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
            'A' => 8,
            'B' => 15,
            'C' => 25,
            'D' => 18,
            'E' => 25,
        ];
    }

    public function title(): string
    {
        return 'Công Nhân Kiểm Tra';
    }
}
