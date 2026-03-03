<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return ['fullname', 'user_code', 'email', 'password', 'role'];
    }

    public function array(): array
    {
        return [
            ['Nguyen Van A', 'NV001', 'vana@example.com', 'Passw0rd!', 'admin'],
            ['Tran Thi B', 'NV002', 'ttb@example.com', 'Passw0rd!', 'moderator'],
            ['Le Van C', 'NV003', 'lvc@example.com', 'Passw0rd!', '3'],
        ];
    }
}
