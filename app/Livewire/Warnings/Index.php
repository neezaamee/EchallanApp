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

    public function mount()
    {
        if (!Auth::user()->can('warnings:view')) {
            abort(403, 'Unauthorized action.');
        }
    }

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Warning::with('officer')
            ->where(function($q) {
                $q->where('violator_name', 'like', '%' . $this->search . '%')
                  ->orWhere('violator_cnic', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_number', 'like', '%' . $this->search . '%');
            })
            ->latest();

        // If not admin, only show own warnings
        if (!Auth::user()->hasRole('admin')) {
            $query->where('officer_id', Auth::id());
        }

        $warnings = $query->paginate(10);

        return view('livewire.warnings.index', [
            'warnings' => $warnings,
        ])->extends('layouts.app')->section('cms-main-content');
    }
}
