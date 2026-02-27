<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Dumping Point</h5>
        <a href="{{ route('dumping-points.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="update">
            {{-- Province Dropdown --}}
            <div class="mb-3">
                <label class="form-label">Province</label>
                <select class="form-select" wire:model.live="province_id">
                    <option value="">-- Select Province --</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('province_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- City Dropdown --}}
            <div class="mb-3">
                <label class="form-label">City</label>
                <select class="form-select" wire:model.live="city_id">
                    <option value="">-- Select City --</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('city_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Circle Dropdown --}}
            <div class="mb-3">
                <label class="form-label">Circle</label>
                <select class="form-select" wire:model="circle_id">
                    <option value="">-- Select Circle --</option>
                    @foreach($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                    @endforeach
                </select>
                @error('circle_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Dumping Point Name --}}
            <div class="mb-3">
                <label class="form-label">Dumping Point Name</label>
                <input type="text" wire:model="name" class="form-control" placeholder="Enter dumping point name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Location (optional) --}}
            <div class="mb-3">
                <label class="form-label">Location (optional)</label>
                <input type="text" wire:model="location" class="form-control" placeholder="Enter location">
                @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Dumping Point
                </button>
            </div>
        </form>
    </div>
</div>
