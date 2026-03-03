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
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('family_id')->nullable()->after('id')->constrained('families')->nullOnDelete();
            $table->boolean('is_household_head')->default(false)->after('family_id');
            $table->boolean('is_primary_contact')->default(false)->after('is_household_head');

            $table->index('family_id');
            $table->index('is_household_head');
            $table->index('is_primary_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            $table->dropIndex(['family_id']);
            $table->dropIndex(['is_household_head']);
            $table->dropIndex(['is_primary_contact']);
            $table->dropColumn(['family_id', 'is_household_head', 'is_primary_contact']);
        });
    }
};
