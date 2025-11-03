<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;

class EditProvince extends Component
{
    public $provinceId;
    public $name;
    public $code;

    protected $rules = [
        'name' => 'required|string|max:255|unique:provinces,name,{{provinceId}}',
        'code' => 'required|string|max:10|unique:provinces,code,{{provinceId}}',
    ];

    public function mount($id)
    {
        $province = Province::findOrFail($id);

        $this->provinceId = $province->id;
        $this->name = $province->name;
        $this->code = $province->code;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $this->provinceId,
            'code' => 'required|string|max:10|unique:provinces,code,' . $this->provinceId,
        ]);

        $province = Province::find($this->provinceId);
        $province->update([
            'name' => $this->name,
            'code' => $this->code,
        ]);

        session()->flash('message', 'Province updated successfully!');
        return redirect()->route('provinces.index');
    }

    public function render()
    {
        return view('livewire.edit-province');
    }
}
