<?php

namespace App\Exports;

use App\Models\Audit;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AuditsTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'date',
            'greenhouse_id',
            'qc_name',
            'picker_code',
            'worker_name',
            'variety_name',
            'plot_code',
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
            'total_points',
        ];
    }

    public function array(): array
    {
        // Get 3 latest real audits from database
        $audits = Audit::orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // If no audits exist, return sample data
        if ($audits->isEmpty()) {
            return $this->getSampleData();
        }

        // Convert audits to array format
        $data = [];
        foreach ($audits as $audit) {
            $data[] = [
                $audit->date->format('Y-m-d'),
                $audit->greenhouse_id,
                $audit->qc_name,
                $audit->picker_code ?? '',
                $audit->worker_name,
                $audit->variety_name,
                'WW-YYYYY',
                $audit->bag_weight ?? '',
                $audit->qty ?? '',
                $audit->uniformity_qty ?? '',
                $audit->urc_weight_qty ?? '',
                $audit->length_qty ?? '',
                $audit->damaged_qty ?? '',
                $audit->leaf_burn_qty ?? '',
                $audit->yellow_spot_qty ?? '',
                $audit->wooden_qty ?? '',
                $audit->dirty_qty ?? '',
                $audit->wrong_label_qty ?? '',
                $audit->pest_disease_qty ?? '',
                $audit->total_points ?? '',
            ];
        }

        return $data;
    }

    protected function getSampleData(): array
    {
        return [
            [
                '2026-01-22',
                'GH001',
                'Nguyen Van A',
                'P001',
                'Tran Van B',
                'Rosa Hybrid Tea',
                '01-2026',
                '2.5',
                '100',
                '5',
                '2.3',
                '3',
                '2',
                '1',
                '0',
                '1',
                '0',
                '0',
                '1',
                '15',
            ],
            [
                '2026-01-22',
                'GH002',
                'Le Thi C',
                'P002',
                'Pham Van D',
                'Chrysanthemum',
                '02-2026',
                '3.0',
                '120',
                '3',
                '1.8',
                '2',
                '1',
                '0',
                '1',
                '0',
                '0',
                '0',
                '0',
                '8',
            ],
            [
                '2026-01-23',
                'GH001',
                'Nguyen Van A',
                'P003',
                'Hoang Thi E',
                'Carnation',
                '03-2026',
                '2.8',
                '110',
                '4',
                '2.0',
                '1',
                '1',
                '1',
                '0',
                '0',
                '1',
                '0',
                '0',
                '9',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add comment to date column explaining format
        $sheet->getComment('A1')->getText()->createTextRun(
            "Định dạng ngày: YYYY-MM-DD\nVí dụ: 2026-01-22\nHoặc: DD/MM/YYYY (22/01/2026)"
        );
        $sheet->getComment('A1')->setWidth('300px');
        $sheet->getComment('A1')->setHeight('80px');

        // Add borders to all data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:T' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Center align numeric columns
        $numericColumns = ['H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
        foreach ($numericColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        return [];
    }
}
