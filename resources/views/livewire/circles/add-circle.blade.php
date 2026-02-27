<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add New Circle</h5>
        <a href="{{ route('circles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label">Circle Name</label>
                <input type="text" wire:model.defer="name" class="form-control" placeholder="Enter circle name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" wire:model.defer="slug" class="form-control" placeholder="Optional slug">
                @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">City</label>
                <select class="form-select" wire:model.defer="city_id">
                    <option value="">-- Select City --</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('city_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Add Circle</button>
            </div>
        </form>
    </div>
</div>
