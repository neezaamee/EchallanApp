<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;

class AddProvince extends Component
{
    public $name;
    public $code;

    protected $rules = [
        'name' => 'required|string|max:255|unique:provinces,name',
        'code' => 'nullable|string|max:10|unique:provinces,code',
    ];

    public function save()
    {
        $this->validate();

        Province::create([
            'name' => $this->name,
            'code' => $this->code,
        ]);

        session()->flash('message', 'Province added successfully!');
        $this->reset(['name', 'code']);
        $this->dispatch('provinceAdded'); // can be used to refresh the table if needed
    }

    public function render()
    {
        return view('livewire.add-province');
    }
}
