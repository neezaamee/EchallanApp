<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $warnings = DB::table('warnings')->whereNull('circle_id')->get();

        foreach ($warnings as $warning) {
            // Find the staff record for this officer
            $staff = DB::table('staff')->where('user_id', $warning->officer_id)->first();
            if (!$staff) {
                continue;
            }

            // Find the active or most recent posting
            $posting = DB::table('staff_postings')
                ->where('staff_id', $staff->id)
                ->orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END")
                ->orderBy('start_date', 'desc')
                ->first();

            if ($posting) {
                $circleId = $posting->circle_id;
                $sectorId = $posting->sector_id;

                // Resolve circle_id if it's null on the posting (e.g. posting is directly to sector/dumping point/medical center)
                if (!$circleId) {
                    if ($posting->sector_id) {
                        $circleId = DB::table('sectors')->where('id', $posting->sector_id)->value('circle_id');
                    } elseif ($posting->dumping_point_id) {
                        $circleId = DB::table('dumping_points')->where('id', $posting->dumping_point_id)->value('circle_id');
                    } elseif ($posting->medical_center_id) {
                        $circleId = DB::table('medical_centers')->where('id', $posting->medical_center_id)->value('circle_id');
                    }
                }

                DB::table('warnings')->where('id', $warning->id)->update([
                    'circle_id' => $circleId,
                    'sector_id' => $sectorId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data backfill doesn't need schema rollback
    }
};
