<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Challan;
use App\Models\Warning;
use Livewire\Attributes\On;

class ViolatorHistory extends Component
{
    public $challans = [];
    public $warnings = [];
    public $hasHistory = false;

    #[On('check-history')]
    public function checkHistory($cnic, $vehicle_number)
    {
        if (empty($cnic) && empty($vehicle_number)) {
            $this->resetHistory();
            return;
        }

        $challansQuery = Challan::query();
        $warningsQuery = Warning::query();

        if (!empty($cnic) && !empty($vehicle_number)) {
            $challansQuery->where(function($q) use ($cnic, $vehicle_number) {
                $q->where('violator_cnic', $cnic)->orWhere('vehicle_number', $vehicle_number);
            });
            $warningsQuery->where(function($q) use ($cnic, $vehicle_number) {
                $q->where('violator_cnic', $cnic)->orWhere('vehicle_number', $vehicle_number);
            });
        } elseif (!empty($cnic)) {
            $challansQuery->where('violator_cnic', $cnic);
            $warningsQuery->where('violator_cnic', $cnic);
        } else {
            $challansQuery->where('vehicle_number', $vehicle_number);
            $warningsQuery->where('vehicle_number', $vehicle_number);
        }

        $this->challans = $challansQuery->latest()->get();
        $this->warnings = $warningsQuery->latest()->get();

        $this->hasHistory = $this->challans->count() > 0 || $this->warnings->count() > 0;
    }

    private function resetHistory()
    {
        $this->challans = [];
        $this->warnings = [];
        $this->hasHistory = false;
    }

    public function render()
    {
        return view('livewire.violator-history');
    }
}
