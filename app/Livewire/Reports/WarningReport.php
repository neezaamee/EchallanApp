<?php

namespace App\Livewire\Reports;

use App\Models\Circle;
use App\Models\Sector;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class WarningReport extends Component
{
    use WithPagination;

    public $view_mode = 'summary'; // 'summary' or 'detailed'
    public $search = '';
    public $circle_id = '';
    public $sector_id = '';
    public $vehicle_type = '';
    public $officer_id = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 15;

    protected $queryString = [
        'view_mode' => ['except' => 'summary'],
        'search' => ['except' => ''],
        'circle_id' => ['except' => ''],
        'sector_id' => ['except' => ''],
        'vehicle_type' => ['except' => ''],
        'officer_id' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function mount()
    {
        if (!Auth::user()->can('reports:warnings') && !Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Default date_from to start of current month if not specified
        if (empty($this->date_from)) {
            $this->date_from = now()->startOfMonth()->toDateString();
        }
        if (empty($this->date_to)) {
            $this->date_to = now()->toDateString();
        }
    }

    public function updatedCircleId()
    {
        $this->sector_id = '';
        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        $this->view_mode = in_array($mode, ['summary', 'detailed']) ? $mode : 'summary';
        $this->resetPage();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSectorId() { $this->resetPage(); }
    public function updatingVehicleType() { $this->resetPage(); }
    public function updatingOfficerId() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'circle_id',
            'sector_id',
            'vehicle_type',
            'officer_id',
        ]);
        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to = now()->toDateString();
        $this->resetPage();
    }

    public function getHierarchicalData($baseQuery)
    {
        $records = (clone $baseQuery)->get();

        if ($records->isEmpty()) {
            return [];
        }

        $grouped = $records->groupBy(function ($w) {
            return $w->circle_id ? ('circle_' . $w->circle_id) : 'circle_none';
        });

        $result = [];

        foreach ($grouped as $circleKey => $circleWarnings) {
            $circleObj = $circleWarnings->first()->circle;
            $circleName = $circleObj ? $circleObj->name : 'Unassigned Circle';

            $sectorsGrouped = $circleWarnings->groupBy(function ($w) {
                return $w->sector_id ? ('sector_' . $w->sector_id) : 'sector_none';
            });

            $sectorsList = [];
            $circleOfficerIds = [];

            foreach ($sectorsGrouped as $sectorKey => $sectorWarnings) {
                $sectorObj = $sectorWarnings->first()->sector;
                $sectorName = $sectorObj ? $sectorObj->name : 'General / Unassigned Sector';

                $officersGrouped = $sectorWarnings->groupBy('officer_id');
                $officersList = [];

                foreach ($officersGrouped as $officerId => $officerWarnings) {
                    $circleOfficerIds[] = $officerId;
                    $officerObj = $officerWarnings->first()->officer;
                    $officerName = $officerObj ? $officerObj->name : 'Unknown Officer';
                    $officerEmail = $officerObj ? $officerObj->email : '';

                    $officersList[] = [
                        'officer_id' => $officerId,
                        'officer_name' => $officerName,
                        'officer_email' => $officerEmail,
                        'total' => $officerWarnings->count(),
                        'motorcycles' => $officerWarnings->filter(fn($w) => in_array(strtolower($w->vehicle_type), ['motorcycle', 'bike']))->count(),
                        'cars' => $officerWarnings->filter(fn($w) => strtolower($w->vehicle_type) === 'car')->count(),
                        'others' => $officerWarnings->reject(fn($w) => in_array(strtolower($w->vehicle_type), ['motorcycle', 'bike', 'car']))->count(),
                    ];
                }

                usort($officersList, fn($a, $b) => $b['total'] <=> $a['total']);

                $sectorsList[] = [
                    'sector_id' => $sectorObj ? $sectorObj->id : null,
                    'sector_name' => $sectorName,
                    'total' => $sectorWarnings->count(),
                    'total_officers' => count($officersList),
                    'officers' => $officersList,
                ];
            }

            usort($sectorsList, fn($a, $b) => $b['total'] <=> $a['total']);

            $result[] = [
                'circle_id' => $circleObj ? $circleObj->id : null,
                'circle_name' => $circleName,
                'total' => $circleWarnings->count(),
                'total_sectors' => count($sectorsList),
                'total_officers' => count(array_unique(array_filter($circleOfficerIds))),
                'sectors' => $sectorsList,
            ];
        }

        usort($result, fn($a, $b) => $b['total'] <=> $a['total']);

        return $result;
    }

    public function render()
    {
        $query = Warning::with(['officer', 'circle', 'sector']);

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('violator_name', 'like', $searchTerm)
                  ->orWhere('violator_cnic', 'like', $searchTerm)
                  ->orWhere('violator_mobile', 'like', $searchTerm)
                  ->orWhere('vehicle_number', 'like', $searchTerm)
                  ->orWhere('violation_name', 'like', $searchTerm)
                  ->orWhere('location', 'like', $searchTerm);
            });
        }

        if (!empty($this->circle_id)) {
            $query->where('circle_id', $this->circle_id);
        }

        if (!empty($this->sector_id)) {
            $query->where('sector_id', $this->sector_id);
        }

        if (!empty($this->vehicle_type)) {
            $query->where('vehicle_type', $this->vehicle_type);
        }

        if (!empty($this->officer_id)) {
            $query->where('officer_id', $this->officer_id);
        }

        if (!empty($this->date_from)) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if (!empty($this->date_to)) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        // Calculate KPI Summary Metrics
        $statsQuery = clone $query;
        $totalWarnings = $statsQuery->count();
        $uniqueViolators = (clone $query)->whereNotNull('violator_cnic')->distinct('violator_cnic')->count('violator_cnic');
        
        $topViolationRow = (clone $query)
            ->select('violation_name', DB::raw('count(*) as total'))
            ->groupBy('violation_name')
            ->orderByDesc('total')
            ->first();
        $topViolation = $topViolationRow ? $topViolationRow->violation_name : 'N/A';

        $activeSectorsCount = (clone $query)->whereNotNull('sector_id')->distinct('sector_id')->count('sector_id');

        // Dropdown options
        $circles = Circle::orderBy('name')->get();
        $sectors = !empty($this->circle_id)
            ? Sector::where('circle_id', $this->circle_id)->orderBy('name')->get()
            : Sector::orderBy('name')->get();

        $vehicleTypes = Warning::distinct()->whereNotNull('vehicle_type')->pluck('vehicle_type')->toArray();
        if (empty($vehicleTypes)) {
            $vehicleTypes = ['Motorcycle', 'Car', 'Rickshaw', 'LTV', 'HTV', 'Other'];
        }

        $officers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['warning_officer', 'admin', 'super_admin', 'lifter_challan_officer', 'duty_officer']);
        })->orWhereIn('id', function ($q) {
            $q->select('officer_id')->from('warnings');
        })->orderBy('name')->get();

        $hierarchicalData = $this->getHierarchicalData($query);

        $warnings = $this->view_mode === 'detailed' 
            ? (clone $query)->latest()->paginate($this->perPage)
            : null;

        return view('livewire.reports.warning-report', [
            'warnings' => $warnings,
            'hierarchicalData' => $hierarchicalData,
            'circles' => $circles,
            'sectors' => $sectors,
            'vehicleTypes' => $vehicleTypes,
            'officers' => $officers,
            'totalWarnings' => $totalWarnings,
            'uniqueViolators' => $uniqueViolators,
            'topViolation' => $topViolation,
            'activeSectorsCount' => $activeSectorsCount,
        ])->extends('layouts.app')->section('cms-main-content');
    }
}
