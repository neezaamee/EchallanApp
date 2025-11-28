<?php

namespace App\Livewire\MedicalCenters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicalCenter;
use App\Models\Circle;
use App\Models\City;

class MedicalCentersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $confirmingMedicalCenterDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['medical-center-added' => 'render'];

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
        $this->confirmingMedicalCenterDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteMedicalCenter()
    {
        if (!$this->deleteId) return;

        $medicalCenter = MedicalCenter::find($this->deleteId);
        if ($medicalCenter) $medicalCenter->delete();

        $this->reset(['confirmingMedicalCenterDeletion', 'deleteId']);
        session()->flash('message', 'Medical Center deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $query = MedicalCenter::with(['circle.city'])
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where('name', 'like', $s)
                  ->orWhereHas('circle', function ($q2) use ($s) {
                      $q2->where('name', 'like', $s)
                         ->orWhereHas('city', function ($q3) use ($s) {
                             $q3->where('name', 'like', $s);
                         });
                  });
            });

        $medicalCenters = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.medical-centers.medical-centers-table', compact('medicalCenters'));
    }
}
