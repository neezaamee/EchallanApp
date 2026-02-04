@extends('layouts.app')
@section('page-title', 'Issue Challan')
@section('cms-main-content')
<div class="row g-3 mb-3">
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Issue New Challan</h5>
            </div>
            <div class="card-body bg-light">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                @if(!$dumpingPoint)
                    <div class="alert alert-warning">
                        You are not assigned to a Dumping Point. Please contact admin.
                    </div>
                @else
                
                <form action="{{ route('challans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dumping_point_id" value="{{ $dumpingPoint->id }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Dumping Point (Auto)</label>
                            <input type="text" class="form-control" value="{{ $dumpingPoint->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pick Up Point</label>
                            <select name="pick_up_point_id" class="form-select" required>
                                <option value="">Select Point</option>
                                @foreach($pickUpPoints as $point)
                                    <option value="{{ $point->id }}">{{ $point->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Violator CNIC</label>
                            <input type="text" name="violator_cnic" class="form-control" placeholder="33100-0000000-0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Violator Name</label>
                            <input type="text" name="violator_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Violator Mobile</label>
                            <input type="text" name="violator_mobile" class="form-control" placeholder="0300-0000000" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type</label>
                            <select name="vehicle_type" id="vehicle_type" class="form-select" required>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="car">Car</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Number</label>
                            <input type="text" name="vehicle_number" class="form-control" placeholder="ABC-1234" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Violation</label>
                        <select name="violation" id="violation" class="form-select" required>
                            <option value="">Select Violation</option>
                            @foreach($violations as $v)
                                <option value="{{ $v['name'] }}|{{ $v['fine_bike'] }}" data-car="{{ $v['fine_car'] }}" data-bike="{{ $v['fine_bike'] }}">
                                    {{ $v['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2">
                            Fine Amount: <strong id="fine_display">0</strong> PKR
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Issue Challan</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleTypeSelect = document.getElementById('vehicle_type');
        const violationSelect = document.getElementById('violation');
        const fineDisplay = document.getElementById('fine_display');

        function updateFine() {
            const type = vehicleTypeSelect.value;
            const selectedOption = violationSelect.options[violationSelect.selectedIndex];
            
            if (selectedOption.value) {
                let fine = 0;
                if (type === 'car') {
                    fine = selectedOption.getAttribute('data-car');
                } else {
                    fine = selectedOption.getAttribute('data-bike'); // default to bike for others too for now
                }
                
                // Update the option value to send the correct amount
                selectedOption.value = selectedOption.text.trim() + '|' + fine;
                fineDisplay.textContent = fine;
            } else {
                fineDisplay.textContent = 0;
            }
        }

        vehicleTypeSelect.addEventListener('change', updateFine);
        violationSelect.addEventListener('change', updateFine);
    });
</script>
@endsection
