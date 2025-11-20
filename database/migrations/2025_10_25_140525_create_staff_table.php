<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            // optional link to user account (if staff have portal access)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // personal info
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('belt_no')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('cnic')->unique();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();

            // job info
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->foreignId('rank_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->string('photo')->nullable(); // store path of profile image

            // status and soft delete support
            $table->enum('status', ['active', 'inactive','suspended', 'retired', 'transferred_out'])->default('active');
            $table->softDeletes();

            // audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
