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
        Schema::create('audit_points_config', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->unique()->comment('Tên trường (leaf_burn_qty, yellow_spot_qty, v.v.)');
            $table->string('display_name')->comment('Tên hiển thị');
            $table->integer('points')->default(0)->comment('Điểm quy định cho mỗi đơn vị');
            $table->boolean('is_active')->default(true)->comment('Kích hoạt hay không');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_points_config');
    }
};
