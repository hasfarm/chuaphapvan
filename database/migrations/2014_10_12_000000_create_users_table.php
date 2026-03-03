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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên đầy đủ của người dùng');
            $table->string('email')->unique()->comment('Email duy nhất');
            $table->timestamp('email_verified_at')->nullable()->comment('Thời gian xác thực email');
            $table->string('password')->comment('Mật khẩu đã hash');
            $table->string('phone')->nullable()->comment('Số điện thoại');
            $table->string('avatar')->nullable()->comment('Đường dẫn ảnh đại diện');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->comment('Trạng thái tài khoản');
            $table->boolean('is_verified')->default(false)->comment('Đã xác minh tài khoản');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable()->comment('Lần đăng nhập lần cuối');
            $table->string('last_login_ip')->nullable()->comment('IP đăng nhập lần cuối');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
