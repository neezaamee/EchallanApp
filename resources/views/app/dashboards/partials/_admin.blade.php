<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalCenters ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Medical Centers</h6>
                    </div>
                    <div class="fs-4 text-primary"><span class="fas fa-hospital"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalStaff ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Total Staff</h6>
                    </div>
                    <div class="fs-4 text-info"><span class="fas fa-user-tie"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalRequests ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Medical Requests</h6>
                    </div>
                    <div class="fs-4 text-warning"><span class="fas fa-file-medical"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">Active</h5>
                        <h6 class="text-700 mb-0">System Status</h6>
                    </div>
                    <div class="fs-4 text-success"><span class="fas fa-check-circle"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Activity Logs</h5>
                <a class="btn btn-link btn-sm px-0" href="{{ url('/admin/activity-logs') }}">View All <span class="fas fa-chevron-right ms-1 fs-11"></span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th class="white-space-nowrap">Description</th>
                                <th class="white-space-nowrap">User</th>
                                <th class="white-space-nowrap text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs ?? [] as $log)
                                <tr>
                                    <td class="align-middle white-space-nowrap">{{ $log->description }}</td>
                                    <td class="align-middle white-space-nowrap">{{ $log->causer?->name ?? 'System' }}</td>
                                    <td class="align-middle white-space-nowrap text-end">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">No recent logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('medical-centers.create') }}" class="btn btn-soft-primary btn-sm text-start">
                        <span class="fas fa-plus me-2"></span> Add Medical Center
                    </a>
                    <a href="{{ route('staff.create') }}" class="btn btn-soft-info btn-sm text-start">
                        <span class="fas fa-user-plus me-2"></span> Register New Staff
                    </a>
                    <a href="{{ route('cities.index') }}" class="btn btn-soft-warning btn-sm text-start">
                        <span class="fas fa-map-marker-alt me-2"></span> Manage Regions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
