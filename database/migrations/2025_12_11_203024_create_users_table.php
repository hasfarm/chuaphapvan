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
        // Migration này sẽ thêm cột role_id vào bảng users
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột role_id sau cột id
            $table->foreignId('role_id')
                ->default(3)  // role_id = 3 cho user thường
                ->constrained('roles')
                ->onDelete('set default')
                ->onUpdate('cascade')
                ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
