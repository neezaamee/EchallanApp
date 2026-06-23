<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Warning History</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="table-simple-pagination-actions">
                    <a href="{{ route('warnings.create') }}" class="btn btn-falcon-default btn-sm" type="button">
                        <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                        <span class="d-none d-sm-inline-block ms-1">Issue New Warning</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="p-3 border-bottom bg-light">
            <div class="row justify-content-between align-items-center">
                <div class="col-sm-auto">
                    <div class="position-relative">
                        <input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search CNIC, Name, Vehicle..." aria-label="search" wire:model.live.debounce.300ms="search" />
                        <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success border-2 d-flex align-items-center p-2 mb-0" role="alert">
                <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
                <p class="mb-0 flex-1 text-800">{{ session('message') }}</p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Violator Info</th>
                        <th>Vehicle</th>
                        <th>Violation</th>
                        <th class="pe-3">Officer</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse ($warnings as $warning)
                        <tr>
                            <td class="text-muted ps-3">{{ $warning->created_at->format('d M, Y H:i') }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $warning->violator_name }}</div>
                                <div class="text-muted fs--2">{{ $warning->violator_cnic }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ strtoupper($warning->vehicle_number) }}</div>
                                <div class="text-muted fs--2">{{ ucfirst($warning->vehicle_type) }}</div>
                            </td>
                            <td class="text-muted">{{ $warning->violation_name }}</td>
                            <td class="text-muted pe-3">{{ $warning->officer->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-4 text-muted">No warnings found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light py-2">
        <div class="d-flex justify-content-end">
            {{ $warnings->links() }}
        </div>
    </div>
</div>
