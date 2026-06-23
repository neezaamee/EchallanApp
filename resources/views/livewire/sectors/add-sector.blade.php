<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add New Sector</h5>
        <a href="{{ route('sectors.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label">Sector Name</label>
                <input type="text" wire:model.blur="name" class="form-control" placeholder="Enter sector name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" wire:model="slug" class="form-control" placeholder="Optional slug">
                @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Circle</label>
                <select class="form-select" wire:model="circle_id">
                    <option value="">-- Select Circle --</option>
                    @foreach($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name }} ({{ $circle->city->name ?? '—' }})</option>
                    @endforeach
                </select>
                @error('circle_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Add Sector</button>
            </div>
        </form>
    </div>
</div>
