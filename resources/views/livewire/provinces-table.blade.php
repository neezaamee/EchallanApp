<div>
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" class="form-control w-25" placeholder="Search provinces..."
               wire:model.live.debounce.500ms="search">

        <a href="{{ route('provinces.create') }}" class="btn btn-success">Add Province</a>
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
                        Name
                        @if($sortField === 'name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('code')" style="cursor: pointer;">
                        Code
                        @if($sortField === 'code')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($provinces as $province)
                    <tr>
                        <td>{{ $province->id }}</td>
                        <td>{{ $province->name }}</td>
                        <td>{{ $province->code }}</td>
                        <td>{{ $province->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('provinces.edit', $province->id) }}" class="btn btn-link p-0 text-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-link p-0 text-danger ms-2" title="Delete"
                                    wire:click="confirmDelete({{ $province->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No provinces found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $provinces->links() }}
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div wire:ignore.self class="modal fade @if($confirmingProvinceDeletion) show d-block @endif"
         tabindex="-1" style="@if($confirmingProvinceDeletion) background:rgba(0,0,0,0.5); @endif">
        @if($confirmingProvinceDeletion)
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingProvinceDeletion', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this province?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('confirmingProvinceDeletion', null)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteProvince">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
