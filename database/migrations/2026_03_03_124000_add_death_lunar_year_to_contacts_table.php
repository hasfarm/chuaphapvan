<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'death_lunar_year')) {
                $table->string('death_lunar_year')
                    ->nullable()
                    ->after('death_lunar_date')
                    ->comment('Năm mất âm lịch');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'death_lunar_year')) {
                $table->dropColumn('death_lunar_year');
            }
        });
    }
};
