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
        Schema::create('medical_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained('citizens')->onDelete('cascade');
            $table->foreignId('medical_center_id')->constrained('medical_centers')->onDelete('cascade');
            $table->enum('status', ['pending', 'passed', 'failed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('psid')->unique();
            $table->decimal('amount', 10, 2)->default(0);
            $table->foreignId('doctor_action_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('doctor_action_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_requests');
    }
};
