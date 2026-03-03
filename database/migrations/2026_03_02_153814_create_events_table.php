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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->comment('Tên sự kiện');
            $table->unsignedSmallInteger('event_year')->comment('Năm tổ chức');
            $table->date('event_date')->nullable()->comment('Ngày diễn ra dương lịch');
            $table->string('event_lunar_date')->nullable()->comment('Ngày diễn ra âm lịch');
            $table->string('event_type')->nullable()->comment('Loại sự kiện');
            $table->string('location')->nullable()->comment('Địa điểm tổ chức');
            $table->text('description')->nullable()->comment('Mô tả sự kiện');
            $table->boolean('is_annual')->default(true)->comment('Sự kiện lặp lại hằng năm');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming')->comment('Trạng thái sự kiện');
            $table->timestamps();
            $table->softDeletes();

            $table->index('event_year');
            $table->index('event_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
