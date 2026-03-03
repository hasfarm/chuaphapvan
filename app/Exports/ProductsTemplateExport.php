<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return ['product_name', 'product_code', 'description', 'price', 'color', 'variety', 'shelf_life_days'];
    }

    public function array(): array
    {
        return [
            ['Lan Hồ Điệp Trắng', 'PROD-001', 'Lan hồ điệp trắng cao cấp', '250000', '#FFFFFF', 'Phalaenopsis', '30'],
            ['Lan Hồ Điệp Tím', 'PROD-002', 'Lan hồ điệp tím đẹp', '280000', '#9333EA', 'Phalaenopsis', '30'],
            ['Lan Dendrobium Vàng', 'PROD-003', 'Lan dendrobium vàng rực', '200000', '#FCD34D', 'Dendrobium', '25'],
        ];
    }
}
