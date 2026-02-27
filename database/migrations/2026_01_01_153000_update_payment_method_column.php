<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip for SQLite (testing) because it doesn't support MODIFY COLUMN
        // and enum columns are already varchar in SQLite (just with check constraints).
        // Since we are not using 'cash' in the standard tests yet, this is fine.
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        // Change payment_method from ENUM to VARCHAR to support 'cash' and future methods
        // Using raw SQL to avoid doctrine/dbal dependency issues with enum changes
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'credit_card'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not revert this because 'cash' values would be truncated or cause errors
        // if we convert back to the restricted ENUM.
    }
};
