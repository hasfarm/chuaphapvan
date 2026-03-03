<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_lunar_year')) {
                $table->unsignedSmallInteger('event_lunar_year')->nullable()->after('event_lunar_date')->comment('Năm diễn ra âm lịch');
                $table->index('event_lunar_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'event_lunar_year')) {
                $table->dropIndex(['event_lunar_year']);
                $table->dropColumn('event_lunar_year');
            }
        });
    }
};
