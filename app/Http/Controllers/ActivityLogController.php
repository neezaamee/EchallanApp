<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::with(['causer', 'subject'])
            ->latest()
            ->paginate(20);

        return view('admin.logs.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        return view('admin.logs.show', compact('activity'));
    }
}
