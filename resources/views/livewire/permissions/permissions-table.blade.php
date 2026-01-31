<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search permissions..."
            wire:model.live.debounce.500ms="search">

        @can('role-create')
            <a href="{{ route('permissions.create') }}" class="btn btn-success">Create New Permission</a>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-2">{{ session('message') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                        No @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                    </th>
                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                        Name @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                    </th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td class="text-end">
                            @can('role-edit')
                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm" title="Edit">Edit</a>
                            @endcan

                            @can('role-delete')
                                <button wire:click.prevent="confirmDelete({{ $permission->id }})" class="btn btn-danger btn-sm ms-1" title="Delete">
                                    Delete
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No permissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $permissions->links() }}
        </div>
    </div>

    @if ($confirmingPermissionDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingPermissionDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this permission?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingPermissionDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deletePermission">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
