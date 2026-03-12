<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Staff Postings & Transfers</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div class="d-flex align-items-center gap-2">
                    <form class="position-relative">
                        <input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search Staff..." wire:model.live.debounce.500ms="search" />
                        <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                    </form>
                    <a href="{{ route('staff-postings.create') }}" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                        <span class="d-none d-sm-inline-block ms-1">New Posting</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3 sort cursor-pointer" wire:click="sortBy('id')">
                            # ID
                            @if($sortField === 'id')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @endif
                        </th>
                        <th>Staff Name</th>
                        <th>CNIC</th>
                        <th>Place of Posting</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse ($postings as $posting)
                        <tr>
                            <td class="ps-3 text-muted fw-bold">#{{ $posting->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $posting->staff->fullName() }}</div>
                                <small class="text-muted">{{ $posting->staff->email }}</small>
                            </td>
                            <td><span class="font-monospace text-muted">{{ $posting->staff->cnic }}</span></td>
                            <td>
                                @php
                                    $place = 'N/A';
                                    if ($posting->medical_center_id) $place = $posting->medicalCenter->name ?? 'N/A';
                                    elseif ($posting->dumping_point_id) $place = $posting->dumpingPoint->name ?? 'N/A';
                                    elseif ($posting->circle_id) $place = $posting->circle->name ?? 'N/A';
                                    elseif ($posting->city_id) $place = $posting->city->name ?? 'N/A';
                                    elseif ($posting->province_id) $place = $posting->province->name ?? 'N/A';
                                @endphp
                                <span class="fw-semi-bold text-700">{{ $place }}</span>
                            </td>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($posting->start_date)->format('d M, Y') }}</td>
                            <td>
                                <span class="badge badge-soft-{{ $posting->status === 'active' ? 'success' : 'secondary' }} text-{{ $posting->status === 'active' ? 'success' : 'secondary' }} fs--2">
                                    {{ ucfirst($posting->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('staff-postings.create') }}?staff_id={{ $posting->staff_id }}" class="btn btn-link p-0 text-primary" title="Transfer Staff">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5 text-muted">
                                <i class="fas fa-map-marker-alt fa-2x mb-3 d-block opacity-25"></i>
                                No active postings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($postings->hasPages())
        <div class="card-footer bg-light py-2">
            <div class="d-flex justify-content-end">
                {{ $postings->links() }}
            </div>
        </div>
    @endif
</div>
