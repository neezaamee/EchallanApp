<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Issue Warning</h5>
        <p class="mb-0 mt-1 fs--1 text-600">Use this form to issue a non-monetary warning to a violator.</p>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @livewire('violator-history')

        <form wire:submit.prevent="save">
            <div class="row gx-2">
                <!-- Violator Name -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="violator_name">Violator Name</label>
                    <input type="text" wire:model="violator_name" id="violator_name" class="form-control" placeholder="Enter name">
                    @error('violator_name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Violator CNIC -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="violator_cnic">Violator CNIC</label>
                    <input type="text" wire:model.live.debounce.500ms="violator_cnic" id="violator_cnic" class="form-control" placeholder="12345-1234567-1">
                    @error('violator_cnic') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Mobile -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="violator_mobile">Mobile Number (Optional)</label>
                    <input type="text" wire:model="violator_mobile" id="violator_mobile" class="form-control" placeholder="Enter mobile number">
                    @error('violator_mobile') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Vehicle Type -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="vehicle_type">Vehicle Type</label>
                    <select wire:model="vehicle_type" id="vehicle_type" class="form-select">
                        <option value="">Select Type</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="car">Car</option>
                        <option value="jeep">Jeep</option>
                        <option value="bus">Bus</option>
                        <option value="truck">Truck</option>
                    </select>
                    @error('vehicle_type') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Vehicle Number -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="vehicle_number">Vehicle Number</label>
                    <input type="text" wire:model.live.debounce.500ms="vehicle_number" id="vehicle_number" class="form-control" placeholder="e.g. ABC-123">
                    @error('vehicle_number') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Violation Name -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="violation_name">Violation Type</label>
                    <input type="text" wire:model="violation_name" id="violation_name" class="form-control" placeholder="e.g. Over speeding">
                    @error('violation_name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Location -->
                <div class="col-12 mb-3">
                    <label class="form-label" for="location">Location Details</label>
                    <input type="text" wire:model="location" id="location" class="form-control" placeholder="Enter location of violation">
                    @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Remarks -->
                <div class="col-12 mb-3">
                    <label class="form-label" for="remarks">Additional Remarks</label>
                    <textarea wire:model="remarks" id="remarks" rows="3" class="form-control" placeholder="Any additional notes..."></textarea>
                    @error('remarks') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('warnings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Issue Warning
                </button>
            </div>
        </form>
    </div>
</div>
