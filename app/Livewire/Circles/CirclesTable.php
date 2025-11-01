<?php

namespace App\Livewire\Circles;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Circle;
use App\Models\City;

class CirclesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $confirmingCircleDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['circle-added' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingCircleDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteCircle()
    {
        if (!$this->deleteId) return;

        $circle = Circle::find($this->deleteId);
        if ($circle) $circle->delete();

        $this->reset(['confirmingCircleDeletion', 'deleteId']);
        session()->flash('message', 'Circle deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $query = Circle::with('city')
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where('name', 'like', $s)
                  ->orWhere('slug', 'like', $s)
                  ->orWhereHas('city', function ($q2) use ($s) {
                      $q2->where('name', 'like', $s);
                  });
            });

        $circles = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // also useful to pass list of cities for filters etc.
        $cities = City::orderBy('name')->get();

        return view('livewire.circles.circles-table', compact('circles','cities'));
    }
}
