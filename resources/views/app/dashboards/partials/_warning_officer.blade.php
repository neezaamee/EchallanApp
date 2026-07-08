<div class="row g-3 mb-3">
    <div class="col-md-4 col-xxl-4">
        <x-falcon.statistic-card 
            value="{{ $todayWarnings ?? 0 }}"
            label="Today's Warnings"
            icon="fas fa-exclamation-triangle"
            color="warning"
        />
    </div>
    <div class="col-md-4 col-xxl-4">
        <x-falcon.statistic-card 
            value="{{ $totalWarnings ?? 0 }}"
            label="Lifetime Issued"
            icon="fas fa-history"
            color="info"
        />
    </div>
    <div class="col-md-4 col-xxl-4">
        <x-falcon.statistic-card 
            value="Active"
            label="Patrol Status"
            icon="fas fa-shield-alt"
            color="success"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <x-falcon.card title="Warning Officer Operations" bodyClass="p-4" headerClass="bg-light">
            <p class="card-text text-800 fs--1">Manage your warning traffic enforcement operations here. You can issue warning slips to first-time violators to encourage road safety, or view the history of your issued warnings.</p>
            <div class="mt-3">
                <x-falcon.button href="{{ route('warnings.create') }}" variant="warning" icon="fas fa-plus">
                    Issue Warning
                </x-falcon.button>
                <x-falcon.button href="{{ route('warnings.index') }}" variant="outline-warning" class="ms-2" icon="fas fa-list">
                    View Warning History
                </x-falcon.button>
            </div>
        </x-falcon.card>
    </div>
</div>

@if(isset($recentWarnings) && $recentWarnings->count() > 0)
<x-falcon.card title="Recent Warnings Issued by You" bodyClass="p-0">
    <x-falcon.table>
        <thead class="bg-200 text-900">
            <tr>
                <th class="ps-3">Date & Time</th>
                <th>Violator Name</th>
                <th>CNIC</th>
                <th>Vehicle</th>
                <th>Violation Type</th>
                <th class="pe-3">Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentWarnings as $warning)
                <tr>
                    <td class="ps-3 align-middle text-muted fs--2">{{ $warning->created_at->format('d-M-Y h:i A') }}</td>
                    <td class="align-middle fw-bold text-dark">{{ $warning->violator_name }}</td>
                    <td class="align-middle text-700 fs--1">{{ $warning->violator_cnic }}</td>
                    <td class="align-middle text-800">
                        <x-falcon.badge variant="secondary" class="me-1">{{ strtoupper($warning->vehicle_type) }}</x-falcon.badge>
                        <strong>{{ strtoupper($warning->vehicle_number) }}</strong>
                    </td>
                    <td class="align-middle text-danger fw-semi-bold">{{ $warning->violation_name }}</td>
                    <td class="align-middle pe-3 text-muted fs--1">{{ $warning->location }}</td>
                </tr>
            @endforeach
        </tbody>
    </x-falcon.table>
</x-falcon.card>
@endif
