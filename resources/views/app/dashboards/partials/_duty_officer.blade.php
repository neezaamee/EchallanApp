<div class="row g-3">
    <!-- Stats Row -->
    <div class="col-sm-4">
        <x-falcon.statistic-card 
            value="{{ $totalBounded }}"
            label="Total Bounded (Current)"
            icon="fas fa-car"
            color="warning"
        />
    </div>
    <div class="col-sm-4">
        <x-falcon.statistic-card 
            value="{{ $paidBounded }}"
            label="Paid & Ready to Release"
            icon="fas fa-check-circle"
            color="success"
        />
    </div>
    <div class="col-sm-4">
        <x-falcon.statistic-card 
            value="{{ $unpaidBounded }}"
            label="Unpaid Pending"
            icon="fas fa-clock"
            color="danger"
        />
    </div>

    <!-- Bounded Vehicles Table -->
    <div class="col-lg-12">
        <x-falcon.card title="Bounded Vehicles at {{ $dumpingPoint?->name ?? 'Assigned Point' }}" bodyClass="p-0">
            <x-slot name="headerActions">
                <x-falcon.button href="{{ route('impound.check-status.form') }}" variant="falcon-default" size="sm" icon="fas fa-search">
                    Check PSID Status
                </x-falcon.button>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Vehicle Info</th>
                        <th>Violator</th>
                        <th>PSID</th>
                        <th>Amount</th>
                        <th class="text-center">Payment</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boundedVehicles as $challan)
                        <tr>
                            <td class="ps-3 align-middle">
                                <strong class="text-dark">{{ strtoupper($challan->vehicle_number) }}</strong><br>
                                <span class="text-500 fs--2">{{ ucfirst($challan->vehicle_type) }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="fw-semi-bold text-800">{{ $challan->violator_name }}</span><br>
                                <span class="text-500 fs--2">{{ $challan->violator_cnic }}</span>
                            </td>
                            <td class="align-middle fw-bold text-dark fs--1">{{ $challan->psid }}</td>
                            <td class="align-middle text-dark">Rs. {{ number_format($challan->fine_amount) }}</td>
                            <td class="align-middle text-center">
                                @if($challan->isPaid())
                                    <x-falcon.badge variant="success"><span class="fas fa-check me-1"></span>Paid</x-falcon.badge>
                                @else
                                    <x-falcon.badge variant="secondary">Unpaid</x-falcon.badge>
                                @endif
                            </td>
                            <td class="align-middle text-end pe-3">
                                @if($challan->isPaid())
                                    <x-falcon.button href="{{ route('impound.release.form', $challan->id) }}" variant="primary" size="sm">
                                        Release
                                    </x-falcon.button>
                                @else
                                    <button class="btn btn-sm btn-secondary disabled" title="Payment Required" disabled>
                                        Release
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-500">
                                <img class="mb-2" src="{{ asset('assets/img/icons/spot-illustrations/empty.png') }}" alt="No data" width="60"><br>
                                No bounded vehicles found at this location.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>

            @if($boundedVehicles->count() > 0)
                <x-slot name="footer">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <p class="fs--1 mb-0 text-muted">Showing latest records</p>
                        </div>
                    </div>
                </x-slot>
            @endif
        </x-falcon.card>
    </div>
</div>
