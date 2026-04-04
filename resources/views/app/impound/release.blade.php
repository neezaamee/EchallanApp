@extends('layouts.app')

@section('cms-main-content')
<div class="row min-vh-75 flex-center g-0">
    <div class="col-lg-8 col-xxl-6">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Release Vehicle: {{ strtoupper($challan->vehicle_number) }}</h5>
                <span class="badge badge-soft-success fs--1">Payment Verified</span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6 border-end-md">
                        <h6 class="text-500 text-uppercase fs--2 fw-bold">Vehicle Details</h6>
                        <p class="mb-1"><strong>Reason:</strong> {{ $challan->violation_name }}</p>
                        <p class="mb-1 text-500 fs--1">Seized at: {{ $challan->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="col-md-6 bg-light p-3 rounded-3">
                        <h6 class="text-500 text-uppercase fs--2 fw-bold">Violator Recorded</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $challan->violator_name }}</p>
                        <p class="mb-1"><strong>CNIC:</strong> {{ $challan->violator_cnic }}</p>
                        <button type="button" class="btn btn-link p-0 fs--1" onclick="copyViolatorInfo()">
                            <span class="fas fa-copy me-1"></span>Use these details for receiver
                        </button>
                    </div>
                </div>

                <form action="{{ route('impound.release.submit', $challan->id) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="mb-2">Receiver Information</h6>
                            <hr class="mt-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="receiver_name">Receiver Name</label>
                            <input class="form-control @error('receiver_name') is-invalid @enderror" id="receiver_name" name="receiver_name" type="text" value="{{ old('receiver_name') }}" required />
                            @error('receiver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="receiver_father_name">Father Name</label>
                            <input class="form-control @error('receiver_father_name') is-invalid @enderror" id="receiver_father_name" name="receiver_father_name" type="text" value="{{ old('receiver_father_name') }}" required />
                            @error('receiver_father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="receiver_cnic">Receiver CNIC (13 Digits)</label>
                            <input class="form-control @error('receiver_cnic') is-invalid @enderror" id="receiver_cnic" name="receiver_cnic" type="text" value="{{ old('receiver_cnic') }}" placeholder="35201XXXXXXXX" required />
                            @error('receiver_cnic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4 border-top pt-3">
                            <div class="alert alert-soft-info fs--1 py-2">
                                <span class="fas fa-info-circle me-2"></span>
                                By clicking 'Confirm Release', you verify that the physical vehicle has been handed over.
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="submit">
                                    <span class="fas fa-car-side me-2"></span>Confirm Release Vehicle
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-link text-500">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function copyViolatorInfo() {
        document.getElementById('receiver_name').value = "{{ $challan->violator_name }}";
        document.getElementById('receiver_father_name').value = "N/A"; // Father name wasn't in challan table, but we can try to guess or just leave it
        document.getElementById('receiver_cnic').value = "{{ $challan->violator_cnic }}";
    }
</script>
@endsection
