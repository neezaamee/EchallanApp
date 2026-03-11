<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalRequests ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Total Requests</h6>
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
                        <h6 class="text-700 mb-0">Approved</h6>
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Recent Medical Requests</h5>
                <div>
                   <a href="{{ route('medical-requests.create') }}" class="btn btn-sm btn-soft-success me-2">
                        <span class="fas fa-plus me-1"></span> New Request
                    </a>
                    <a href="{{ route('medical-requests.index') }}" class="btn btn-sm btn-link px-0">View All <span class="fas fa-chevron-right ms-1 fs-11"></span></a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th>PSID</th>
                                <th>Medical Center</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests ?? [] as $request)
                                <tr>
                                    <td class="align-middle white-space-nowrap"><strong>{{ $request->psid }}</strong></td>
                                    <td class="align-middle white-space-nowrap">{{ $request->medicalCenter?->name ?? 'N/A' }}</td>
                                    <td class="align-middle white-space-nowrap">
                                        <span class="badge badge-soft-{{ ($request->payment_status ?? '') === 'paid' ? 'success' : 'danger' }} rounded-pill">
                                            {{ ucfirst($request->payment_status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap">
                                        <span class="badge badge-soft-{{ ($request->status ?? '') === 'passed' ? 'success' : (($request->status ?? '') === 'failed' ? 'danger' : 'warning') }} rounded-pill">
                                            {{ ucfirst($request->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap text-end">{{ $request->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="mb-2">No requests found.</p>
                                        <a href="{{ route('medical-requests.create') }}" class="btn btn-primary btn-sm">Create your first request</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
