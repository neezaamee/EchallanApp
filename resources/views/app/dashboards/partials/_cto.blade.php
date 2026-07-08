<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $pendingUnpaid ?? 0 }}"
            label="Pending (Unpaid)"
            icon="fas fa-clock"
            color="warning"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $pendingPaid ?? 0 }}"
            label="Actionable Requests"
            icon="fas fa-tasks"
            color="info"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $passedThisMonth ?? 0 }}"
            label="Passed (This Month)"
            icon="fas fa-check-circle"
            color="success"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $failedThisMonth ?? 0 }}"
            label="Failed (This Month)"
            icon="fas fa-times-circle"
            color="danger"
        />
    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-falcon.card title="Recent Medical Requests ({{ $cityName ?? 'All' }})" bodyClass="p-0">
            <x-slot name="headerActions">
                <a href="{{ route('medical-requests.index') }}" class="btn btn-link btn-sm px-0 fw-bold">
                    View All <span class="fas fa-chevron-right ms-1 fs--2"></span>
                </a>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Citizen</th>
                        <th>PSID</th>
                        <th>Medical Center</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests ?? [] as $request)
                        <tr>
                            <td class="ps-3 align-middle fw-bold text-dark">{{ $request->citizen->full_name ?? 'N/A' }}</td>
                            <td class="align-middle text-700 fs--1">{{ $request->psid }}</td>
                            <td class="align-middle text-800">{{ $request->medicalCenter?->name ?? 'N/A' }}</td>
                            <td class="align-middle">
                                <x-falcon.badge :variant="$request->payment_status === 'paid' ? 'success' : 'danger'">
                                    {{ ucfirst($request->payment_status) }}
                                </x-falcon.badge>
                            </td>
                            <td class="align-middle">
                                <x-falcon.badge :variant="$request->status === 'passed' ? 'success' : ($request->status === 'failed' ? 'danger' : 'warning')]">
                                    {{ ucfirst($request->status) }}
                                </x-falcon.badge>
                            </td>
                            <td class="align-middle text-end pe-3 text-muted fs--2">{{ $request->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted fs--1">No recent requests found in this region.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>
        </x-falcon.card>
    </div>
</div>
