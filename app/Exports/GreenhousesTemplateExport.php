<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GreenhousesTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return ['farm', 'greenhouse_name', 'greenhouse_code', 'area_size', 'type', 'description'];
    }

    public function array(): array
    {
        return [
            ['Farm A', 'Nhà kính A1', 'GH-A1', '500', 'Kính cường lực', 'Nhà kính trồng rau sạch'],
            ['Farm A', 'Nhà kính A2', 'GH-A2', '300', 'Lưới che', 'Nhà kính trồng hoa'],
            ['Farm B', 'Nhà kính B1', 'GH-B1', '1000', 'Nhựa UV', 'Nhà kính trồng cây ăn quả'],
        ];
    }
}
