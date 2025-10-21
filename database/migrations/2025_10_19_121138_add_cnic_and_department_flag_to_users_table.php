<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // CNIC as unique identifier (username equivalent). allow null for seeded department users if you prefer,
            // but we'll make it nullable = false to enforce uniqueness for all
            $table->string('cnic')->unique()->after('name');
            // optional short username if you want; we will use cnic OR email for login
            $table->string('username')->nullable()->after('cnic');
            // quick flag: whether this is a department account (admin/officer/accountant) created by staff
            $table->boolean('is_department_user')->default(false)->after('remember_token');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cnic', 'username', 'is_department_user']);
        });
    }
};
