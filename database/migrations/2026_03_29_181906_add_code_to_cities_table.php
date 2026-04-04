<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->string('code', 3)->nullable()->after('id')->index();
        });

        // Populate existing cities with padded IDs (e.g., 1 -> 001)
        $cities = DB::table('cities')->get();
        foreach ($cities as $city) {
            $code = str_pad($city->id, 3, '0', STR_PAD_LEFT);
            DB::table('cities')->where('id', $city->id)->update(['code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->dropColumn('code');
        });
    }
};
