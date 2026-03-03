<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'life_status')) {
                $table->enum('life_status', ['alive', 'deceased'])
                    ->default('alive')
                    ->after('gender')
                    ->comment('Tình trạng còn sống hay đã mất');
                $table->index('life_status');
            }

            if (!Schema::hasColumn('contacts', 'death_solar_date')) {
                $table->date('death_solar_date')
                    ->nullable()
                    ->after('life_status')
                    ->comment('Ngày mất dương lịch');
                $table->index('death_solar_date');
            }

            if (!Schema::hasColumn('contacts', 'death_lunar_date')) {
                $table->string('death_lunar_date')
                    ->nullable()
                    ->after('death_solar_date')
                    ->comment('Ngày mất âm lịch');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'death_solar_date')) {
                $table->dropIndex(['death_solar_date']);
            }

            if (Schema::hasColumn('contacts', 'life_status')) {
                $table->dropIndex(['life_status']);
            }

            $dropColumns = [];
            if (Schema::hasColumn('contacts', 'death_lunar_date')) {
                $dropColumns[] = 'death_lunar_date';
            }
            if (Schema::hasColumn('contacts', 'death_solar_date')) {
                $dropColumns[] = 'death_solar_date';
            }
            if (Schema::hasColumn('contacts', 'life_status')) {
                $dropColumns[] = 'life_status';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
