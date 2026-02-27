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
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            
            // Officer Info
            $table->foreignId('officer_id')->constrained('users'); // The user who issued the challan
            
            // Location Info (Snapshot at time of issuance)
            $table->foreignId('dumping_point_id')->constrained();
            $table->foreignId('pick_up_point_id')->constrained();
            
            // Violator Info
            $table->string('violator_name');
            $table->string('violator_cnic');
            $table->string('violator_mobile');
            
            // Vehicle Info
            $table->string('vehicle_type'); // e.g., 'motorcycle', 'car'
            $table->string('vehicle_number');
            
            // Violation Info
            $table->string('violation_name'); // e.g., 'Wrong Parking'
            $table->decimal('fine_amount', 10, 2);
            
            // Status Info
            $table->string('status')->default('pending'); // pending, paid, released
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            
            // Payment Proof
            $table->string('transaction_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challans');
    }
};
