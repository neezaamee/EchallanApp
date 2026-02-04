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
        Schema::create('pick_up_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dumping_point_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('location')->nullable(); // description or coordinates
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pick_up_points');
    }
};
