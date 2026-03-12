<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Staff Management</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="table-simple-pagination-actions">
                    <a href="{{ route('staff.create') }}" class="btn btn-falcon-default btn-sm" type="button">
                        <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                        <span class="d-none d-sm-inline-block ms-1">New Staff</span>
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
                        <input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search staff..." aria-label="search" wire:model.live.debounce.500ms="search" />
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
                        <th wire:click="sortBy('id')" style="cursor: pointer; width: 50px;" class="ps-3">
                            # @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th wire:click="sortBy('first_name')" style="cursor: pointer;">
                            Name / Role @if($sortField === 'first_name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th>Location Info</th>
                        <th>Current Posting</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse ($staff as $s)
                        <tr>
                            <td class="text-muted ps-3">{{ ($staff->currentPage() - 1) * $staff->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-l me-2">
                                        @if($s->photo)
                                            <img class="rounded-circle" src="{{ asset($s->photo) }}" alt="" />
                                        @else
                                            <div class="avatar-name rounded-circle"><span>{{ strtoupper(substr($s->first_name, 0, 1)) }}</span></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $s->fullName() }}</h6>
                                        <p class="fs--2 mb-0 text-muted">{{ $s->rank->name ?? '—' }} ({{ $s->user ? $s->user->getRoleNames()->first() : '—' }})</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php $posting = $s->activePosting; @endphp
                                @if($posting)
                                    <div class="lh-1">
                                        <span class="fw-semi-bold text-dark">{{ $posting->getCircleName() }}</span><br>
                                        <small class="fs--2 text-700">{{ $posting->getCityName() }}, {{ $posting->getProvinceName() }}</small>
                                    </div>
                                @else
                                    <span class="badge badge-soft-secondary text-dark fs--2 italic">No active posting</span>
                                @endif
                            </td>
                            <td>
                                @if($posting)
                                    @php
                                        $place = 'N/A';
                                        if ($posting->medical_center_id) $place = $posting->medicalCenter->name ?? '—';
                                        elseif ($posting->dumping_point_id) $place = $posting->dumpingPoint->name ?? '—';
                                        elseif ($posting->circle_id) $place = $posting->circle->name ?? '—';
                                        elseif ($posting->city_id) $place = $posting->city->name ?? '—';
                                        elseif ($posting->province_id) $place = $posting->province->name ?? '—';
                                    @endphp
                                    <span class="badge badge-soft-info text-dark fs--2"><i class="fas fa-map-marker-alt me-1"></i>{{ $place }}</span>
                                @else
                                    <a href="{{ route('staff-postings.create', ['staff_id' => $s->id]) }}" class="btn btn-link p-0 fs--2 text-decoration-none">
                                        <span class="fas fa-plus-circle me-1"></span>Assign Now
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="lh-1">
                                    <span class="fw-semi-bold text-dark fs--1">{{ $s->phone }}</span><br>
                                    <small class="text-muted">{{ $s->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge badge-soft-{{ $s->status === 'active' ? 'success' : 'secondary' }} text-dark fs--2">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                    @if($s->activePosting)
                                        <span class="badge badge-soft-primary text-dark fs--2">Posted</span>
                                    @else
                                        <span class="badge badge-soft-warning text-dark fs--2">Not Posted</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('staff.show', $s->id) }}" class="btn btn-link p-0 text-info" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('staff.edit', $s->id) }}" class="btn btn-link p-0 text-primary ms-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-link p-0 text-danger ms-2" wire:click.prevent="confirmDelete({{ $s->id }})" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-muted">No staff found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light py-2">
        <div class="d-flex justify-content-end">
            {{ $staff->links() }}
        </div>
    </div>

    @if ($confirmingStaffDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white"><span class="fas fa-exclamation-triangle me-2"></span>Confirm Deletion</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('confirmingStaffDeletion', null)"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-0">Are you sure you want to <strong>permanently delete</strong> this staff member? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-falcon-default btn-sm" wire:click="$set('confirmingStaffDeletion', null)">Cancel</button>
                        <button class="btn btn-danger btn-sm" wire:click="deleteStaff">Delete Permanently</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
