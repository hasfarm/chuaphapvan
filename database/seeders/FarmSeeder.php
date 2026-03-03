<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Farm;

class FarmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Farm::create([
            'farm_name' => 'chuaphapvan Farm',
            'farm_code' => 'LH001',
            'description' => 'Trang trại chính',
            'location' => 'Long An',
            'phone' => '0123456789',
            'manager_name' => 'Nguyễn Văn A',
        ]);
    }
}
