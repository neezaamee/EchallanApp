<?php

namespace App\Livewire\Warnings;

use App\Models\Warning;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingWarningDeletion = null;
    public $deleteId = null;

    public function mount()
    {
        if (!Auth::user()->can('warnings:view')) {
            abort(403, 'Unauthorized action.');
        }
    }

    protected $queryString = ['search'];

    public function confirmDelete($id)
    {
        if (!Auth::user()->can('warnings:delete')) {
            abort(403, 'Unauthorized action.');
        }
        $this->confirmingWarningDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteWarning()
    {
        if (!Auth::user()->can('warnings:delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (!$this->deleteId) return;

        $warning = Warning::find($this->deleteId);
        if ($warning) {
            $warning->delete();
        }

        $this->reset(['confirmingWarningDeletion', 'deleteId']);
        session()->flash('message', 'Warning deleted successfully!');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Warning::with(['officer', 'circle', 'sector'])
            ->where(function($q) {
                $q->where('violator_name', 'like', '%' . $this->search . '%')
                  ->orWhere('violator_cnic', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_number', 'like', '%' . $this->search . '%');
            })
            ->latest();

        // If not super_admin or admin, only show own warnings
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            $query->where('officer_id', Auth::id());
        }

        $warnings = $query->paginate(10);

        return view('livewire.warnings.index', [
            'warnings' => $warnings,
        ])->extends('layouts.app')->section('cms-main-content');
    }
}
