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
        Schema::table('warnings', function (Blueprint $table) {
            $table->foreignId('circle_id')->nullable()->after('officer_id')->constrained('circles')->nullOnDelete();
            $table->foreignId('sector_id')->nullable()->after('circle_id')->constrained('sectors')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warnings', function (Blueprint $table) {
            $table->dropForeign(['circle_id']);
            $table->dropForeign(['sector_id']);
            $table->dropColumn(['circle_id', 'sector_id']);
        });
    }
};
