<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Province</h5>
            <a href="{{ route('provinces.index') }}" class="btn btn-light btn-sm">← Back</a>
        </div>

        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="update">
                <div class="mb-3">
                    <label class="form-label">Province Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           wire:model="name" placeholder="Enter province name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Province Code</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                           wire:model="slug" placeholder="Enter province code (e.g., PB)">
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Province
                </button>
            </form>
        </div>
    </div>
</div>
