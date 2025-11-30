<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search medical centers, circles, or cities..."
            wire:model.live.debounce.500ms="search">

        <a href="{{ route('medical-centers.create') }}" class="btn btn-success">Add Medical Center</a>
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
                        Medical Center
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
                @forelse ($medicalCenters as $mc)
                    <tr>
                        <td>{{ $mc->id }}</td>
                        <td>{{ $mc->name }}</td>
                        <td>{{ $mc->circle->name ?? '—' }}</td>
                        <td>{{ $mc->circle->city->name ?? '—' }}</td>
                        <td>{{ $mc->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('medical-centers.edit', $mc->id) }}" class="btn btn-link p-0 text-primary"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-link p-0 text-danger ms-2"
                                wire:click.prevent="confirmDelete({{ $mc->id }})" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No medical centers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $medicalCenters->links() }}
        </div>
    </div>

    @if ($confirmingMedicalCenterDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingMedicalCenterDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this medical center?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingMedicalCenterDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteMedicalCenter">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
