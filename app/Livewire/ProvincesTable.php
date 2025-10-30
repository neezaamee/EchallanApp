<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Province;

class ProvincesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $confirmingProvinceDeletion = null;

    protected $paginationTheme = 'bootstrap';

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
        $this->confirmingProvinceDeletion = $id;
    }
    public function deleteProvince()
    {
        $province = Province::find($this->confirmingProvinceDeletion);
        if ($province) {
            $province->delete();
        }

        $this->confirmingProvinceDeletion = null;
        session()->flash('message', 'Province deleted successfully!');
    }
    public function render()
    {
        $query = Province::query();

        if (trim($this->search) !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        $provinces = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.provinces-table', [
            'provinces' => $provinces,
        ]);
    }
}
