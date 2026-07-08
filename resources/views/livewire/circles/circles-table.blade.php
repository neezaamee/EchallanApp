<x-falcon.card title="Circles" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('circles:create')
        <x-falcon.button href="{{ route('circles.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Circle
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search circles..." wire:model.live.debounce.500ms="search" />
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
                    Circle @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('slug')" style="cursor: pointer;">Slug @if($sortField === 'slug') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif</th>
                <th wire:click="sortBy('city_id')" style="cursor: pointer;">City @if($sortField === 'city_id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif</th>
                <th>Created At</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($circles as $circle)
                <tr>
                    <td class="text-muted ps-3">{{ ($circles->currentPage() - 1) * $circles->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $circle->name }}</td>
                    <td>
                        <x-falcon.badge variant="secondary">
                            {{ $circle->slug ?? '—' }}
                        </x-falcon.badge>
                    </td>
                    <td>{{ $circle->city->name ?? '—' }}</td>
                    <td class="text-muted fs--2">{{ $circle->created_at->format('M d, Y') }}</td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :editRoute="auth()->user()->can('circles:edit') ? route('circles.edit', $circle->id) : null"
                            :deleteAction="auth()->user()->can('circles:delete') ? 'confirmDelete(' . $circle->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center p-4 text-muted">No circles found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $circles->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingCircleDeletion"
        onCancel="$set('confirmingCircleDeletion', null)"
        onConfirm="deleteCircle"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this circle? This action cannot be undone."
    />
</x-falcon.card>
