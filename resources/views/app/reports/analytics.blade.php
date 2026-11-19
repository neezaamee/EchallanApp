@extends('layouts.app')

@section('page-title', 'Reports & Analytics Dashboard - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    {{-- Banner Header --}}
    <div class="card mb-3 border shadow-none">
        <div class="card-body p-3">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h4 class="mb-1 text-dark fw-bold">
                        <i class="fas fa-chart-line text-primary me-2"></i>Executive Reports & Analytics Dashboard
                    </h4>
                    <p class="mb-0 text-muted fs--1">
                        Consolidated overview across <strong>Revenue &amp; Payments</strong>, <strong>Medical Examinations</strong>, <strong>Traffic Challans</strong>, and <strong>Warnings</strong>.
                    </p>
                </div>
                <div class="col-md-auto mt-3 mt-md-0">
                    <form method="GET" action="{{ route('reports.analytics') }}" class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-700">From</span>
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control" />
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-700">To</span>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-falcon-primary btn-sm">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('reports.analytics') }}" class="btn btn-falcon-default btn-sm" title="Reset to current month">
                            <i class="fas fa-undo"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Revenue & High Level KPI Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xxl-3">
            <x-falcon.statistic-card 
                :value="'PKR ' . number_format($totalRevenue)"
                label="Total Realized Revenue"
                icon="fas fa-coins"
                color="success"
            />
        </div>
        <div class="col-sm-6 col-xxl-3">
            <x-falcon.statistic-card 
                :value="number_format($totalMedical)"
                label="Medical Requests"
                icon="fas fa-notes-medical"
                color="info"
            />
        </div>
        <div class="col-sm-6 col-xxl-3">
            <x-falcon.statistic-card 
                :value="number_format($totalChallans)"
                label="Traffic Challans"
                icon="fas fa-file-invoice"
                color="primary"
            />
        </div>
        <div class="col-sm-6 col-xxl-3">
            <x-falcon.statistic-card 
                :value="number_format($totalWarnings)"
                label="Traffic Warnings Issued"
                icon="fas fa-exclamation-triangle"
                color="warning"
            />
        </div>
    </div>

    {{-- Module Quick-Jump Cards --}}
    <div class="row g-3 mb-3">
        {{-- Medical Requests Card --}}
        <div class="col-md-6 col-xxl-4">
            <div class="card h-100 border shadow-none">
                <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-900 fw-bold">
                        <i class="fas fa-user-md text-info me-2"></i>Medical Examinations
                    </h6>
                    @can('reports:medical')
                    <a href="{{ route('reports.medical') }}" class="btn btn-link btn-sm p-0 fs--1">
                        Detailed Report &rarr;
                    </a>
                    @endcan
                </div>
                <div class="card-body p-3">
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-success">{{ number_format($passedMedical) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold">Passed (Fit)</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-danger">{{ number_format($failedMedical) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold">Failed</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-warning">{{ number_format($pendingMedical) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold">Pending</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="fs--1 text-muted">Paid Applications:</span>
                        <span class="fs--1 fw-bold text-dark">{{ number_format($paidMedical) }} / {{ number_format($totalMedical) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="fs--1 text-muted">Total Medical Fees:</span>
                        <span class="fs--1 fw-bold text-success">PKR {{ number_format($medicalRevenue) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Traffic Challans Card --}}
        <div class="col-md-6 col-xxl-4">
            <div class="card h-100 border shadow-none">
                <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-900 fw-bold">
                        <i class="fas fa-traffic-light text-primary me-2"></i>Traffic Violations & Challans
                    </h6>
                    @canany(['reports:challans', 'reports:lifter-squad'])
                    <a href="{{ route('reports.challans') }}" class="btn btn-link btn-sm p-0 fs--1">
                        Detailed Report &rarr;
                    </a>
                    @endcanany
                </div>
                <div class="card-body p-3">
                    <div class="row text-center g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-primary">{{ number_format($carChallans) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold"><i class="fas fa-car me-1"></i>Cars</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-info">{{ number_format($bikeChallans) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold"><i class="fas fa-motorcycle me-1"></i>Motorcycles</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="fs--1 text-muted">Paid vs Unpaid Challans:</span>
                        <span class="fs--1 fw-bold text-dark">
                            <span class="text-success">{{ number_format($paidChallans) }} Paid</span> / 
                            <span class="text-danger">{{ number_format($pendingChallans) }} Pending</span>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="fs--1 text-muted">Realized Challan Fines:</span>
                        <span class="fs--1 fw-bold text-success">PKR {{ number_format($challanRevenue) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Warnings Card --}}
        <div class="col-md-12 col-xxl-4">
            <div class="card h-100 border shadow-none">
                <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-900 fw-bold">
                        <i class="fas fa-exclamation-circle text-warning me-2"></i>Traffic Warnings Summary
                    </h6>
                    @can('reports:warnings')
                    <a href="{{ route('reports.warnings') }}" class="btn btn-link btn-sm p-0 fs--1">
                        Detailed Report &rarr;
                    </a>
                    @endcan
                </div>
                <div class="card-body p-3">
                    <div class="row text-center g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-warning">{{ number_format($totalWarnings) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold">Total Notices</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <div class="fs-1 fw-bold text-dark">{{ number_format($uniqueViolators) }}</div>
                                <div class="fs--2 text-muted text-uppercase fw-semi-bold">Unique Violators</div>
                            </div>
                        </div>
                    </div>
                    <div class="fs--1 text-700 fw-semi-bold mb-2">Top Reported Infractions:</div>
                    @forelse($topViolations as $violation)
                        <div class="d-flex justify-content-between align-items-center py-1 border-top fs--2">
                            <span class="text-truncate me-2" style="max-width: 200px;">{{ $violation->violation_name }}</span>
                            <span class="badge badge-soft-warning">{{ number_format($violation->count) }}</span>
                        </div>
                    @empty
                        <div class="text-muted fs--2 py-2 border-top">No warnings recorded for this period.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Medical Centers Volume Table --}}
    <div class="card border shadow-none mb-3">
        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-900 fw-bold">
                <i class="fas fa-hospital me-2 text-primary"></i>Top Medical Centers by Activity
            </h6>
            @can('reports:medical')
            <a href="{{ route('reports.medical') }}" class="btn btn-falcon-default btn-sm">
                View All Medical Centers &rarr;
            </a>
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0 fs--1">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="ps-3">Medical Center</th>
                            <th>Total Examinations</th>
                            <th class="text-end pe-3">Revenue Collected</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topMedicalCenters as $center)
                            <tr>
                                <td class="ps-3 fw-semi-bold text-dark align-middle">{{ $center->name }}</td>
                                <td class="align-middle">{{ number_format($center->medical_requests_count) }}</td>
                                <td class="text-end pe-3 fw-bold text-success align-middle">
                                    PKR {{ number_format($center->medical_requests_sum_amount ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">No center activity recorded for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
