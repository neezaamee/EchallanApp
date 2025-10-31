<div>
    <!-- Header section -->
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text"
               class="form-control w-25"
               placeholder="Search cities or provinces..."
               wire:model.live.debounce.500ms="search">

        <a href="{{ route('cities.create') }}" class="btn btn-success">
            Add City
        </a>
    </div>

    <!-- Success message -->
    @if (session()->has('message'))
        <div class="alert alert-success mb-3">
            {{ session('message') }}
        </div>
    @endif

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                        #
                        @if ($sortField === 'id')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                        City Name
                        @if ($sortField === 'name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th wire:click="sortBy('slug')" style="cursor: pointer;">
                        Slug
                        @if ($sortField === 'slug')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th wire:click="sortBy('province_id')" style="cursor: pointer;">
                        Province
                        @if ($sortField === 'province_id')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($cities as $city)
                    <tr>
                        <td>{{ $city->id }}</td>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->slug ?? '—' }}</td>
                        <td>{{ $city->province->name ?? '—' }}</td>
                        <td>{{ $city->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('cities.edit', $city->id) }}"
                               class="btn btn-link p-0 text-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <button class="btn btn-link p-0 text-danger ms-2"
                                    title="Delete"
                                    wire:click="confirmDelete({{ $city->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No cities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $cities->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmingCityDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="$set('confirmingCityDeletion', false)">
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to delete this city?</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                                wire:click="$set('confirmingCityDeletion', false)">
                            Cancel
                        </button>
                        <button class="btn btn-danger" wire:click="deleteCity">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
