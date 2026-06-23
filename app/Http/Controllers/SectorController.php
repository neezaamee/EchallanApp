<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function index()
    {
        return view('app.infrastructure.sectors.index');
    }

    public function create()
    {
        return view('app.infrastructure.sectors.create');
    }

    public function edit($id)
    {
        return view('app.infrastructure.sectors.edit', compact('id'));
    }
}
