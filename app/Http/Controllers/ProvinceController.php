<?php

namespace App\Http\Controllers;
use App\Models\Province;

use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(){
        //$provinces = Province::all();
        return view('pages.infrastructure.provinces.index');
    }
    public function create()
    {
        return view('pages.infrastructure.provinces.create');
    }
    public function edit($id){
        return view('pages.infrastructure.provinces.edit', compact('id'));
    }
}
