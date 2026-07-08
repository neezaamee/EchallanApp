<x-falcon.card title="Medical Centers" bodyClass="p-0">
    <x-slot name="headerActions">
        @can('medical-centers:create')
        <x-falcon.button href="{{ route('medical-centers.create') }}" variant="falcon-default" size="sm" icon="fas fa-plus">
            New Medical Center
        </x-falcon.button>
        @endcan
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <div class="col-sm-auto">
            <x-falcon.search-box placeholder="Search medical centers..." wire:model.live.debounce.500ms="search" />
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
                    S.No @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i> @endif
                </th>
                <th wire:click="sortBy('name')" style="cursor: pointer;">
                    Medical Center @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th wire:click="sortBy('circle_id')" style="cursor: pointer;">Circle @if($sortField === 'circle_id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif</th>
                <th>City</th>
                <th>Created At</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($medicalCenters as $mc)
                <tr>
                    <td class="text-muted ps-3">{{ ($medicalCenters->currentPage() - 1) * $medicalCenters->perPage() + $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $mc->name }}</td>
                    <td>{{ $mc->circle->name ?? '—' }}</td>
                    <td>
                        <x-falcon.badge variant="info">
                            {{ $mc->circle->city->name ?? '—' }}
                        </x-falcon.badge>
                    </td>
                    <td class="text-muted fs--2">{{ $mc->created_at->format('M d, Y') }}</td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown 
                            :editRoute="auth()->user()->can('medical-centers:edit') ? route('medical-centers.edit', $mc->id) : null"
                            :deleteAction="auth()->user()->can('medical-centers:delete') ? 'confirmDelete(' . $mc->id . ')' : null"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center p-4 text-muted">No medical centers found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    <x-slot name="footer">
        <x-falcon.pagination>
            {{ $medicalCenters->links() }}
        </x-falcon.pagination>
    </x-slot>

    {{-- Deletion Confirmation Modal --}}
    <x-falcon.confirmation-modal 
        :show="$confirmingMedicalCenterDeletion"
        onCancel="$set('confirmingMedicalCenterDeletion', null)"
        onConfirm="deleteMedicalCenter"
        title="Confirm Deletion"
        message="Are you sure you want to permanently delete this medical center? This action cannot be undone."
    />
</x-falcon.card>
