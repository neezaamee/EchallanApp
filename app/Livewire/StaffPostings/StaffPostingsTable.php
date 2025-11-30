<?php

namespace App\Livewire\StaffPostings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StaffPosting;

class StaffPostingsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

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

    public function getPlaceOfPosting($posting)
    {
        if ($posting->medical_center_id) {
            return $posting->medicalCenter->name ?? '—';
        } elseif ($posting->dumping_point_id) {
            return $posting->dumpingPoint->name ?? '—';
        } elseif ($posting->circle_id) {
            return $posting->circle->name ?? '—';
        } elseif ($posting->city_id) {
            return $posting->city->name ?? '—';
        } elseif ($posting->province_id) {
            return $posting->province->name ?? '—';
        }
        return '—';
    }

    public function render()
    {
        $query = StaffPosting::with(['staff', 'province', 'city', 'circle', 'dumpingPoint', 'medicalCenter'])
            ->where('status', 'active')
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->whereHas('staff', function ($staffQuery) use ($s) {
                    $staffQuery->where('first_name', 'like', $s)
                        ->orWhere('last_name', 'like', $s)
                        ->orWhere('cnic', 'like', $s);
                });
            });

        $postings = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.staff-postings.staff-postings-table', compact('postings'));
    }
}
