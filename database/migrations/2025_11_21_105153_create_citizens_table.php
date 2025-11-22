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
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();

            // optional link to user account (if staff have portal access)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // personal info
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('cnic')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // job info
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();

            // status and soft delete support
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen');
    }
};
