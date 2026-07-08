<x-falcon.card title="Permissions" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('permissions:create')
        <x-falcon.button href="{{ route('permissions.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Permission
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search permissions..." wire:model.live.debounce.500ms="search" />
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

    {{-- Table Component --}}
    <x-falcon.table>
        <thead class="bg-200 text-900">
            <tr>
                <th wire:click="sortBy('id')" style="cursor: pointer; width: 80px;" class="ps-3">
                    S.No @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('name')" style="cursor: pointer;">
                    Permission Name @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td class="text-muted ps-3">{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $permission->name }}</td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :editRoute="auth()->user()->can('permissions:edit') ? route('permissions.edit', $permission->id) : null"
                            :deleteAction="auth()->user()->can('permissions:delete') ? 'confirmDelete(' . $permission->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center p-4 text-muted">No permissions found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $permissions->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingPermissionDeletion"
        onCancel="$set('confirmingPermissionDeletion', null)"
        onConfirm="deletePermission"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this permission? This action cannot be undone."
    />
</x-falcon.card>
