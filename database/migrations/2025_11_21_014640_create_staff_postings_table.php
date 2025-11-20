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
        Schema::create('staff_postings', function (Blueprint $table) {
            $table->id();

    $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();

    // every posting must belong to either city OR circle OR dumping point
    $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();
    $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
    $table->foreignId('circle_id')->nullable()->constrained('circles')->nullOnDelete();
    $table->foreignId('dumping_point_id')->nullable()->constrained('dumping_points')->nullOnDelete();

    $table->date('start_date')->default(now());
    $table->date('end_date')->nullable(); // null means still active

    $table->enum('status', ['active','inactive'])->default('active');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_postings');
    }
};
