<?php

namespace App\Livewire\Sectors;

use Livewire\Component;
use App\Models\Circle;
use App\Models\Sector;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AddSector extends Component
{
    public $name;
    public $slug;
    public $circle_id;
    public $circles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'circle_id' => 'required|exists:circles,id',
    ];

    public function mount()
    {
        $this->circles = Circle::orderBy('name')->get();
    }

    public function updatedName($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('sectors')->where(fn ($query) => $query->where('circle_id', $this->circle_id)),
            ],
            'circle_id' => 'required|exists:circles,id',
        ],
        [
            'name.unique' => 'A sector with this name already exists in the selected circle.',
        ]);

        Sector::create([
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'circle_id' => $this->circle_id,
        ]);

        session()->flash('message', 'Sector added successfully!');
        $this->reset(['name', 'slug', 'circle_id']);
        $this->dispatch('sector-added');
    }

    public function render()
    {
        return view('livewire.sectors.add-sector');
    }
}
