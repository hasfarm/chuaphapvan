<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'qty_quantity',
                'uniformity_level',
                'urc_weight',
                'legit_height',
                'damage_count',
                'leaf_yellow_spot',
                'leaf_yellow_vein',
                'wooden_spot',
                'dirty_damage',
                'total_label_defect',
                'pest_disease',
                'total_defect'
            ]);
        });

        Schema::table('audits', function (Blueprint $table) {
            // Add new columns
            $table->integer('leaf_burn_qty')->default(0)->after('bag_weight');
            $table->integer('yellow_spot_qty')->default(0)->after('leaf_burn_qty');
            $table->integer('wooden_qty')->default(0)->after('yellow_spot_qty');
            $table->integer('dirty_qty')->default(0)->after('wooden_qty');
            $table->integer('wrong_label_qty')->default(0)->after('dirty_qty');
            $table->integer('pest_disease_qty')->default(0)->after('wrong_label_qty');
            $table->integer('total_points')->default(0)->after('pest_disease_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'leaf_burn_qty',
                'yellow_spot_qty',
                'wooden_qty',
                'dirty_qty',
                'wrong_label_qty',
                'pest_disease_qty',
                'total_points'
            ]);
        });

        Schema::table('audits', function (Blueprint $table) {
            // Add back old columns
            $table->integer('qty_quantity')->default(0)->after('bag_weight');
            $table->integer('uniformity_level')->default(0)->after('qty_quantity');
            $table->decimal('urc_weight', 8, 2)->default(0)->after('uniformity_level');
            $table->integer('legit_height')->default(0)->after('urc_weight');
            $table->integer('damage_count')->default(0)->after('legit_height');
            $table->integer('leaf_yellow_spot')->default(0)->after('damage_count');
            $table->integer('leaf_yellow_vein')->default(0)->after('leaf_yellow_spot');
            $table->integer('wooden_spot')->default(0)->after('leaf_yellow_vein');
            $table->integer('dirty_damage')->default(0)->after('wooden_spot');
            $table->integer('total_label_defect')->default(0)->after('dirty_damage');
            $table->integer('pest_disease')->default(0)->after('total_label_defect');
            $table->integer('total_defect')->default(0)->after('pest_disease');
        });
    }
};
