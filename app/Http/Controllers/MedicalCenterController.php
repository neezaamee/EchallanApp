<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalCenterController extends Controller
{
    public function index()
    {
        return view('pages.medical-centers.index');
    }

    public function create()
    {
        return view('pages.medical-centers.create');
    }

    public function edit(\App\Models\MedicalCenter $medicalCenter)
    {
        return view('pages.medical-centers.edit', compact('medicalCenter'));
    }
}
