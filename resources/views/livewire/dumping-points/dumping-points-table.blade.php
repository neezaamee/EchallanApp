<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search dumping points, circles, or cities..."
            wire:model.live.debounce.500ms="search">

        <a href="{{ route('dumping-points.create') }}" class="btn btn-success">Add Dumping Point</a>
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
                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                        Dumping Point
                        @if($sortField === 'name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('circle_id')" style="cursor: pointer;">
                        Circle
                    </th>
                    <th wire:click="sortBy('city_id')" style="cursor: pointer;">
                        City
                    </th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dumpingPoints as $dp)
                    <tr>
                        <td>{{ $dp->id }}</td>
                        <td>{{ $dp->name }}</td>
                        <td>{{ $dp->circle->name ?? '—' }}</td>
                        <td>{{ $dp->circle->city->name ?? '—' }}</td>
                        <td>{{ $dp->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('dumping-points.edit', $dp->id) }}" class="btn btn-link p-0 text-primary"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-link p-0 text-danger ms-2"
                                wire:click.prevent="confirmDelete({{ $dp->id }})" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No dumping points found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $dumpingPoints->links() }}
        </div>
    </div>

    @if ($confirmingDumpingPointDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingDumpingPointDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this dumping point?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingDumpingPointDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteDumpingPoint">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
