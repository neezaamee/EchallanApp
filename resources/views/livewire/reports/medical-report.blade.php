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
                        <i class="fas fa-notes-medical text-primary me-2"></i>Medical Requests &amp; Fitness Examination Report
                    </h4>
                    <p class="mb-0 text-muted fs--1">
                        Comprehensive audit of citizen medical examinations, fitness outcomes, and revenue collected across medical centers.
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
                <h3 class="mb-0 fw-bold text-dark">Citizen Medical Examination Report</h3>
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
                <i class="fas fa-hospital me-1"></i> Center-Wise Summary
            </button>
            <button type="button" 
                wire:click="setViewMode('detailed')" 
                class="btn {{ $view_mode === 'detailed' ? 'btn-primary' : 'btn-falcon-default' }}">
                <i class="fas fa-list me-1"></i> Detailed Citizen Records
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
                :value="number_format($totalRequests)"
                label="Total Applications"
                icon="fas fa-notes-medical"
                color="primary"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="'PKR ' . number_format($totalRevenue)"
                label="Total Revenue Collected"
                icon="fas fa-coins"
                color="success"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($passedCount)"
                label="Declared Fit (Passed)"
                icon="fas fa-check-circle"
                color="info"
            />
        </div>
        <div class="col-sm-6 col-md-3">
            <x-falcon.statistic-card 
                :value="number_format($unpaidCount)"
                label="Unpaid Applications"
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

            {{-- Medical Center Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Medical Center</label>
                <select wire:model.live="medical_center_id" class="form-select form-select-sm">
                    <option value="">All Medical Centers</option>
                    @foreach($medicalCenters as $center)
                        <option value="{{ $center->id }}">{{ $center->name }}</option>
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

            {{-- Examination Status Filter --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fs--1 fw-semi-bold text-700">Examination Status</label>
                <select wire:model.live="status" class="form-select form-select-sm">
                    <option value="">All Examination Statuses</option>
                    <option value="passed">Passed (Fit)</option>
                    <option value="failed">Failed (Unfit)</option>
                    <option value="pending">Pending Examination</option>
                </select>
            </div>

            {{-- Search Box --}}
            <div class="col-md-6 col-sm-8">
                <label class="form-label fs--1 fw-semi-bold text-700">Keyword Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search Citizen Name, CNIC, PSID..." />
            </div>

            {{-- Reset --}}
            <div class="col-md-3 col-sm-4 text-end">
                <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-sync-alt me-1"></i> Reset Filters
                </button>
            </div>
        </div>
    </x-falcon.card>

    {{-- VIEW MODE 1: CENTER SUMMARY --}}
    @if ($view_mode === 'summary')
        <div class="card border shadow-none mb-3">
            <div class="card-header bg-light py-2 px-3">
                <h6 class="mb-0 text-900 fw-bold">
                    <i class="fas fa-hospital me-2 text-primary"></i>Medical Centers Performance Breakdown
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 fs--1">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="ps-3">Medical Center</th>
                                <th class="text-center">Total Requests</th>
                                <th class="text-center text-success">Passed (Fit)</th>
                                <th class="text-center text-danger">Failed</th>
                                <th class="text-center text-warning">Pending</th>
                                <th class="text-center">Paid Count</th>
                                <th class="text-end pe-3">Revenue Collected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($centerSummary as $cs)
                                <tr>
                                    <td class="ps-3 align-middle fw-bold text-dark">{{ $cs->name }}</td>
                                    <td class="align-middle text-center">{{ number_format($cs->total) }}</td>
                                    <td class="align-middle text-center text-success fw-semi-bold">{{ number_format($cs->passed) }}</td>
                                    <td class="align-middle text-center text-danger fw-semi-bold">{{ number_format($cs->failed) }}</td>
                                    <td class="align-middle text-center text-warning fw-semi-bold">{{ number_format($cs->pending) }}</td>
                                    <td class="align-middle text-center">{{ number_format($cs->paid_count) }}</td>
                                    <td class="align-middle text-end pe-3 fw-bold text-success">
                                        PKR {{ number_format($cs->revenue ?? 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No medical center records found for this period.</td>
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
                    <i class="fas fa-list me-2 text-primary"></i>Detailed Medical Examination Records
                </h6>
                <div class="fs--2 text-muted">Showing {{ $requests->firstItem() ?? 0 }} - {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }}</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 fs--1">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Citizen Name</th>
                                <th>CNIC</th>
                                <th>Medical Center</th>
                                <th>PSID</th>
                                <th>Payment</th>
                                <th>Exam Result</th>
                                <th class="text-end pe-3">Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td class="ps-3 align-middle text-700">{{ $req->created_at->format('d M, Y') }}</td>
                                    <td class="align-middle fw-bold text-dark">{{ $req->citizen->full_name ?? 'N/A' }}</td>
                                    <td class="align-middle font-monospace text-700">{{ $req->citizen->cnic ?? 'N/A' }}</td>
                                    <td class="align-middle text-700">{{ $req->medicalCenter->name ?? 'N/A' }}</td>
                                    <td class="align-middle font-monospace text-primary fs--2">{{ $req->psid }}</td>
                                    <td class="align-middle">
                                        @if($req->payment_status === 'paid')
                                            <span class="badge badge-soft-success">Paid</span>
                                        @else
                                            <span class="badge badge-soft-warning">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($req->status === 'passed')
                                            <span class="badge badge-soft-success">Fit</span>
                                        @elseif($req->status === 'failed')
                                            <span class="badge badge-soft-danger">Unfit</span>
                                        @else
                                            <span class="badge badge-soft-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-end pe-3 fw-bold text-dark">
                                        PKR {{ number_format($req->amount) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No medical records match the selected criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($requests->hasPages())
                <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center d-print-none">
                    <div class="fs--2 text-muted">
                        Page {{ $requests->currentPage() }} of {{ $requests->lastPage() }}
                    </div>
                    <div>
                        {{ $requests->links() }}
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
