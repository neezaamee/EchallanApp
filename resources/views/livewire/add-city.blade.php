<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary">
        <h5 class="mb-0 text-white">Add New City</h5>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label">City Name</label>
                <input type="text" wire:model.defer="name" class="form-control" placeholder="Enter city name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" wire:model.defer="slug" class="form-control" placeholder="Optional slug">
                @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Province</label>
                <select class="form-select" wire:model.defer="province_id">
                    <option value="">-- Select Province --</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                </select>
                @error('province_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('cities.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Add City
                </button>
            </div>
        </form>
    </div>
</div>
