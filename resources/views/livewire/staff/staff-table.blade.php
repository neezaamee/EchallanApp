<x-falcon.card title="Staff Management" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('staff:create')
        <x-falcon.button href="{{ route('staff.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Staff
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search staff..." wire:model.live.debounce.500ms="search" />
        </div>
        <div class="col-auto ms-auto d-flex align-items-center gap-2">
            <small class="text-muted text-nowrap">Show entries:</small>
            <select wire:model.live="perPage" class="form-select form-select-sm shadow-none" style="width: auto;">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </x-falcon.filter-panel>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    {{-- Table --}}
    <x-falcon.table>
        <thead class="bg-200 text-900">
            <tr>
                <th wire:click="sortBy('id')" style="cursor: pointer; width: 80px;" class="ps-3">
                    S.No @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
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
        <tbody>
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
                                <h6 class="mb-0 fw-bold text-dark fs--1">{{ $s->fullName() }}</h6>
                                <p class="fs--2 mb-0 text-muted">{{ $s->rank->name ?? '—' }} ({{ $s->user ? $s->user->getRoleNames()->first() : '—' }})</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $posting = $s->activePosting; @endphp
                        @if($posting)
                            <div class="lh-1">
                                <span class="fw-semi-bold text-dark fs--1">{{ $posting->getCircleName() }}</span><br>
                                <small class="fs--2 text-700">{{ $posting->getCityName() }}, {{ $posting->getProvinceName() }}</small>
                            </div>
                        @else
                            <x-falcon.badge variant="secondary">No active posting</x-falcon.badge>
                        @endif
                    </td>
                    <td>
                        @if($posting)
                            @php
                                $place = 'N/A';
                                if ($posting->medical_center_id) $place = $posting->medicalCenter->name ?? '—';
                                elseif ($posting->dumping_point_id) $place = $posting->dumpingPoint->name ?? '—';
                                elseif ($posting->sector_id) $place = $posting->sector->name ?? '—';
                                elseif ($posting->circle_id) $place = $posting->circle->name ?? '—';
                                elseif ($posting->city_id) $place = $posting->city->name ?? '—';
                                elseif ($posting->province_id) $place = $posting->province->name ?? '—';
                            @endphp
                            <x-falcon.badge variant="info">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $place }}
                            </x-falcon.badge>
                        @else
                            @can('staff-postings:create')
                            <a href="{{ route('staff-postings.create', ['staff_id' => $s->id]) }}" class="btn btn-link p-0 fs--2 text-decoration-none fw-bold">
                                <span class="fas fa-plus-circle me-1"></span>Assign Now
                            </a>
                            @endcan
                        @endif
                    </td>
                    <td>
                        <div class="lh-1">
                            <span class="fw-semi-bold text-dark fs--1">{{ $s->phone }}</span><br>
                            <small class="text-muted fs--2">{{ $s->email }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column align-items-start gap-1">
                            <x-falcon.badge :variant="$s->status === 'active' ? 'success' : 'secondary'">
                                {{ ucfirst($s->status) }}
                            </x-falcon.badge>
                            @if($s->activePosting)
                                <x-falcon.badge variant="primary">Posted</x-falcon.badge>
                            @else
                                <x-falcon.badge variant="warning">Not Posted</x-falcon.badge>
                            @endif
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :viewRoute="auth()->user()->can('staff:view') ? route('staff.show', $s->id) : null"
                            :editRoute="auth()->user()->can('staff:edit') ? route('staff.edit', $s->id) : null"
                            :deleteAction="auth()->user()->can('staff:delete') ? 'confirmDelete(' . $s->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-muted">No staff found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $staff->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingStaffDeletion"
        onCancel="$set('confirmingStaffDeletion', null)"
        onConfirm="deleteStaff"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this staff member? This action cannot be undone."
    />
</x-falcon.card>
