<div>
    <!-- Flash message -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text"
               wire:model.debounce.500ms="search"
               class="form-control w-50"
               placeholder="Search Dumping Points...">

        <select wire:model="circleFilter" class="form-select w-25">
            <option value="">All Circles</option>
            @foreach ($circles as $circle)
                <option value="{{ $circle->id }}">{{ $circle->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Dumping Point</th>
                <th>Location</th>
                <th>Circle</th>
                <th>City</th>
                <th>Province</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dumpingPoints as $index => $point)
                <tr>
                    <td>{{ $dumpingPoints->firstItem() + $index }}</td>
                    <td>{{ $point->name }}</td>
                    <td>{{ $point->location ?? 'N/A' }}</td>
                    <td>{{ $point->circle->name ?? '-' }}</td>
                    <td>{{ $point->circle->city->name ?? '-' }}</td>
                    <td>{{ strtoupper($point->circle->city->province->slug ?? '-') }}</td>
                    <td>
                        <a href="{{ route('dumping-points.edit', $point->id) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                        <button wire:click="delete({{ $point->id }})"
                                class="btn btn-sm btn-danger"
                                onclick="confirm('Are you sure you want to delete this dumping point?') || event.stopImmediatePropagation()">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No Dumping Points Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $dumpingPoints->links('pagination::bootstrap-5') }}
    </div>
</div>
