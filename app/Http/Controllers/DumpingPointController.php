<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DumpingPointController extends Controller
{
    // Show the list page
    public function index()
    {
        return view('pages.infrastructure.dumping-points.index');
    }

    // Show the create form page
    public function create()
    {
        return view('pages.infrastructure.dumping-points.create');
    }

    // Show the edit form page, passing the specific point
    public function edit(DumpingPointController $dumpingPoint)
{
    return view('pages.infrastructure.dumping-points.edit', compact('dumpingPoint'));
}

}
