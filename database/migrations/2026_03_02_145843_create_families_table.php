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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_name')->comment('Tên gia đình');
            $table->string('family_code')->nullable()->unique()->comment('Mã gia đình');
            $table->string('head_name')->nullable()->comment('Chủ hộ');
            $table->string('phone')->nullable()->comment('Điện thoại');
            $table->string('email')->nullable()->comment('Email');
            $table->text('address')->nullable()->comment('Địa chỉ');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Trạng thái');
            $table->timestamps();
            $table->softDeletes();

            $table->index('family_name');
            $table->index('phone');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
