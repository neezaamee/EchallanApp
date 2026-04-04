<div class="row g-3">
    <!-- Stats Row -->
    <div class="col-sm-4">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url({{ asset('assets/img/icons/spot-illustrations/corner-1.png') }});"></div>
            <div class="card-body position-relative">
                <h6>Total Bounded<span class="badge badge-soft-warning rounded-pill ms-2">Current</span></h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-warning">{{ $totalBounded }}</div>
                <a class="fw-semi-bold fs--1 text-nowrap" href="#bounded-table">See all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url({{ asset('assets/img/icons/spot-illustrations/corner-2.png') }});"></div>
            <div class="card-body position-relative">
                <h6>Paid & Ready<span class="badge badge-soft-success rounded-pill ms-2">Release</span></h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-success">{{ $paidBounded }}</div>
                <p class="fs--1 mb-0">Verified Payments</p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url({{ asset('assets/img/icons/spot-illustrations/corner-3.png') }});"></div>
            <div class="card-body position-relative">
                <h6>Unpaid<span class="badge badge-soft-danger rounded-pill ms-2">Pending</span></h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-danger">{{ $unpaidBounded }}</div>
                <p class="fs--1 mb-0">Awaiting Bank Sync</p>
            </div>
        </div>
    </div>

    <!-- Bounded Vehicles Table -->
    <div class="col-lg-12">
        <div class="card h-100" id="bounded-table">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bounded Vehicles at {{ $dumpingPoint?->name ?? 'Assigned Point' }}</h5>
                <div>
                    <a href="{{ route('impound.check-status.form') }}" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-search me-1"></span>Check PSID Status
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs--1 mb-0">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="sort pe-1" data-sort="vehicle">Vehicle Info</th>
                                <th class="sort pe-1" data-sort="violator">Violator</th>
                                <th class="sort pe-1" data-sort="psid">PSID</th>
                                <th class="sort pe-1" data-sort="amount">Amount</th>
                                <th class="sort pe-1 text-center" data-sort="payment">Payment</th>
                                <th class="no-sort text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($boundedVehicles as $challan)
                                <tr class="btn-reveal-trigger">
                                    <td class="vehicle align-middle white-space-nowrap">
                                        <strong>{{ strtoupper($challan->vehicle_number) }}</strong><br>
                                        <span class="text-500">{{ ucfirst($challan->vehicle_type) }}</span>
                                    </td>
                                    <td class="violator align-middle">
                                        {{ $challan->violator_name }}<br>
                                        <span class="text-500 fs--2">{{ $challan->violator_cnic }}</span>
                                    </td>
                                    <td class="psid align-middle fw-semi-bold">{{ $challan->psid }}</td>
                                    <td class="amount align-middle">Rs. {{ number_format($challan->fine_amount) }}</td>
                                    <td class="payment align-middle text-center">
                                        @if($challan->isPaid())
                                            <span class="badge badge-soft-success"><span class="fas fa-check me-1"></span>Paid</span>
                                        @else
                                            <span class="badge badge-soft-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-end">
                                        @if($challan->isPaid())
                                            <a href="{{ route('impound.release.form', $challan->id) }}" class="btn btn-sm btn-primary">
                                                Release
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary disabled" title="Payment Required">
                                                Release
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-500">
                                        <img src="{{ asset('assets/img/icons/spot-illustrations/empty.png') }}" alt="No data" width="60"><br>
                                        No bounded vehicles found at this location.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($boundedVehicles->count() > 0)
                <div class="card-footer bg-light py-2">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <p class="fs--1 mb-0">Showing latest records</p>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-link btn-sm px-0 fw-semi-bold" href="#!">View all history<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
