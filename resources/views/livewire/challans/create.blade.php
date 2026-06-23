<div class="row g-3 mb-3">
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Issue New Challan</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                @if(!$dumping_point_id)
                    <div class="alert alert-warning">
                        You are not assigned to a Dumping Point. Please contact admin.
                    </div>
                @else
                
                @livewire('violator-history')

                <form wire:submit.prevent="save">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Dumping Point (Auto)</label>
                            <input type="text" class="form-control" value="{{ $dumping_point_name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pick Up Point</label>
                            <select wire:model="pick_up_point_id" class="form-select">
                                <option value="">Select Point</option>
                                @foreach($pickUpPoints as $point)
                                    <option value="{{ $point->id }}">{{ $point->name }}</option>
                                @endforeach
                            </select>
                            @error('pick_up_point_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Violator CNIC</label>
                            <input type="text" wire:model.live.debounce.500ms="violator_cnic" class="form-control" placeholder="33100-0000000-0">
                            @error('violator_cnic') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Violator Name</label>
                            <input type="text" wire:model="violator_name" class="form-control" placeholder="Full Name">
                            @error('violator_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Violator Mobile</label>
                            <input type="text" wire:model="violator_mobile" class="form-control" placeholder="0300-0000000">
                            @error('violator_mobile') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type</label>
                            <select wire:model.live="vehicle_type" class="form-select">
                                <option value="">Select Vehicle Type</option>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="car">Car</option>
                                <option value="other">Other</option>
                            </select>
                            @error('vehicle_type') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Number</label>
                            <input type="text" wire:model.live.debounce.500ms="vehicle_number" class="form-control" placeholder="ABC-1234">
                            @error('vehicle_number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Violation</label>
                        <select wire:model.live="violation" class="form-select">
                            <option value="">Select Violation</option>
                            @foreach($this->violations as $v)
                                <option value="{{ $v['name'] }}">{{ $v['name'] }}</option>
                            @endforeach
                        </select>
                        @error('violation') <span class="text-danger small">{{ $message }}</span> @enderror
                        <div class="form-text mt-2 text-dark">
                            Fine Amount: <strong class="fs-1 text-danger">{{ number_format($fine_amount) }}</strong> PKR
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-file-invoice me-2"></i>Issue Challan</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
