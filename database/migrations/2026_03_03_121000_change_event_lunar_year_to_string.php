<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('events') || !Schema::hasColumn('events', 'event_lunar_year')) {
            return;
        }

        DB::statement("ALTER TABLE events MODIFY event_lunar_year VARCHAR(50) NULL COMMENT 'Năm diễn ra âm lịch (Can Chi)'");

        $can = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
        $chi = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

        $events = DB::table('events')
            ->select('id', 'event_lunar_year')
            ->whereNotNull('event_lunar_year')
            ->get();

        foreach ($events as $event) {
            $value = trim((string) $event->event_lunar_year);
            if ($value === '' || !preg_match('/^\d{4}$/', $value)) {
                continue;
            }

            $year = (int) $value;
            $canName = $can[($year + 6) % 10];
            $chiName = $chi[($year + 8) % 12];
            DB::table('events')->where('id', $event->id)->update([
                'event_lunar_year' => $canName . ' ' . $chiName,
            ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('events') || !Schema::hasColumn('events', 'event_lunar_year')) {
            return;
        }

        DB::statement("ALTER TABLE events MODIFY event_lunar_year SMALLINT UNSIGNED NULL COMMENT 'Năm diễn ra âm lịch'");
    }
};
