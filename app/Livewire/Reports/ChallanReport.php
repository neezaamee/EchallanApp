<?php

namespace App\Livewire\Reports;

use App\Models\Challan;
use App\Models\DumpingPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ChallanReport extends Component
{
    use WithPagination;

    public $view_mode = 'summary'; // 'summary' or 'detailed'
    public $search = '';
    public $vehicle_type = '';
    public $dumping_point_id = '';
    public $payment_status = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 15;

    protected $queryString = [
        'view_mode' => ['except' => 'summary'],
        'search' => ['except' => ''],
        'vehicle_type' => ['except' => ''],
        'dumping_point_id' => ['except' => ''],
        'payment_status' => ['except' => ''],
        'status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user->canany(['reports:challans', 'reports:lifter-squad']) && !$user->hasRole(['super_admin', 'admin', 'cto'])) {
            abort(403, 'Unauthorized access to Challans Report.');
        }

        if (empty($this->date_from)) {
            $this->date_from = now()->startOfMonth()->toDateString();
        }
        if (empty($this->date_to)) {
            $this->date_to = now()->toDateString();
        }
    }

    public function setViewMode($mode)
    {
        $this->view_mode = in_array($mode, ['summary', 'detailed']) ? $mode : 'summary';
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'vehicle_type', 'dumping_point_id', 'payment_status', 'status']);
        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to = now()->toDateString();
        $this->resetPage();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingVehicleType() { $this->resetPage(); }
    public function updatingDumpingPointId() { $this->resetPage(); }
    public function updatingPaymentStatus() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function render()
    {
        $startDate = Carbon::parse($this->date_from)->startOfDay();
        $endDate = Carbon::parse($this->date_to)->endOfDay();

        $query = Challan::with(['dumpingPoint', 'pickUpPoint', 'officer'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (!empty($this->vehicle_type)) {
            if ($this->vehicle_type === 'motorcycle') {
                $query->whereIn('vehicle_type', ['motorcycle', 'bike']);
            } else {
                $query->where('vehicle_type', $this->vehicle_type);
            }
        }

        if (!empty($this->dumping_point_id)) {
            $query->where('dumping_point_id', $this->dumping_point_id);
        }

        if (!empty($this->payment_status)) {
            $query->where('payment_status', $this->payment_status);
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->search)) {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('violator_name', 'like', "%{$s}%")
                  ->orWhere('violator_cnic', 'like', "%{$s}%")
                  ->orWhere('vehicle_number', 'like', "%{$s}%")
                  ->orWhere('psid', 'like', "%{$s}%")
                  ->orWhere('violation_name', 'like', "%{$s}%");
            });
        }

        // Calculate KPI Metrics
        $totalChallans = (clone $query)->count();
        $totalFines = (float) (clone $query)->sum('fine_amount');
        $paidRevenue = (float) (clone $query)->where('payment_status', 'paid')->sum('fine_amount');
        $paidCount = (clone $query)->where('payment_status', 'paid')->count();
        $pendingCount = (clone $query)->where('payment_status', '!=', 'paid')->count();
        $carCount = (clone $query)->where('vehicle_type', 'car')->count();
        $bikeCount = (clone $query)->whereIn('vehicle_type', ['motorcycle', 'bike'])->count();

        // Dumping Points list for filter
        $dumpingPoints = DumpingPoint::orderBy('name')->get();

        // Summary by Dumping Point
        $dumpingSummary = [];
        if ($this->view_mode === 'summary') {
            $dumpingSummary = DumpingPoint::withCount([
                'challans as total' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                },
                'challans as cars' => function ($q) use ($startDate, $endDate) {
                    $q->where('vehicle_type', 'car')->whereBetween('created_at', [$startDate, $endDate]);
                },
                'challans as bikes' => function ($q) use ($startDate, $endDate) {
                    $q->whereIn('vehicle_type', ['motorcycle', 'bike'])->whereBetween('created_at', [$startDate, $endDate]);
                },
                'challans as paid_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
                },
            ])
            ->withSum([
                'challans as total_fines' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            ], 'fine_amount')
            ->withSum([
                'challans as paid_fines' => function ($q) use ($startDate, $endDate) {
                    $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
                }
            ], 'fine_amount')
            ->when(!empty($this->dumping_point_id), function ($q) {
                $q->where('id', $this->dumping_point_id);
            })
            ->get()
            ->filter(fn($ds) => $ds->total > 0);
        }

        $challans = $this->view_mode === 'detailed'
            ? (clone $query)->latest()->paginate($this->perPage)
            : null;

        return view('livewire.reports.challan-report', [
            'challans' => $challans,
            'dumpingSummary' => $dumpingSummary,
            'dumpingPoints' => $dumpingPoints,
            'totalChallans' => $totalChallans,
            'totalFines' => $totalFines,
            'paidRevenue' => $paidRevenue,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'carCount' => $carCount,
            'bikeCount' => $bikeCount,
        ])->extends('layouts.app')->section('cms-main-content');
    }
}
