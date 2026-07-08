<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $totalRequests ?? 0 }}"
            label="Total Records"
            icon="fas fa-file-medical"
            color="primary"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $pendingRequests ?? 0 }}"
            label="In Progress"
            icon="fas fa-clock"
            color="warning"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $approvedRequests ?? 0 }}"
            label="Approved / Released"
            icon="fas fa-check-circle"
            color="success"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $unpaidRequests ?? 0 }}"
            label="Unpaid Fees"
            icon="fas fa-exclamation-circle"
            color="danger"
        />
    </div>
</div>

<div class="row g-3">
    {{-- Medical Requests Table --}}
    <div class="col-lg-6">
        <x-falcon.card title="Recent Medical Requests" bodyClass="p-0">
            <x-slot name="headerActions">
                <div class="d-flex align-items-center">
                    <x-falcon.button href="{{ route('medical-requests.create') }}" variant="falcon-success" size="xs" class="me-2" icon="fas fa-plus">
                        New
                    </x-falcon.button>
                    <a href="{{ route('medical-requests.index') }}" class="btn btn-link btn-sm px-0 fw-bold">
                        View All <span class="fas fa-chevron-right ms-1 fs--2"></span>
                    </a>
                </div>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">PSID</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests ?? [] as $request)
                        @php /** @var \App\Models\MedicalRequest $request */ @endphp
                        <tr>
                            <td class="ps-3 align-middle fw-bold text-dark fs--1">{{ $request->psid }}</td>
                            <td class="align-middle">
                                <x-falcon.badge :variant="($request->payment_status ?? '') === 'paid' ? 'success' : 'danger'">
                                    {{ ucfirst($request->payment_status ?? 'N/A') }}
                                </x-falcon.badge>
                            </td>
                            <td class="align-middle text-end pe-3 text-muted fs--2">{{ $request->created_at?->format('M d') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted fs--1">No medical requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>
        </x-falcon.card>
    </div>

    {{-- Traffic Challans Table --}}
    <div class="col-lg-6">
        <x-falcon.card title="Recent Traffic Challans" bodyClass="p-0">
            <x-slot name="headerActions">
                <div class="d-flex align-items-center">
                    <x-falcon.button href="{{ route('impound.check-status.form') }}" variant="falcon-primary" size="xs" class="me-2" icon="fas fa-search">
                        Find
                    </x-falcon.button>
                    <a href="{{ route('challans.index') }}" class="btn btn-link btn-sm px-0 fw-bold">
                        View All <span class="fas fa-chevron-right ms-1 fs--2"></span>
                    </a>
                </div>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">PSID</th>
                        <th>Vehicle</th>
                        <th>Payment</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentChallans ?? [] as $challan)
                        @php /** @var \App\Models\Challan $challan */ @endphp
                        <tr>
                            <td class="ps-3 align-middle fw-bold text-dark fs--1">{{ $challan->psid }}</td>
                            <td class="align-middle text-800 fw-semi-bold">{{ strtoupper($challan->vehicle_number) }}</td>
                            <td class="align-middle">
                                <div class="fw-bold text-dark fs--1">Rs. {{ number_format($challan->fine_amount) }}</div>
                                <x-falcon.badge :variant="($challan->payment_status ?? '') === 'paid' ? 'success' : 'danger'">
                                    {{ ucfirst($challan->payment_status ?? 'Unpaid') }}
                                </x-falcon.badge>
                            </td>
                            <td class="align-middle text-end pe-3">
                                <x-falcon.button href="{{ route('challans.show', $challan->id) }}" variant="outline-info" size="xs">
                                    View
                                </x-falcon.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted fs--1">No traffic challans found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>
        </x-falcon.card>
    </div>
</div>
