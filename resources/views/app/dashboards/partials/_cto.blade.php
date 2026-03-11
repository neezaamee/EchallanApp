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
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="mb-0">Recent Medical Requests ({{ $cityName ?? 'All' }})</h5>
                <a href="{{ route('medical-requests.index') }}" class="btn btn-sm btn-link px-0">View All <span class="fas fa-chevron-right ms-1 fs-11"></span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th>Citizen</th>
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
                                    <td class="align-middle white-space-nowrap">{{ $request->citizen->full_name ?? 'N/A' }}</td>
                                    <td class="align-middle white-space-nowrap">{{ $request->psid }}</td>
                                    <td class="align-middle white-space-nowrap">{{ $request->medicalCenter?->name ?? 'N/A' }}</td>
                                    <td class="align-middle white-space-nowrap">
                                        <span class="badge badge-soft-{{ $request->payment_status === 'paid' ? 'success' : 'danger' }} rounded-pill font-medium">
                                            {{ ucfirst($request->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap">
                                        <span class="badge badge-soft-{{ $request->status === 'passed' ? 'success' : ($request->status === 'failed' ? 'danger' : 'warning') }} rounded-pill font-medium">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap text-end">{{ $request->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No recent requests found in this region.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
