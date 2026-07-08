<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait HasJurisdiction
{
    /**
     * Boot the trait and add the global scope.
     */
    protected static function bootHasJurisdiction()
    {
        static::addGlobalScope('jurisdiction', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();

                // Super Admins, Admins, and Citizens see global jurisdictions (all provinces/cities)
                if ($user->hasRole(['super_admin', 'admin', 'citizen'])) {
                    return;
                }

                // Definitive fix for infinite recursion:
                // Use DB facade to get staff_id to avoid triggering HasJurisdiction on the Staff model
                $staffId = \Illuminate\Support\Facades\DB::table('staff')
                    ->where('user_id', $user->id)
                    ->value('id');

                if (!$staffId) {
                    // Non-admins without a staff record should see nothing in management tables
                    $builder->whereRaw('1 = 0');
                    return;
                }

                $activePosting = \App\Models\StaffPosting::where('staff_id', $staffId)
                    ->where('status', 'active')
                    ->first();

                if (!$activePosting) {
                    // If no active posting, show nothing for non-admins
                    $builder->whereRaw('1 = 0');
                    return;
                }
                $model = $builder->getModel();
                $table = $model->getTable();

                // --- Jurisdictional Discovery for Curator (the one looking at the data) ---
                $curatorCityId = $activePosting->city_id;
                $curatorProvinceId = $activePosting->province_id;

                // If direct city_id is null, traverse relationships to find the city
                if (!$curatorCityId) {
                    if ($activePosting->circle_id) {
                        $curatorCityId = \Illuminate\Support\Facades\DB::table('circles')->where('id', $activePosting->circle_id)->value('city_id');
                    } elseif ($activePosting->sector_id) {
                        $circleId = \Illuminate\Support\Facades\DB::table('sectors')->where('id', $activePosting->sector_id)->value('circle_id');
                        $curatorCityId = \Illuminate\Support\Facades\DB::table('circles')->where('id', $circleId)->value('city_id');
                    } elseif ($activePosting->medical_center_id) {
                        $circleId = \Illuminate\Support\Facades\DB::table('medical_centers')->where('id', $activePosting->medical_center_id)->value('circle_id');
                        $curatorCityId = \Illuminate\Support\Facades\DB::table('circles')->where('id', $circleId)->value('city_id');
                    } elseif ($activePosting->dumping_point_id) {
                        $circleId = \Illuminate\Support\Facades\DB::table('dumping_points')->where('id', $activePosting->dumping_point_id)->value('circle_id');
                        $curatorCityId = \Illuminate\Support\Facades\DB::table('circles')->where('id', $circleId)->value('city_id');
                    }
                }

                // If direct province_id is null, traverse from city
                if (!$curatorProvinceId && $curatorCityId) {
                    $curatorProvinceId = \Illuminate\Support\Facades\DB::table('cities')->where('id', $curatorCityId)->value('province_id');
                }
                // --- End Jurisdictional Discovery ---

                // 1. Direct city_id check (e.g., medical_centers, dumping_points)
                if (Schema::hasColumn($table, 'city_id') && $curatorCityId) {
                    $builder->where($table . '.city_id', $curatorCityId);
                    return;
                }

                // 2. Direct province_id check (e.g., cities)
                if (Schema::hasColumn($table, 'province_id') && $curatorProvinceId) {
                    $builder->where($table . '.province_id', $curatorProvinceId);
                    return;
                }

                // 3. Indirect check via circle relationship (Models that belong to a Circle)
                if (method_exists($model, 'circle') && $curatorCityId) {
                    $builder->whereHas('circle', function ($q) use ($curatorCityId) {
                        $q->where('city_id', $curatorCityId);
                    });
                    return;
                }

                // 4. Indirect check via medicalCenter relationship
                if (method_exists($model, 'medicalCenter') && $curatorCityId) {
                    $builder->whereHas('medicalCenter.circle', function ($q) use ($curatorCityId) {
                        $q->where('city_id', $curatorCityId);
                    });
                    return;
                }

                // 5. Indirect check via medicalRequest relationship (for Payments/Refunds)
                if (method_exists($model, 'medicalRequest') && $curatorCityId) {
                    $builder->whereHas('medicalRequest.medicalCenter.circle', function ($q) use ($curatorCityId) {
                        $q->where('city_id', $curatorCityId);
                    });
                    return;
                }

                // 6. Indirect check via activePosting relationship (for Staff model)
                if (method_exists($model, 'activePosting') && $curatorCityId) {
                    $builder->whereHas('activePosting', function ($q) use ($curatorCityId) {
                        $q->where(function($sub) use ($curatorCityId) {
                            $sub->where('city_id', $curatorCityId)
                                ->orWhereHas('circle', fn($c) => $c->where('city_id', $curatorCityId))
                                ->orWhereHas('medicalCenter.circle', fn($mc) => $mc->where('city_id', $curatorCityId))
                                ->orWhereHas('dumpingPoint.circle', fn($dp) => $dp->where('city_id', $curatorCityId));
                        });
                    });
                    return;
                } elseif (method_exists($model, 'activePosting') && $curatorProvinceId) {
                    $builder->whereHas('activePosting', function ($q) use ($curatorProvinceId) {
                        $q->where(function($sub) use ($curatorProvinceId) {
                            $sub->where('province_id', $curatorProvinceId)
                                ->orWhereHas('city', fn($ct) => $ct->where('province_id', $curatorProvinceId))
                                ->orWhereHas('circle.city', fn($c) => $c->where('province_id', $curatorProvinceId))
                                ->orWhereHas('medicalCenter.circle.city', fn($mc) => $mc->where('province_id', $curatorProvinceId))
                                ->orWhereHas('dumpingPoint.circle.city', fn($dp) => $dp->where('province_id', $curatorProvinceId));
                        });
                    });
                    return;
                }

                // 7. Direct Province model check (for viewing the Province model itself)
                if ($table === 'provinces' && $curatorProvinceId) {
                    $builder->where($table . '.id', $curatorProvinceId);
                    return;
                }
            }
        });
    }

    /**
     * Scope a query to only include specific jurisdiction manually if needed
     */
    public function scopeWithoutJurisdiction(Builder $builder)
    {
        return $builder->withoutGlobalScope('jurisdiction');
    }
}
