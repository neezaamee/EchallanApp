<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        return view('app.infrastructure.cities.index');
    }
    public function create()
    {
        return view('app.infrastructure.cities.create');
    }

    public function edit($id)
    {
        return view('app.infrastructure.cities.edit', compact('id'));
    }
}
