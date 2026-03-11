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
        Schema::table('challans', function (Blueprint $table) {
            $table->timestamp('released_at')->nullable();
            $table->foreignId('released_by_staff_id')->nullable()->constrained('staff');
            $table->string('receiver_name')->nullable();
            $table->string('receiver_cnic')->nullable();
            $table->string('receiver_father_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challans', function (Blueprint $table) {
            $table->dropForeign(['released_by_staff_id']);
            $table->dropColumn([
                'released_at',
                'released_by_staff_id',
                'receiver_name',
                'receiver_cnic',
                'receiver_father_name'
            ]);
        });
    }
};
