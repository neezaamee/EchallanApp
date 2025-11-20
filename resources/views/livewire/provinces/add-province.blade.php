<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary">
        <h5 class="mb-0 text-white">Add New Province</h5>
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
                <label class="form-label">Province Name</label>
                <input type="text" wire:model.defer="name" class="form-control" placeholder="Enter province name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Province Code</label>
                <input type="text" wire:model.defer="code" class="form-control" placeholder="Optional code">
                @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('provinces.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Add Province
                </button>
            </div>
        </form>
    </div>
</div>
