<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\City;
use App\Models\Province;

class CitiesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $confirmingCityDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    // ✅ Keep pagination in sync with search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // ✅ Sorting logic
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // ✅ Handle delete confirmation
    public function confirmDelete($id)
    {
        $this->confirmingCityDeletion = true; // show modal
        $this->deleteId = $id;
    }

    // ✅ Perform deletion
    public function deleteCity()
    {
        if (!$this->deleteId) {
            return;
        }

        $city = City::find($this->deleteId);
        if ($city) {
            $city->delete();
        }

        $this->reset(['confirmingCityDeletion', 'deleteId']);
        session()->flash('message', 'City deleted successfully!');
        $this->resetPage(); // refresh table after deletion
    }

    // ✅ Query builder with safe grouping
    public function render()
    {
        $cities = City::with('province')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('province', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.cities-table', compact('cities'));
    }
}
