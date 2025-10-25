<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            // optional link to users table (if you later create portal accounts for staff)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // personal profile
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('belt_no')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('cnic')->unique();

            // relations
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->foreignId('rank_id')->nullable()->constrained('ranks')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();

            // audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
