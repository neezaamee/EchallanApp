<div class="row g-3 mb-3">
    <div class="col-md-4 col-xxl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $todayWarnings ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Today's Warnings</h6>
                    </div>
                    <div class="fs-4 text-warning"><span class="fas fa-exclamation-triangle"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xxl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $totalWarnings ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Lifetime Issued</h6>
                    </div>
                    <div class="fs-4 text-info"><span class="fas fa-history"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xxl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">Active</h5>
                        <h6 class="text-700 mb-0">Patrol Status</h6>
                    </div>
                    <div class="fs-4 text-success"><span class="fas fa-shield-alt"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="card bg-soft-warning border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning-dark">Warning Officer Operations</h5>
                <p class="card-text">Manage your warning traffic enforcement operations here. You can issue warning slips to first-time violators to encourage road safety, or view the history of your issued warnings.</p>
                <div class="mt-3">
                    <a href="{{ route('warnings.create') }}" class="btn btn-warning text-white btn-sm">
                        <span class="fas fa-plus me-2"></span> Issue Warning
                    </a>
                    <a href="{{ route('warnings.index') }}" class="btn btn-outline-warning btn-sm ms-2">
                        <span class="fas fa-list me-2"></span> View Warning History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($recentWarnings) && $recentWarnings->count() > 0)
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Recent Warnings Issued by You</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Violator Name</th>
                        <th>CNIC</th>
                        <th>Vehicle</th>
                        <th>Violation Type</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentWarnings as $warning)
                        <tr>
                            <td>{{ $warning->created_at->format('d-M-Y h:i A') }}</td>
                            <td>{{ $warning->violator_name }}</td>
                            <td>{{ $warning->violator_cnic }}</td>
                            <td>
                                <span class="badge bg-secondary me-1">{{ strtoupper($warning->vehicle_type) }}</span>
                                <strong>{{ $warning->vehicle_number }}</strong>
                            </td>
                            <td><span class="text-danger">{{ $warning->violation_name }}</span></td>
                            <td>{{ $warning->location }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
