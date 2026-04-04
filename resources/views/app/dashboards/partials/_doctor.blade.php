{{-- Medical Center Information --}}
@if ($medicalCenter ?? null)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-soft-primary">
                <div class="card-body">
                    <h5 class="mb-3 text-primary"><span class="fas fa-hospital me-2"></span>My Medical Center Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-0 fs-10 text-800"><strong>Name:</strong> {{ $medicalCenter->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-10 text-800"><strong>Location:</strong> {{ $medicalCenter->location ?? 'N/A' }}{{ $cityName ? ', ' . $cityName : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

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
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Medical Requests</h5>
                <a href="{{ route('medical-requests.index') }}" class="btn btn-link btn-sm px-0">View All <span class="fas fa-chevron-right ms-1 fs-11"></span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th>Citizen</th>
                                <th>PSID</th>
                                <th>Status</th>
                                <th class="text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests ?? [] as $request)
                                <tr>
                                    <td class="align-middle white-space-nowrap">{{ $request->citizen->full_name ?? 'N/A' }}</td>
                                    <td class="align-middle white-space-nowrap">{{ $request->psid }}</td>
                                    <td class="align-middle white-space-nowrap">
                                        <span class="badge badge-soft-{{ $request->status === 'passed' ? 'success' : ($request->status === 'failed' ? 'danger' : 'warning') }} rounded-pill font-medium">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle white-space-nowrap text-end">{{ $request->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No recent requests processed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
