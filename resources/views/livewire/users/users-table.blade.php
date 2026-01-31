<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search users by name or email..."
            wire:model.live.debounce.500ms="search">

        <a href="{{ route('users.create') }}" class="btn btn-success">Create New User</a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-2">{{ session('message') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                        # @if($sortField === 'id') <i
                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                    </th>
                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                        Name @if($sortField === 'name') <i
                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                    </th>
                    <th wire:click="sortBy('email')" style="cursor: pointer;">
                        Email @if($sortField === 'email') <i
                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                    </th>
                    <th>Roles</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->getRoleNames() as $role)
                                <span class="badge bg-success text-white">{{ $role }}</span>
                            @endforeach
                        </td>
                        <td class="text-end">
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-link p-0 text-info"
                                title="Show"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-link p-0 text-primary ms-2"
                                title="Edit"><i class="fas fa-edit"></i></a>

                            @if(auth()->user()->can('delete users'))
                                <button wire:click.prevent="confirmDelete({{ $user->id }})"
                                    class="btn btn-link p-0 text-danger ms-2" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>

    @if ($confirmingUserDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingUserDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this user?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingUserDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteUser">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>