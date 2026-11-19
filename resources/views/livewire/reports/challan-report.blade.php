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
                        <i class="fas fa-file-invoice text-primary me-2"></i>Traffic Challans &amp; Impounded Vehicles Report
                    </h4>
                    <p class="mb-0 text-muted fs--1">
                        Comprehensive summary of electronic traffic challans, fine recovery, vehicle types, and dumping points.
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
                <h3 class="mb-0 fw-bold text-dark">Traffic Violations & Challans Report</h3>
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
                <i class="fas fa-warehouse me-1"></i> Dumping Point Summary
            </button>
            <button type="button" 
                wire:click="setViewMode('detailed')" 
                class="btn {{ $view_mode === 'detailed' ? 'btn-primary' : 'btn-falcon-default' }}">
                <i class="fas fa-list me-1"></i> Detailed Challan Log
            </button>
        </div>
        <div class="text-muted fs--2">
            Period: <strong>{{ \Carbon\Carbon::parse($date_from)->format('d M, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($date_to)->format('d M, Y') }}</strong>
        </div>
    </div>

    {{-- KPI Metric Summary Cards --}}
    <div class="row g-3 mb-3 d-print-none">
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($totalChallans)"
                label="Total Challans Issued"
                icon="fas fa-file-invoice"
                color="primary"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="'PKR ' . number_format($paidRevenue)"
                label="Realized Fine Recovery"
                icon="fas fa-coins"
                color="success"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($carCount) . ' / ' . number_format($bikeCount)"
                label="Cars / Motorcycles"
                icon="fas fa-traffic-light"
                color="info"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($pendingCount)"
                label="Unpaid / Pending Challans"
                icon="fas fa-clock"
                color="warning"
            />
        </div>
    </div>

    {{-- Filters Panel (Hidden in Print) --}}
    <x-falcon.card title="Report Parameters & Filters" class="mb-3 d-print-none">
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

            {{-- Vehicle Type --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Vehicle Type</label>
                <select wire:model.live="vehicle_type" class="form-select form-select-sm">
                    <option value="">All Vehicles</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                    <option value="other">Other</option>
                </select>
            </div>

            {{-- Dumping Point Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Dumping Point</label>
                <select wire:model.live="dumping_point_id" class="form-select form-select-sm">
                    <option value="">All Dumping Points</option>
                    @foreach($dumpingPoints as $dp)
                        <option value="{{ $dp->id }}">{{ $dp->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Status Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Payment Status</label>
                <select wire:model.live="payment_status" class="form-select form-select-sm">
                    <option value="">All Payment Statuses</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Challan Status</label>
                <select wire:model.live="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="released">Released</option>
                </select>
            </div>

            {{-- Search Box --}}
            <div class="col-md-4 col-sm-8">
                <label class="form-label fs--1 fw-semi-bold text-700">Keyword Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Violator Name, CNIC, Vehicle #, PSID..." />
            </div>

            {{-- Reset --}}
            <div class="col-md-2 col-sm-4 text-end">
                <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-sync-alt me-1"></i> Reset
                </button>
            </div>
        </div>
    </x-falcon.card>

    {{-- VIEW MODE 1: DUMPING POINT SUMMARY --}}
    @if ($view_mode === 'summary')
        <div class="card border shadow-none mb-3">
            <div class="card-header bg-light py-2 px-3">
                <h6 class="mb-0 text-900 fw-bold">
                    <i class="fas fa-warehouse me-2 text-primary"></i>Dumping Points Breakdown
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 fs--1">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="ps-3">Dumping Point</th>
                                <th class="text-center">Total Challans</th>
                                <th class="text-center">Cars</th>
                                <th class="text-center">Motorcycles</th>
                                <th class="text-center">Paid Challans</th>
                                <th class="text-end">Total Fine Assessed</th>
                                <th class="text-end pe-3">Paid Fine Recovered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dumpingSummary as $ds)
                                <tr>
                                    <td class="ps-3 align-middle fw-bold text-dark">{{ $ds->name }}</td>
                                    <td class="align-middle text-center">{{ number_format($ds->total) }}</td>
                                    <td class="align-middle text-center">{{ number_format($ds->cars) }}</td>
                                    <td class="align-middle text-center">{{ number_format($ds->bikes) }}</td>
                                    <td class="align-middle text-center text-success fw-semi-bold">{{ number_format($ds->paid_count) }}</td>
                                    <td class="align-middle text-end text-muted">
                                        PKR {{ number_format($ds->total_fines ?? 0) }}
                                    </td>
                                    <td class="align-middle text-end pe-3 fw-bold text-success">
                                        PKR {{ number_format($ds->paid_fines ?? 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No dumping point records found for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- VIEW MODE 2: DETAILED RECORDS --}}
    @if ($view_mode === 'detailed')
        <div class="card border shadow-none mb-3">
            <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-900 fw-bold">
                    <i class="fas fa-list me-2 text-primary"></i>Detailed Traffic Challans Log
                </h6>
                <div class="fs--2 text-muted">Showing {{ $challans->firstItem() ?? 0 }} - {{ $challans->lastItem() ?? 0 }} of {{ $challans->total() }}</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 fs--1">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Violator Name</th>
                                <th>Vehicle #</th>
                                <th>Vehicle Type</th>
                                <th>Violation</th>
                                <th>Dumping Point</th>
                                <th>PSID</th>
                                <th>Payment</th>
                                <th class="text-end pe-3">Fine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($challans as $ch)
                                <tr>
                                    <td class="ps-3 align-middle text-700">{{ $ch->created_at->format('d M, Y') }}</td>
                                    <td class="align-middle fw-bold text-dark">{{ $ch->violator_name }}</td>
                                    <td class="align-middle font-monospace text-primary fw-semi-bold">{{ $ch->vehicle_number }}</td>
                                    <td class="align-middle text-capitalize">{{ $ch->vehicle_type }}</td>
                                    <td class="align-middle text-truncate" style="max-width: 150px;">{{ $ch->violation_name }}</td>
                                    <td class="align-middle text-700">{{ $ch->dumpingPoint->name ?? 'N/A' }}</td>
                                    <td class="align-middle font-monospace text-primary fs--2">{{ $ch->psid }}</td>
                                    <td class="align-middle">
                                        @if($ch->payment_status === 'paid')
                                            <span class="badge badge-soft-success">Paid</span>
                                        @else
                                            <span class="badge badge-soft-warning">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-end pe-3 fw-bold text-dark">
                                        PKR {{ number_format($ch->fine_amount) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">No challan records match the selected criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($challans->hasPages())
                <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center d-print-none">
                    <div class="fs--2 text-muted">
                        Page {{ $challans->currentPage() }} of {{ $challans->lastPage() }}
                    </div>
                    <div>
                        {{ $challans->links() }}
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
