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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->unique()->after('status');
        });

        // Populate existing successful payments
        \Illuminate\Support\Facades\DB::table('payments')
            ->where('status', 'success')
            ->orderBy('id')
            ->chunkById(100, function ($payments) {
                foreach ($payments as $payment) {
                    $date = $payment->paid_at ? \Illuminate\Support\Carbon::parse($payment->paid_at) : \Illuminate\Support\Carbon::parse($payment->created_at);
                    $receiptNumber = 'RCP-' . $date->format('Ymd') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);
                    
                    \Illuminate\Support\Facades\DB::table('payments')
                        ->where('id', $payment->id)
                        ->update(['receipt_number' => $receiptNumber]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('receipt_number');
        });
    }
};
