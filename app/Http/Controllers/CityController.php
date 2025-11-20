<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        return view('pages.infrastructure.cities.index');
    }
    public function create()
    {
        return view('pages.infrastructure.cities.create');
    }

    public function edit($id)
    {
        return view('pages.infrastructure.cities.edit', compact('id'));
    }
}
