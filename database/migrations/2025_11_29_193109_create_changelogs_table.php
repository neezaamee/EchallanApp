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
        Schema::create('changelogs', function (Blueprint $table) {
            $table->id();
            $table->string('version'); // e.g., "1.2.0"
            $table->date('release_date');
            $table->enum('type', ['added', 'changed', 'fixed', 'removed', 'security', 'deprecated']);
            $table->string('title');
            $table->text('description');
            $table->boolean('is_published')->default(false);
            $table->integer('order')->default(0); // For sorting within version
            $table->timestamps();

            // Index for faster queries
            $table->index(['version', 'order']);
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changelogs');
    }
};
