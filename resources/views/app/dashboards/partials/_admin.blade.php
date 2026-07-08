<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalCenters ?? 0 }}"
            label="Medical Centers"
            icon="fas fa-hospital"
            color="primary"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalStaff ?? 0 }}"
            label="Total Staff"
            icon="fas fa-user-tie"
            color="info"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalRequests ?? 0 }}"
            label="Medical Requests"
            icon="fas fa-file-medical"
            color="warning"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="Active"
            label="System Status"
            icon="fas fa-check-circle"
            color="success"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <x-falcon.card title="Recent Activity Logs" bodyClass="p-0">
            <x-slot name="headerActions">
                <a class="btn btn-link btn-sm px-0 fw-bold" href="{{ url('/activity-logs') }}">
                    View All <span class="fas fa-chevron-right ms-1 fs--2"></span>
                </a>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Description</th>
                        <th>User</th>
                        <th class="text-end pe-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs ?? [] as $log)
                        @php /** @var \Spatie\Activitylog\Models\Activity $log */ @endphp
                        <tr>
                            <td class="ps-3 align-middle text-dark fw-semi-bold">{{ $log->description }}</td>
                            <td class="align-middle text-700">{{ $log->causer?->name ?? 'System' }}</td>
                            <td class="align-middle text-end pe-3 text-muted fs--2">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No recent logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>
        </x-falcon.card>
    </div>

    <div class="col-lg-4">
        <x-falcon.card title="Quick Actions" bodyClass="p-3" headerClass="bg-light">
            <div class="d-grid gap-2">
                <x-falcon.button href="{{ route('medical-centers.create') }}" variant="falcon-primary" class="text-start" icon="fas fa-plus">
                    Add Medical Center
                </x-falcon.button>
                <x-falcon.button href="{{ route('staff.create') }}" variant="falcon-info" class="text-start" icon="fas fa-user-plus">
                    Register New Staff
                </x-falcon.button>
                <x-falcon.button href="{{ route('cities.index') }}" variant="falcon-warning" class="text-start" icon="fas fa-map-marker-alt">
                    Manage Regions
                </x-falcon.button>
            </div>
        </x-falcon.card>
    </div>
</div>
