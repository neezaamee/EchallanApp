<div>
    {{-- Print CSS Styles --}}
    <style>
        @media print {
            .d-print-none { display: none !important; }
            .d-print-block { display: block !important; }
            body, .content { background: #fff !important; font-size: 11px !important; color: #000 !important; }
            .card { border: 1px solid #ccc !important; margin-bottom: 8px !important; box-shadow: none !important; }
            .card-header { padding: 4px 8px !important; background-color: #f8f9fa !important; border-bottom: 1px solid #ddd !important; }
            .card-body { padding: 6px !important; }
            .table-sm th, .table-sm td { padding: 3px 5px !important; font-size: 10px !important; color: #000 !important; }
            .badge { border: 1px solid #666 !important; font-size: 9px !important; padding: 2px 4px !important; color: #000 !important; background: transparent !important; }
            .badge-primary, .badge-soft-primary, .bg-primary { background-color: #e2e8f0 !important; color: #000 !important; }
        }
    </style>

    {{-- Screen Banner Header --}}
    <div class="card mb-3 border shadow-none d-print-none">
        <div class="card-body p-3">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h4 class="mb-1 text-dark fw-bold">
                        <i class="fas fa-chart-bar text-primary me-2"></i>Warnings Summary & Analytics Report
                    </h4>
                    <p class="mb-0 text-muted fs--1">
                        Hierarchical breakdown of warnings issued by <strong>Circle &rarr; Sector &rarr; Officer</strong> for specified date ranges.
                    </p>
                </div>
                <div class="col-md-auto mt-3 mt-md-0">
                    <button type="button" onclick="window.print()" class="btn btn-falcon-default btn-sm me-2">
                        <i class="fas fa-print me-1"></i> Print Report
                    </button>
                    <button type="button" wire:click="resetFilters" class="btn btn-falcon-secondary btn-sm">
                        <i class="fas fa-undo me-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Print Only Header --}}
    <div class="d-none d-print-block mb-3 border-bottom pb-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Traffic Warnings Summary Report</h3>
                <div class="fs--1 text-dark">
                    Date Period: <strong>{{ \Carbon\Carbon::parse($date_from)->format('d M, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($date_to)->format('d M, Y') }}</strong>
                </div>
            </div>
            <div class="text-end fs--2 text-muted">
                Generated: {{ now()->format('d M, Y H:i A') }}
            </div>
        </div>
    </div>

    {{-- View Mode Switch --}}
    <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" 
                wire:click="setViewMode('summary')" 
                class="btn {{ $view_mode === 'summary' ? 'btn-primary' : 'btn-falcon-default' }}">
                <i class="fas fa-sitemap me-1"></i> Hierarchical Summary (Circle &rarr; Sector &rarr; Officer)
            </button>
            <button type="button" 
                wire:click="setViewMode('detailed')" 
                class="btn {{ $view_mode === 'detailed' ? 'btn-primary' : 'btn-falcon-default' }}">
                <i class="fas fa-list me-1"></i> Detailed Log History
            </button>
        </div>
        <div class="text-muted fs--2">
            Date Range: <strong>{{ \Carbon\Carbon::parse($date_from)->format('d M, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($date_to)->format('d M, Y') }}</strong>
        </div>
    </div>

    {{-- KPI Metric Summary Cards --}}
    <div class="row g-3 mb-3 d-print-none">
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($totalWarnings)"
                label="Total Warnings Issued"
                icon="fas fa-exclamation-triangle"
                color="warning"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($uniqueViolators)"
                label="Unique Violators (CNIC)"
                icon="fas fa-users"
                color="info"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="$topViolation"
                label="Top Violation Category"
                icon="fas fa-exclamation-circle"
                color="danger"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="$activeSectorsCount"
                label="Active Sectors Reporting"
                icon="fas fa-map-marked-alt"
                color="success"
            />
        </div>
    </div>

    {{-- Search & Filters Panel (HIDDEN IN PRINT) --}}
    <x-falcon.card title="Report Parameters & Date Range" class="mb-3 d-print-none">
        <div class="row g-3 align-items-end">
            {{-- Date From --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">From Date</label>
                <input type="date" wire:model.live="date_from" class="form-control form-control-sm" />
            </div>

            {{-- Date To --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">To Date</label>
                <input type="date" wire:model.live="date_to" class="form-control form-control-sm" />
            </div>

            {{-- Circle Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Circle Filter</label>
                <select wire:model.live="circle_id" class="form-select form-select-sm">
                    <option value="">All Circles</option>
                    @foreach($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sector Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Sector Filter</label>
                <select wire:model.live="sector_id" class="form-select form-select-sm" {{ empty($circle_id) && $sectors->isEmpty() ? 'disabled' : '' }}>
                    <option value="">All Sectors</option>
                    @foreach($sectors as $sector)
                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Vehicle Type Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Vehicle Type</label>
                <select wire:model.live="vehicle_type" class="form-select form-select-sm">
                    <option value="">All Vehicle Types</option>
                    @foreach($vehicleTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Officer Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Specific Officer</label>
                <select wire:model.live="officer_id" class="form-select form-select-sm">
                    <option value="">All Officers</option>
                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Search Box --}}
            <div class="col-md-4 col-sm-8">
                <label class="form-label fs--1 fw-semi-bold text-700">Keyword Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search Violator, CNIC, Vehicle #..." />
            </div>

            {{-- Clear Filters --}}
            <div class="col-md-2 col-sm-4 text-end">
                <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-sync-alt me-1"></i> Reset
                </button>
            </div>
        </div>
    </x-falcon.card>

    {{-- VIEW MODE 1: HIERARCHICAL SUMMARY VIEW --}}
    @if ($view_mode === 'summary')
        @if (empty($hierarchicalData))
            <div class="card p-5 text-center text-muted border shadow-none">
                <i class="fas fa-chart-pie fa-3x mb-3 text-300"></i>
                <h5 class="text-700 fw-semi-bold">No Warnings Found for Selected Period</h5>
                <p class="fs--1 mb-0">Try adjusting your date range or clearing specific Circle/Sector search filters.</p>
            </div>
        @else
            <div class="summary-container">
                @foreach ($hierarchicalData as $cIndex => $circleData)
                    <div class="card mb-3 border shadow-none">
                        {{-- Circle Line Header --}}
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 text-primary fw-bold fs-0">
                                    <i class="fas fa-building me-2"></i>{{ ucfirst($circleData['circle_name']) }}
                                </h5>
                                <div class="text-muted fs--2 mt-1">
                                    <strong>{{ $circleData['total_sectors'] }}</strong> Active Sector(s) &bull; <strong>{{ $circleData['total_officers'] }}</strong> Officer(s) Active
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-primary text-white fs--1">
                                    Circle Total: {{ number_format($circleData['total']) }} Warnings
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-2">
                            {{-- Sectors under Circle --}}
                            @foreach ($circleData['sectors'] as $sIndex => $sectorData)
                                <div class="border rounded p-2 mb-2 bg-white">
                                    {{-- Sector Line Header --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt text-info me-2"></i>
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold fs--1">
                                                    {{  ucfirst($sectorData['sector_name']) }}
                                                </h6>
                                                <span class="text-muted fs--2">
                                                    {{ $sectorData['total_officers'] }} Officer(s)
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge badge-soft-info text-dark fs--2 fw-bold">
                                                Sector Total: {{ number_format($sectorData['total']) }} Warnings
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Compact Officer Table under Sector --}}
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped align-middle mb-0">
                                            <thead class="bg-200 text-900 fs--2">
                                                <tr>
                                                    <th class="ps-2 py-1 text-center" style="width: 35px;">#</th>
                                                    <th class="py-1">Officer Name</th>
                                                    <th class="text-center py-1" style="width: 130px;">Total Warnings</th>
                                                    <th class="text-center py-1" style="width: 110px;">Motorcycles</th>
                                                    <th class="text-center py-1" style="width: 90px;">Cars</th>
                                                    <th class="text-center py-1" style="width: 90px;">Others</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs--2">
                                                @foreach ($sectorData['officers'] as $oIndex => $officer)
                                                    <tr>
                                                        <td class="ps-2 text-center text-muted fw-semi-bold py-1">{{ $oIndex + 1 }}</td>
                                                        <td class="py-1">
                                                            <div class="fw-bold text-dark fs--1">{{ ucwords($officer['officer_name']) }}</div>
                                                            @if($officer['officer_email'])
                                                                <div class="text-muted fs--2 d-print-none">{{ strtolower($officer['officer_email']) }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center py-1">
                                                            <span class="badge bg-soft-primary text-primary fw-bold fs--1">
                                                                {{ number_format($officer['total']) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center py-1 text-dark font-mono">{{ number_format($officer['motorcycles']) }}</td>
                                                        <td class="text-center py-1 text-dark font-mono">{{ number_format($officer['cars']) }}</td>
                                                        <td class="text-center py-1 text-dark font-mono">{{ number_format($officer['others']) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    {{-- VIEW MODE 2: DETAILED LOG LIST --}}
    @else
        <x-falcon.card title="Warnings Detailed History List" bodyClass="p-0">
            <x-slot name="headerActions">
                <span class="badge badge-soft-primary fs--1">
                    Showing {{ $warnings ? $warnings->total() : 0 }} Record(s)
                </span>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Date & Time</th>
                        <th>Violator Details</th>
                        <th>Vehicle</th>
                        <th>Violation</th>
                        <th>Circle / Sector</th>
                        <th>Location</th>
                        <th>Officer</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warnings as $index => $warning)
                        <tr>
                            <td class="ps-3 text-muted fs--2">{{ $warnings->firstItem() + $index }}</td>
                            <td class="text-muted fs--2 text-nowrap">
                                {{ $warning->created_at->format('d M, Y') }}<br>
                                <span class="text-500">{{ $warning->created_at->format('H:i A') }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs--1">{{ $warning->violator_name }}</div>
                                <div class="text-muted fs--2">CNIC: {{ $warning->violator_cnic }}</div>
                                @if($warning->violator_mobile)
                                    <div class="text-muted fs--2"><i class="fas fa-phone-alt me-1"></i>{{ $warning->violator_mobile }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-soft-info fs--2">{{ strtoupper($warning->vehicle_number) }}</span>
                                <div class="text-muted fs--2 mt-1">{{ ucfirst($warning->vehicle_type) }}</div>
                            </td>
                            <td>
                                <span class="fw-semi-bold text-danger">{{ $warning->violation_name }}</span>
                                @if($warning->remarks)
                                    <div class="text-muted fs--2 italic">{{ Str::limit($warning->remarks, 40) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semi-bold text-dark fs--2">{{ $warning->circle->name ?? '—' }}</div>
                                <div class="text-muted fs--2">{{ $warning->sector->name ?? '—' }}</div>
                            </td>
                            <td class="text-800 fs--2">{{ $warning->location ?? '—' }}</td>
                            <td>
                                <div class="fw-semi-bold fs--2 text-dark">{{ $warning->officer->name ?? 'System' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-4 text-muted">
                                <i class="fas fa-search fa-2x mb-2 text-300 d-block"></i>
                                No warning records found matching the specified report criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>

            <x-slot name="footer">
                <div class="d-print-none">
                    @if($warnings)
                        <x-falcon.pagination>
                            {{ $warnings->links() }}
                        </x-falcon.pagination>
                    @endif
                </div>
            </x-slot>
        </x-falcon.card>
    @endif
</div>
