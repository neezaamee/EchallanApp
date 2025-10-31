<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCirclesTable extends Migration
{
    public function up()
    {
        Schema::create('circles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->onDelete('cascade'); // requires cities table exists
            $table->string('name');
            $table->string('slug')->nullable()->index();
            $table->timestamps();

            $table->unique(['city_id', 'name']); // prevents duplicate circle names for same city
        });
    }

    public function down()
    {
        Schema::dropIfExists('circles');
    }
}
