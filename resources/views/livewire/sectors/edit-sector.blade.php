<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Sector</h5>
        <a href="{{ route('sectors.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="update">
            <div class="mb-3">
                <label for="name" class="form-label">Sector Name</label>
                <input wire:model="name" type="text" id="name" class="form-control">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input wire:model="slug" type="text" id="slug" class="form-control">
                @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="circle_id" class="form-label">Circle</label>
                <select wire:model="circle_id" id="circle_id" class="form-select">
                    <option value="">Select Circle</option>
                    @foreach ($circles as $circle)
                        <option value="{{ $circle->id }}">
                            {{ $circle->name }} ({{ $circle->city->name ?? '—' }})
                        </option>
                    @endforeach
                </select>
                @error('circle_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Sector</button>
            </div>
        </form>
    </div>
</div>
