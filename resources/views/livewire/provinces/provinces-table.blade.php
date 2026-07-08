<x-falcon.card title="Provinces" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('provinces:create')
        <x-falcon.button href="{{ route('provinces.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Province
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search provinces..." wire:model.live.debounce.500ms="search" />
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
                    No @if ($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('name')" style="cursor: pointer;">
                    Name @if ($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('slug')" style="cursor: pointer;">
                    Slug @if ($sortField === 'slug') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th>Created At</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($provinces as $province)
                <tr>
                    <td class="text-muted ps-3">{{ ($provinces->currentPage() - 1) * $provinces->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $province->name }}</td>
                    <td>
                        <x-falcon.badge variant="secondary">
                            {{ $province->slug }}
                        </x-falcon.badge>
                    </td>
                    <td class="text-muted fs--2">{{ $province->created_at->format('M d, Y') }}</td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :editRoute="auth()->user()->can('provinces:edit') ? route('provinces.edit', $province->id) : null"
                            :deleteAction="auth()->user()->can('provinces:delete') ? 'confirmDelete(' . $province->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-muted">No provinces found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $provinces->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingProvinceDeletion"
        onCancel="$set('confirmingProvinceDeletion', null)"
        onConfirm="deleteProvince"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this province? This action might affect linked cities and circles."
    />
</x-falcon.card>
