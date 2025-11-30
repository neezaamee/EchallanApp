<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search staff by name, CNIC, email..."
            wire:model.live.debounce.500ms="search">

        <a href="{{ route('staff.create') }}" class="btn btn-success">Add Staff</a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-2">{{ session('message') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                        #
                        @if($sortField === 'id')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('first_name')" style="cursor: pointer;">
                        Name
                        @if($sortField === 'first_name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th>Rank / Designation</th>
                    <th>Current Posting</th>
                    <th>CNIC</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staff as $s)
                    <tr>
                        <td>{{ $s->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($s->photo)
                                    <img src="{{ asset($s->photo) }}" alt="" class="rounded-circle me-2" width="30" height="30">
                                @else
                                    <div class="rounded-circle me-2 bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        {{ strtoupper(substr($s->first_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    {{ $s->fullName() }}<br>
                                    <small class="text-muted">{{ $s->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $s->rank->name ?? '—' }} <br>
                            <small class="text-muted">{{ $s->getRoleNames()->first() ?? '—' }}</small>
                        </td>
                        <td>
                            @php
                                $posting = $s->activePosting;
                                $place = 'Not Posted';
                                if ($posting) {
                                    if ($posting->medical_center_id) {
                                        $place = $posting->medicalCenter->name ?? '—';
                                    } elseif ($posting->dumping_point_id) {
                                        $place = $posting->dumpingPoint->name ?? '—';
                                    } elseif ($posting->circle_id) {
                                        $place = $posting->circle->name ?? '—';
                                    } elseif ($posting->city_id) {
                                        $place = $posting->city->name ?? '—';
                                    } elseif ($posting->province_id) {
                                        $place = $posting->province->name ?? '—';
                                    }
                                }
                            @endphp
                            @if($posting)
                                {{ $place }}
                            @else
                                <span class="text-muted">Not Posted</span>
                            @endif
                        </td>
                        <td>{{ $s->cnic }}</td>
                        <td>{{ $s->phone }}</td>
                        <td>{{ ucfirst($s->gender) }}</td>
                        <td>
                            <span class="badge bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('staff.edit', $s->id) }}" class="btn btn-link p-0 text-primary"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-link p-0 text-danger ms-2"
                                wire:click.prevent="confirmDelete({{ $s->id }})" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No staff found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $staff->links() }}
        </div>
    </div>

    @if ($confirmingStaffDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingStaffDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this staff member?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingStaffDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteStaff">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
