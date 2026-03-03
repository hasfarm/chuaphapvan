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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->comment('Họ và tên');
            $table->string('dharma_name')->nullable()->comment('Pháp danh');
            $table->string('phone')->nullable()->comment('Điện thoại');
            $table->string('email')->nullable()->comment('Email');
            $table->date('solar_birth_date')->nullable()->comment('Ngày sinh dương lịch');
            $table->unsignedSmallInteger('solar_birth_year')->nullable()->comment('Năm sinh dương lịch');
            $table->string('lunar_birth_date')->nullable()->comment('Ngày sinh âm lịch (text)');
            $table->string('lunar_birth_year')->nullable()->comment('Năm sinh âm lịch');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->comment('Giới tính');
            $table->string('family_name')->nullable()->comment('Tên gia đình');
            $table->string('family_head_name')->nullable()->comment('Chủ hộ');
            $table->text('address')->nullable()->comment('Địa chỉ');
            $table->text('family_address')->nullable()->comment('Địa chỉ gia đình');
            $table->string('zodiac_info')->nullable()->comment('Tử vi');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Trạng thái');
            $table->timestamps();
            $table->softDeletes();

            $table->index('full_name');
            $table->index('phone');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
