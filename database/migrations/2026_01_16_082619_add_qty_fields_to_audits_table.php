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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('audits', 'uniformity_qty')) {
                $table->integer('uniformity_qty')->default(0)->after('bag_weight')->comment('Số lượng đồng đều');
            }
            if (!Schema::hasColumn('audits', 'urc_weight_qty')) {
                $table->decimal('urc_weight_qty', 8, 2)->default(0)->after('uniformity_qty')->comment('Trọng lượng URC');
            }
            if (!Schema::hasColumn('audits', 'length_qty')) {
                $table->integer('length_qty')->default(0)->after('urc_weight_qty')->comment('Số lượng chiều dài');
            }
            if (!Schema::hasColumn('audits', 'damaged_qty')) {
                $table->integer('damaged_qty')->default(0)->after('length_qty')->comment('Số lượng hư hỏng');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn([
                'uniformity_qty',
                'urc_weight_qty',
                'length_qty',
                'damaged_qty'
            ]);
        });
    }
};
