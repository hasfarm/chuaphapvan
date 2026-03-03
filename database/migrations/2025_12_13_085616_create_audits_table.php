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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();

            // Ngày
            $table->date('date')->comment('Ngày Kiểm Soát Chất Lượng');

            // Nhà kính
            $table->string('greenhouse_id')->comment('Mã nhà kính');
            $table->string('greenhouse_name')->nullable()->comment('Tên nhà kính');

            // Chất lượng
            $table->string('qc_name')->comment('Tên QC');

            // Mã
            $table->string('picker_code')->comment('Mã người chọn');
            $table->string('worker_name')->comment('Tên công nhân');

            // Hoa
            $table->string('variety_name')->comment('Giống hoa');

            // Plot & Vị trí
            $table->string('plot_code')->comment('Mã ô/Plot');
            $table->decimal('bag_weight', 8, 2)->comment('Cân nặng túi (Kg)');

            // QTY & Chất lượng
            $table->integer('qty_quantity')->default(0)->comment('Số lượng');

            // Đồng Đều
            $table->integer('uniformity_level')->default(0)->comment('Độ Đồng Đều');

            // URC Weight
            $table->decimal('urc_weight', 8, 2)->default(0)->comment('Trọng lượng URC');

            // Ngắn
            $table->integer('legit_height')->default(0)->comment('Chiều cao ngắn');

            // Hư hỏng
            $table->integer('damage_count')->default(0)->comment('Số hư hỏng');

            // Các loại lỗi
            $table->integer('leaf_yellow_spot')->default(0)->comment('Vàng đốm lá');
            $table->integer('leaf_yellow_vein')->default(0)->comment('Vàng gân lá');
            $table->integer('wooden_spot')->default(0)->comment('Nốt gỗ');
            $table->integer('dirty_damage')->default(0)->comment('Bẩn, Kiếm');

            // Tổng lỗi
            $table->integer('total_label_defect')->default(0)->comment('Tổng nhãn lỗi');
            $table->integer('pest_disease')->default(0)->comment('Sâu bệnh');
            $table->integer('total_defect')->default(0)->comment('Tổng lỗi');

            // User & timestamps
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('date');
            $table->index('greenhouse_id');
            $table->index('picker_code');
            $table->index('qc_name');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
