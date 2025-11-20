<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use App\Models\Province;

class EditProvince extends Component
{
    public $provinceId;
    public $name;
    public $slug;

    protected $rules = [
        'name' => 'required|string|max:255|unique:provinces,name,{{provinceId}}',
        'code' => 'required|string|max:10|unique:provinces,code,{{provinceId}}',
    ];

    public function mount($id)
    {
        if (!Auth::user()->can('update provinces') && !Auth::user()->can('delete provinces')) {
        abort(403, 'Not allowed.');
    }
        $province = Province::findOrFail($id);

        $this->provinceId = $province->id;
        $this->name = $province->name;
        $this->slug = $province->slug;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $this->provinceId,
            'slug' => 'required|string|max:10|unique:provinces,slug,' . $this->provinceId,
        ]);

        $province = Province::find($this->provinceId);
        $province->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        session()->flash('message', 'Province updated successfully!');
        return redirect()->route('provinces.index');
    }

    public function render()
    {
        return view('livewire.edit-province');
    }
}
