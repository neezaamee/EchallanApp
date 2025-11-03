<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="card-title mb-3">Edit Dumping Point</h5>

        @if ($successMessage)
            <div class="alert alert-success">{{ $successMessage }}</div>
        @endif

        <div class="mb-3">
            <label for="circle_id" class="form-label fw-bold">Circle</label>
            <select wire:model="circle_id" id="circle_id" class="form-select">
                <option value="">Select Circle</option>
                @foreach ($circles as $circle)
                    <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                @endforeach
            </select>
            @error('circle_id') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Dumping Point Name</label>
            <input wire:model="name" type="text" id="name" class="form-control">
            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label fw-bold">Location</label>
            <textarea wire:model="location" id="location" class="form-control" rows="3"></textarea>
            @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <button wire:click="update" wire:loading.attr="disabled" class="btn btn-primary w-100">
            <span wire:loading.remove>Update Dumping Point</span>
            <span wire:loading>
                <i class="spinner-border spinner-border-sm"></i> Updating...
            </span>
        </button>
    </div>
</div>
