<?php

namespace App\Http\Controllers;
use App\Models\Province;

use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(){
        $provinces = Province::all();
        return view('pages.infrastructure.provinces.index', compact('provinces'));
    }
    public function edit($id){
        return view('pages.infrastructure.provinces.edit', compact('id'));
    }
}
