<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalRequests ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Total Records</h6>
                    </div>
                    <div class="fs-4 text-primary"><span class="fas fa-file-medical"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $pendingRequests ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">In Progress</h6>
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
                        <h5 class="mb-1">{{ $approvedRequests ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Approved / Released</h6>
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
                        <h5 class="mb-1">{{ $unpaidRequests ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Unpaid Fees</h6>
                    </div>
                    <div class="fs-4 text-danger"><span class="fas fa-exclamation-circle"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Medical Requests Table --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Medical Requests</h5>
                <div>
                   <a href="{{ route('medical-requests.create') }}" class="btn btn-sm btn-soft-success me-2">
                        <span class="fas fa-plus me-1"></span> New
                    </a>
                    <a href="{{ route('medical-requests.index') }}" class="btn btn-sm btn-link px-0 text-secondary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th>PSID</th>
                                <th>Status</th>
                                <th class="text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests ?? [] as $request)
                                <tr>
                                    <td class="align-middle white-space-nowrap"><strong>{{ $request->psid }}</strong></td>
                                    <td class="align-middle white-space-nowrap">
                                        <span class="badge badge-soft-{{ ($request->payment_status ?? '') === 'paid' ? 'success' : 'danger' }} rounded-pill">
                                            {{ ucfirst($request->payment_status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap text-end text-600">{{ $request->created_at?->format('M d') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No medical requests.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Traffic Challans Table --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Traffic Challans</h5>
                <div>
                    <a href="{{ route('impound.check-status.form') }}" class="btn btn-sm btn-soft-primary me-2">
                        <span class="fas fa-search me-1"></span> Find
                    </a>
                    <a href="{{ route('challans.index') }}" class="btn btn-sm btn-link px-0 text-secondary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th>PSID</th>
                                <th>Vehicle</th>
                                <th>Payment</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentChallans ?? [] as $challan)
                                <tr>
                                    <td class="align-middle white-space-nowrap"><strong>{{ $challan->psid }}</strong></td>
                                    <td class="align-middle white-space-nowrap text-600">{{ strtoupper($challan->vehicle_number) }}</td>
                                    <td class="align-middle white-space-nowrap">
                                        <div class="fw-semi-bold text-900 fs-10">Rs. {{ number_format($challan->fine_amount) }}</div>
                                        <span class="badge badge-soft-{{ ($challan->payment_status ?? '') === 'paid' ? 'success' : 'danger' }} rounded-pill fs-11">
                                            {{ ucfirst($challan->payment_status ?? 'Unpaid') }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap text-end">
                                        <a href="{{ route('challans.show', $challan->id) }}" class="btn btn-xs btn-outline-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No traffic challans found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
