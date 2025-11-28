<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Transfer / Post Staff</h5>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Staff Member <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model.live="staff_id">
                        <option value="">-- Select Staff --</option>
                        @foreach($staff_list as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->fullName() }} ({{ $staff->cnic }})</option>
                        @endforeach
                    </select>
                    @error('staff_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model="start_date" class="form-control">
                    @error('start_date') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Location Type <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model.live="location_type">
                        <option value="">-- Select Location Type --</option>
                        <option value="province">Province</option>
                        <option value="city">City</option>
                        <option value="circle">Circle</option>
                        <option value="dumping_point">Dumping Point</option>
                        <option value="medical_center">Medical Center</option>
                    </select>
                    @error('location_type') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            @if($location_type === 'province')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Province <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="province_id">
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('province_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($location_type === 'city')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Province <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model.live="province_id">
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="city_id">
                            <option value="">-- Select City --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('city_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($location_type === 'circle')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Province</label>
                        <select class="form-select" wire:model.live="province_id">
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>
                        <select class="form-select" wire:model.live="city_id">
                            <option value="">-- Select City --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Circle <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="circle_id">
                            <option value="">-- Select Circle --</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                            @endforeach
                        </select>
                        @error('circle_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($location_type === 'dumping_point')
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Province</label>
                        <select class="form-select" wire:model.live="province_id">
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>
                        <select class="form-select" wire:model.live="city_id">
                            <option value="">-- Select City --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Circle</label>
                        <select class="form-select" wire:model.live="circle_id">
                            <option value="">-- Select Circle --</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Dumping Point <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="dumping_point_id">
                            <option value="">-- Select Dumping Point --</option>
                            @foreach($dumping_points as $dp)
                                <option value="{{ $dp->id }}">{{ $dp->name }}</option>
                            @endforeach
                        </select>
                        @error('dumping_point_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($location_type === 'medical_center')
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Province</label>
                        <select class="form-select" wire:model.live="province_id">
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>
                        <select class="form-select" wire:model.live="city_id">
                            <option value="">-- Select City --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Circle</label>
                        <select class="form-select" wire:model.live="circle_id">
                            <option value="">-- Select Circle --</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Medical Center <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="medical_center_id">
                            <option value="">-- Select Medical Center --</option>
                            @foreach($medical_centers as $mc)
                                <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                            @endforeach
                        </select>
                        @error('medical_center_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('staff-postings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Post / Transfer Staff
                </button>
            </div>
        </form>
    </div>
</div>
