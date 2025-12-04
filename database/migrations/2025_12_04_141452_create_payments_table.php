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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_request_id')->constrained('medical_requests')->onDelete('cascade');
            $table->string('psid')->index();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->unique();
            $table->enum('payment_method', ['credit_card', 'debit_card', 'bank_transfer', 'mobile_wallet'])->default('credit_card');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('payment_gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
