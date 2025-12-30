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
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $id,
            'code' => 'required|string|max:10|unique:provinces,code,' . $id,
        ]);

        $province = Province::findOrFail($id);
        $province->update($request->all());

        return redirect()->route('provinces.index')->with('success', 'Province updated successfully');
    }

    public function destroy($id)
    {
        $province = Province::findOrFail($id);
        $province->delete();

        return redirect()->route('provinces.index')->with('success', 'Province deleted successfully');
    }
}
