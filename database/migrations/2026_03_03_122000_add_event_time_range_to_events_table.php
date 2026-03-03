<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_start_time')) {
                $table->time('event_start_time')->nullable()->after('event_date')->comment('Giờ bắt đầu sự kiện');
            }

            if (!Schema::hasColumn('events', 'event_end_time')) {
                $table->time('event_end_time')->nullable()->after('event_start_time')->comment('Giờ kết thúc sự kiện');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('events', 'event_end_time')) {
                $columnsToDrop[] = 'event_end_time';
            }

            if (Schema::hasColumn('events', 'event_start_time')) {
                $columnsToDrop[] = 'event_start_time';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
