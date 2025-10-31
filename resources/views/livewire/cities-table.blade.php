<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search cities or provinces..."
               wire:model.live.debounce.500ms="search">

        <a href="{{ route('cities.create') }}" class="btn btn-success">Add City</a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-responsive table-striped align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                        #
                        @if($sortField === 'id')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                        City Name
                        @if($sortField === 'name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th wire:click="sortBy('slug')" style="cursor: pointer;">
                        Slug
                        @if($sortField === 'slug')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>

                    <th wire:click="sortBy('province_id')" style="cursor: pointer;">
                        Province
                        @if($sortField === 'province_id')
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

                            <button class="btn btn-link p-0 text-danger ms-2" title="Delete"
                                    wire:click="confirmDelete({{ $city->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No cities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $cities->links() }}
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div wire:ignore.self class="modal fade @if($confirmingCityDeletion) show d-block @endif"
         tabindex="-1" style="@if($confirmingCityDeletion) background:rgba(0,0,0,0.5); @endif">
        @if($confirmingCityDeletion)
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingCityDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this city?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingCityDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteCity">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
