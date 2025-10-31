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

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

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
        $this->confirmingCityDeletion = $id;
    }

    public function deleteCity()
    {
        if ($this->confirmingCityDeletion === null) {
            return;
        }

        $city = City::find($this->confirmingCityDeletion);

        if ($city) {
            $city->delete();
            session()->flash('message', 'City deleted successfully!');
        } else {
            session()->flash('message', 'City not found.');
        }

        // reset modal state and optionally reset to first page
        $this->confirmingCityDeletion = null;
        $this->resetPage();
    }
    public function render()
    {
        $cities = City::with('province')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('province', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.cities-table', ['cities' => $cities]);
    }
}
