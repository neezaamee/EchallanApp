<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PickUpPointController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        if (!in_array($perPage, [20, 50, 100])) {
            $perPage = 50;
        }

        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['id', 'name', 'dumping_point_id', 'is_active', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $pickUpPoints = \App\Models\PickUpPoint::with('dumpingPoint')
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return view('app.infrastructure.pick-up-points.index', compact('pickUpPoints', 'perPage', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $dumpingPoints = \App\Models\DumpingPoint::all();
        return view('app.infrastructure.pick-up-points.create', compact('dumpingPoints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dumping_point_id' => 'required|exists:dumping_points,id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        \App\Models\PickUpPoint::create($request->all());

        return redirect()->route('pick-up-points.index')->with('success', 'Pick Up Point created successfully.');
    }

    public function edit(\App\Models\PickUpPoint $pickUpPoint)
    {
        $dumpingPoints = \App\Models\DumpingPoint::all();
        return view('app.infrastructure.pick-up-points.edit', compact('pickUpPoint', 'dumpingPoints'));
    }

    public function update(Request $request, \App\Models\PickUpPoint $pickUpPoint)
    {
        $request->validate([
            'dumping_point_id' => 'required|exists:dumping_points,id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $pickUpPoint->update($request->all());

        return redirect()->route('pick-up-points.index')->with('success', 'Pick Up Point updated successfully.');
    }

    public function destroy(\App\Models\PickUpPoint $pickUpPoint)
    {
        $pickUpPoint->delete();
        return back()->with('success', 'Pick Up Point deleted successfully.');
    }
}
