<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Greenhouse;

class GreenhouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $greenhouses = [
            ['greenhouse_name' => 'LH_A1', 'greenhouse_code' => 'LH_A1', 'area_size' => 1000, 'type' => 'A', 'description' => 'Nhà kính A1'],
            ['greenhouse_name' => 'LH_A2', 'greenhouse_code' => 'LH_A2', 'area_size' => 1000, 'type' => 'A', 'description' => 'Nhà kính A2'],
            ['greenhouse_name' => 'LH_A3', 'greenhouse_code' => 'LH_A3', 'area_size' => 1000, 'type' => 'A', 'description' => 'Nhà kính A3'],
            ['greenhouse_name' => 'LH_A4', 'greenhouse_code' => 'LH_A4', 'area_size' => 1000, 'type' => 'A', 'description' => 'Nhà kính A4'],
            ['greenhouse_name' => 'LH_A5', 'greenhouse_code' => 'LH_A5', 'area_size' => 1000, 'type' => 'A', 'description' => 'Nhà kính A5'],
            ['greenhouse_name' => 'LH_B2', 'greenhouse_code' => 'LH_B2', 'area_size' => 800, 'type' => 'B', 'description' => 'Nhà kính B2'],
            ['greenhouse_name' => 'LH_B3', 'greenhouse_code' => 'LH_B3', 'area_size' => 800, 'type' => 'B', 'description' => 'Nhà kính B3'],
            ['greenhouse_name' => 'LH_B4', 'greenhouse_code' => 'LH_B4', 'area_size' => 800, 'type' => 'B', 'description' => 'Nhà kính B4'],
            ['greenhouse_name' => 'LH_B6', 'greenhouse_code' => 'LH_B6', 'area_size' => 800, 'type' => 'B', 'description' => 'Nhà kính B6'],
            ['greenhouse_name' => 'LH_B7', 'greenhouse_code' => 'LH_B7', 'area_size' => 800, 'type' => 'B', 'description' => 'Nhà kính B7'],
            ['greenhouse_name' => 'LH_C1', 'greenhouse_code' => 'LH_C1', 'area_size' => 600, 'type' => 'C', 'description' => 'Nhà kính C1'],
            ['greenhouse_name' => 'LH_C2', 'greenhouse_code' => 'LH_C2', 'area_size' => 600, 'type' => 'C', 'description' => 'Nhà kính C2'],
            ['greenhouse_name' => 'LH_C3', 'greenhouse_code' => 'LH_C3', 'area_size' => 600, 'type' => 'C', 'description' => 'Nhà kính C3'],
            ['greenhouse_name' => 'LH_C4', 'greenhouse_code' => 'LH_C4', 'area_size' => 600, 'type' => 'C', 'description' => 'Nhà kính C4'],
            ['greenhouse_name' => 'LH_C5', 'greenhouse_code' => 'LH_C5', 'area_size' => 600, 'type' => 'C', 'description' => 'Nhà kính C5'],
            ['greenhouse_name' => 'LH_C7', 'greenhouse_code' => 'LH_C7', 'area_size' => 600, 'type' => 'C', 'description' => 'Nhà kính C7'],
        ];

        foreach ($greenhouses as $greenhouse) {
            Greenhouse::create([
                'farm_id' => 1,
                ...$greenhouse
            ]);
        }
    }
}
