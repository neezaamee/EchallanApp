<x-falcon.card title="Sectors" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('sectors:create')
        <x-falcon.button href="{{ route('sectors.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Sector
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search sectors..." wire:model.live.debounce.500ms="search" />
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
                    Sector @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('slug')" style="cursor: pointer;">Slug @if($sortField === 'slug') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif</th>
                <th>Circle</th>
                <th>City</th>
                <th>Created At</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sectors as $sector)
                <tr>
                    <td class="text-muted ps-3">{{ ($sectors->currentPage() - 1) * $sectors->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $sector->name }}</td>
                    <td>
                        <x-falcon.badge variant="secondary">
                            {{ $sector->slug ?? '—' }}
                        </x-falcon.badge>
                    </td>
                    <td>{{ $sector->circle->name ?? '—' }}</td>
                    <td>{{ $sector->circle->city->name ?? '—' }}</td>
                    <td class="text-muted fs--2">{{ $sector->created_at ? $sector->created_at->format('M d, Y') : '—' }}</td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :editRoute="auth()->user()->can('sectors:edit') ? route('sectors.edit', $sector->id) : null"
                            :deleteAction="auth()->user()->can('sectors:delete') ? 'confirmDelete(' . $sector->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-muted">No sectors found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $sectors->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingSectorDeletion"
        onCancel="$set('confirmingSectorDeletion', null)"
        onConfirm="deleteSector"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this sector? This action cannot be undone."
    />
</x-falcon.card>
