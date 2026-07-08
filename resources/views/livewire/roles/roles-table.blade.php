<x-falcon.card title="Role Management" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('roles:create')
        <x-falcon.button href="{{ route('roles.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Role
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search roles..." wire:model.live.debounce.500ms="search" />
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
                    S.No @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('name')" style="cursor: pointer;">
                    Role Name @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td class="text-muted ps-3">{{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ ucwords(str_replace(['_', '-'], ' ', $role->name)) }}</td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :viewRoute="route('roles.show', $role->id)"
                            :editRoute="auth()->user()->can('roles:edit') ? route('roles.edit', $role->id) : null"
                            :deleteAction="(auth()->user()->can('roles:delete') && $role->name !== 'super_admin' && $role->name !== 'admin') ? 'confirmDelete(' . $role->id . ')' : null"
                        >
                            @if($role->name !== 'super_admin')
                                <a class="dropdown-item text-800" href="{{ route('roles.impersonate', $role->id) }}">
                                    <span class="fas fa-user-secret text-warning me-2 fs--2"></span>Impersonate
                                </a>
                            @endif
                        </x-falcon.action-dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center p-4 text-muted">No roles found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $roles->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingRoleDeletion"
        onCancel="$set('confirmingRoleDeletion', null)"
        onConfirm="deleteRole"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this role? This might affect users assigned to it."
    />
</x-falcon.card>
