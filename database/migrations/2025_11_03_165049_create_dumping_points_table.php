<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_dumping_points_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dumping_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('location')->nullable(); // Or lat/long, as you need

            // This is the important part!
            $table->foreignId('circle_id')
                  ->constrained('circles') // Assumes your circles table is 'circles'
                  ->onDelete('cascade'); // Deletes point if circle is deleted

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dumping_points');
    }
};
