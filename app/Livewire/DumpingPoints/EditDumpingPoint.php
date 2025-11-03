<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DumpingPoint;
use App\Models\Circle;

class EditDumpingPoint extends Component
{
    public $dumpingPointId;
    public $name;
    public $location;
    public $circle_id;
    public $circles;

    public function mount($id)
    {
        $this->dumpingPointId = $id;
        $dumpingPoint = DumpingPoint::findOrFail($id);

        $this->name = $dumpingPoint->name;
        $this->location = $dumpingPoint->location;
        $this->circle_id = $dumpingPoint->circle_id;

        $this->circles = Circle::all();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'circle_id' => 'required|exists:circles,id',
        ]);

        $dumpingPoint = DumpingPoint::findOrFail($this->dumpingPointId);
        $dumpingPoint->update([
            'name' => $this->name,
            'location' => $this->location,
            'circle_id' => $this->circle_id,
        ]);

        session()->flash('success', 'Dumping Point updated successfully!');
        return redirect()->route('dumping-points.index');
    }

    public function render()
    {
        return view('livewire.dumping-points.edit-dumping-point');
    }
}
