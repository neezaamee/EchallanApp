<x-falcon.card title="User Accounts" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('users:create')
        <x-falcon.button href="{{ route('users.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New User
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search users..." wire:model.live.debounce.500ms="search" />
        </div>
    </x-falcon.filter-panel>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    @if (session()->has('error'))
        <x-falcon.alert variant="danger">
            {{ session('error') }}
        </x-falcon.alert>
    @endif

    {{-- Table Component --}}
    <x-falcon.table>
        <thead class="bg-200 text-900">
            <tr>
                <th wire:click="sortBy('id')" style="cursor: pointer; width: 80px;" class="ps-3">
                    No. @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('name')" style="cursor: pointer;">
                    Name @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('email')" style="cursor: pointer;">
                    Email @if($sortField === 'email') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th>Roles</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="text-muted ps-3">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $user->name }}</td>
                    <td><a href="mailto:{{ $user->email }}" class="text-700 fs--1">{{ $user->email }}</a></td>
                    <td>
                        @foreach ($user->getRoleNames() as $role)
                            <x-falcon.badge variant="success">{{ $role }}</x-falcon.badge>
                        @endforeach
                    </td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :viewRoute="auth()->user()->can('users:view') ? route('users.show', $user->id) : null"
                            :editRoute="auth()->user()->can('users:edit') ? route('users.edit', $user->id) : null"
                            :deleteAction="auth()->user()->can('users:delete') ? 'confirmDelete(' . $user->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-muted">No users found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $users->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingUserDeletion"
        onCancel="$set('confirmingUserDeletion', null)"
        onConfirm="deleteUser"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this user account? This action cannot be undone."
    />
</x-falcon.card>