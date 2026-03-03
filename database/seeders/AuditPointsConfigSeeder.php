<?php

namespace Database\Seeders;

use App\Models\AuditPointsConfig;
use Illuminate\Database\Seeder;

class AuditPointsConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'field_name' => 'leaf_burn_qty',
                'display_name' => 'Leaf Burn',
                'points' => 1,
                'is_active' => true,
            ],
            [
                'field_name' => 'yellow_spot_qty',
                'display_name' => 'Yellow Spot',
                'points' => 1,
                'is_active' => true,
            ],
            [
                'field_name' => 'wooden_qty',
                'display_name' => 'Wooden',
                'points' => 1,
                'is_active' => true,
            ],
            [
                'field_name' => 'dirty_qty',
                'display_name' => 'Dirty',
                'points' => 1,
                'is_active' => true,
            ],
            [
                'field_name' => 'wrong_label_qty',
                'display_name' => 'Wrong Label',
                'points' => 1,
                'is_active' => true,
            ],
            [
                'field_name' => 'pest_disease_qty',
                'display_name' => 'Pest Disease',
                'points' => 1,
                'is_active' => true,
            ],
            [
                'field_name' => 'qty',
                'display_name' => 'QTY',
                'points' => 10,
                'is_active' => true,
            ],
            [
                'field_name' => 'uniformity_qty',
                'display_name' => 'Uniformity QTY',
                'points' => 20,
                'is_active' => true,
            ],
            [
                'field_name' => 'urc_weight_qty',
                'display_name' => 'URC Weight',
                'points' => 20,
                'is_active' => true,
            ],
            [
                'field_name' => 'length_qty',
                'display_name' => 'Ngắn Dài (Length)',
                'points' => 5,
                'is_active' => true,
            ],
            [
                'field_name' => 'damaged_qty',
                'display_name' => 'Damaged QTY',
                'points' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($configs as $config) {
            AuditPointsConfig::updateOrCreate(
                ['field_name' => $config['field_name']],
                $config
            );
        }
    }
}
