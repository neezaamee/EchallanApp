<?php

namespace App\Livewire\Sectors;

use Livewire\Component;
use App\Models\Sector;
use App\Models\Circle;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EditSector extends Component
{
    public $sector_id;
    public $name;
    public $slug;
    public $circle_id;
    public $circles = [];

    public function mount($id)
    {
        $sector = Sector::findOrFail($id);

        $this->sector_id = $sector->id;
        $this->name = $sector->name;
        $this->slug = $sector->slug;
        $this->circle_id = $sector->circle_id;

        $this->circles = Circle::orderBy('name')->get();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sectors,slug,' . $this->sector_id,
            'circle_id' => 'required|exists:circles,id',
        ]);

        $sector = Sector::findOrFail($this->sector_id);
        $sector->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'circle_id' => $this->circle_id,
        ]);

        session()->flash('success', 'Sector updated successfully.');
        return redirect()->route('sectors.index');
    }

    public function render()
    {
        return view('livewire.sectors.edit-sector');
    }
}
