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
        Schema::create('payment_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // created, updated, refunded, cancelled, viewed
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('reason')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_audit_logs');
    }
};
