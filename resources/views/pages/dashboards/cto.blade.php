@extends('layout.cms-layout')
@section('page-title', 'CTO Dashboard')
@section('cms-main-content')
    <div class="row mb-3">
        <div class="col">
            <div class="card bg-100 shadow-none border">
                <div class="row gx-0 flex-between-center">
                    <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2"
                            src="{{ asset('assets/img/illustrations/crm-bar-chart.png') }}" alt="" width="90" />
                        <div>
                            <h6 class="text-primary fs-10 mb-0">Welcome to </h6>
                            <h4 class="text-primary fw-bold mb-0">
                                CTO
                                @if ($cityName ?? null)
                                    - {{ $cityName }}
                                @endif
                                <span class="text-danger fw-medium"> - </span><span class="text-info fw-medium"> Dashboard
                                    Welfare CMS</span>
                            </h4>
                        </div><img class="ms-n4 d-md-none d-lg-block"
                            src="{{ asset('assets/img/illustrations/crm-line-chart.png') }}" alt=""
                            width="150" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">{{ $pendingUnpaid ?? 0 }}</h5>
                            <h6 class="text-700 mb-0">Pending (Unpaid)</h6>
                        </div>
                        <div class="fs-4 text-warning"><span class="fas fa-clock"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">{{ $pendingPaid ?? 0 }}</h5>
                            <h6 class="text-700 mb-0">Actionable Requests</h6>
                        </div>
                        <div class="fs-4 text-info"><span class="fas fa-tasks"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">{{ $passedThisMonth ?? 0 }}</h5>
                            <h6 class="text-700 mb-0">Passed (This Month)</h6>
                        </div>
                        <div class="fs-4 text-success"><span class="fas fa-check-circle"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">{{ $failedThisMonth ?? 0 }}</h5>
                            <h6 class="text-700 mb-0">Failed (This Month)</h6>
                        </div>
                        <div class="fs-4 text-danger"><span class="fas fa-times-circle"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Medical Requests ({{ $cityName ?? 'All' }})</h5>
                    <a href="{{ route('medical-requests.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Citizen</th>
                                    <th>PSID</th>
                                    <th>Medical Center</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests ?? [] as $request)
                                    <tr>
                                        <td>{{ $request->citizen->full_name ?? 'N/A' }}</td>
                                        <td>{{ $request->psid }}</td>
                                        <td>{{ $request->medicalCenter?->name ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge badge-sm {{ $request->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($request->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-sm {{ $request->status === 'passed' ? 'bg-success' : ($request->status === 'failed' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">No requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
