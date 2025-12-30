<?php

namespace App\Livewire\Staff;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Staff;

class StaffTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $confirmingStaffDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['staff-added' => 'render'];

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
        $this->confirmingStaffDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteStaff()
    {
        if (!$this->deleteId) return;

        $staff = Staff::find($this->deleteId);
        if ($staff) $staff->delete();

        $this->reset(['confirmingStaffDeletion', 'deleteId']);
        session()->flash('message', 'Staff deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $cityId = null;

        if (!$user->hasRole(['super_admin', 'admin'])) {
            $cityId = $user->staff?->activePosting?->city_id;
        }

        $query = Staff::with(['rank', 'city', 'province', 'roles', 'activePosting.province', 'activePosting.city', 'activePosting.circle', 'activePosting.dumpingPoint', 'activePosting.medicalCenter'])
            ->when($cityId, function ($q) use ($cityId) {
                $q->whereHas('activePosting', function ($q2) use ($cityId) {
                    $q2->where('city_id', $cityId);
                });
            })
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where('first_name', 'like', $s)
                  ->orWhere('last_name', 'like', $s)
                  ->orWhere('cnic', 'like', $s)
                  ->orWhere('email', 'like', $s)
                  ->orWhere('belt_no', 'like', $s);
            });

        $staff = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.staff.staff-table', compact('staff'));
    }
}
