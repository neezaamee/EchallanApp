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
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            
            // Officer Info
            $table->foreignId('officer_id')->constrained('users'); // The user who issued the warning
            
            // Violator Info
            $table->string('violator_name');
            $table->string('violator_cnic');
            $table->string('violator_mobile')->nullable();
            
            // Vehicle Info
            $table->string('vehicle_type'); // e.g., 'motorcycle', 'car'
            $table->string('vehicle_number');
            
            // Violation Info
            $table->string('violation_name'); // e.g., 'Wrong Parking'
            $table->text('location')->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warnings');
    }
};
