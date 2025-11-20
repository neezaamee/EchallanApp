<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CircleController extends Controller
{
    // index uses a blade that embeds Livewire component
    public function index()
    {
        return view('pages.infrastructure.circles.index'); // view should render <livewire:circles.circles-table />
    }

    public function create()
    {
        return view('pages.infrastructure.circles.create'); // view contains <livewire:circles.add-circle />
    }

    public function store(Request $request)
    {
        // we keep CRUD in Livewire components; optionally keep controller store if you want non-livewire
        abort(404);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return view('pages.infrastructure.circles.edit', compact('id')); // contains <livewire:circles.edit-circle :id="$id" />
    }

    public function update(Request $request, $id)
    {
        abort(404);
    }

    public function destroy($id)
    {
        abort(404);
    }
}
