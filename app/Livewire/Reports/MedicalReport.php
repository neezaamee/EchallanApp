<?php

namespace App\Livewire\Reports;

use App\Models\MedicalCenter;
use App\Models\MedicalRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MedicalReport extends Component
{
    use WithPagination;

    public $view_mode = 'summary'; // 'summary' or 'detailed'
    public $search = '';
    public $medical_center_id = '';
    public $payment_status = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 15;

    protected $queryString = [
        'view_mode' => ['except' => 'summary'],
        'search' => ['except' => ''],
        'medical_center_id' => ['except' => ''],
        'payment_status' => ['except' => ''],
        'status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user->can('reports:medical') && !$user->hasRole(['super_admin', 'admin', 'cto'])) {
            abort(403, 'Unauthorized access to Medical Reports.');
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
        $this->reset(['search', 'medical_center_id', 'payment_status', 'status']);
        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to = now()->toDateString();
        $this->resetPage();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingMedicalCenterId() { $this->resetPage(); }
    public function updatingPaymentStatus() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function render()
    {
        $startDate = Carbon::parse($this->date_from)->startOfDay();
        $endDate = Carbon::parse($this->date_to)->endOfDay();

        $query = MedicalRequest::with(['citizen', 'medicalCenter', 'doctorActionBy'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (!empty($this->medical_center_id)) {
            $query->where('medical_center_id', $this->medical_center_id);
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
                $q->where('psid', 'like', "%{$s}%")
                  ->orWhereHas('citizen', function ($cq) use ($s) {
                      $cq->where('full_name', 'like', "%{$s}%")
                         ->orWhere('cnic', 'like', "%{$s}%")
                         ->orWhere('mobile_number', 'like', "%{$s}%");
                  });
            });
        }

        // Calculate KPI Metrics
        $totalRequests = (clone $query)->count();
        $totalRevenue = (float) (clone $query)->where('payment_status', 'paid')->sum('amount');
        $passedCount = (clone $query)->where('status', 'passed')->count();
        $failedCount = (clone $query)->where('status', 'failed')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $unpaidCount = (clone $query)->where('payment_status', 'unpaid')->count();

        // Medical Centers list for filter
        $medicalCenters = MedicalCenter::orderBy('name')->get();

        // Center-wise Summary
        $centerSummary = [];
        if ($this->view_mode === 'summary') {
            $centerSummary = MedicalCenter::withCount([
                'medicalRequests as total' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                },
                'medicalRequests as passed' => function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'passed')->whereBetween('created_at', [$startDate, $endDate]);
                },
                'medicalRequests as failed' => function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'failed')->whereBetween('created_at', [$startDate, $endDate]);
                },
                'medicalRequests as pending' => function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate]);
                },
                'medicalRequests as paid_count' => function ($q) use ($startDate, $endDate) {
                    $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
                },
            ])
            ->withSum([
                'medicalRequests as revenue' => function ($q) use ($startDate, $endDate) {
                    $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
                }
            ], 'amount')
            ->when(!empty($this->medical_center_id), function ($q) {
                $q->where('id', $this->medical_center_id);
            })
            ->get()
            ->filter(fn($cs) => $cs->total > 0);
        }

        $requests = $this->view_mode === 'detailed'
            ? (clone $query)->latest()->paginate($this->perPage)
            : null;

        return view('livewire.reports.medical-report', [
            'requests' => $requests,
            'centerSummary' => $centerSummary,
            'medicalCenters' => $medicalCenters,
            'totalRequests' => $totalRequests,
            'totalRevenue' => $totalRevenue,
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
            'pendingCount' => $pendingCount,
            'unpaidCount' => $unpaidCount,
        ])->extends('layouts.app')->section('cms-main-content');
    }
}
