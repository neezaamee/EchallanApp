@extends('layouts.app')

@section('cms-main-content')
<div class="row min-vh-75 flex-center g-0">
    <div class="col-lg-8 col-xxl-6">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Verification Result for PSID: {{ $challan->psid }}</h5>
                <div>
                    @if($challan->status === 'released')
                        <span class="badge badge-soft-info fs--1">Released</span>
                    @elseif($challan->isPaid())
                        <span class="badge badge-soft-success fs--1">Paid</span>
                    @else
                        <span class="badge badge-soft-warning fs--1">Unpaid</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 border-end-md">
                        <h6 class="text-500 text-uppercase fs--2 fw-bold">Vehicle Info</h6>
                        <p class="mb-1"><strong>Number:</strong> {{ strtoupper($challan->vehicle_number) }}</p>
                        <p class="mb-1"><strong>Type:</strong> {{ ucfirst($challan->vehicle_type) }}</p>
                        <p class="mb-1"><strong>Reason:</strong> {{ $challan->violation_name }}</p>
                        <p class="mb-1 text-500 fs--1">Seized at: {{ $challan->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-500 text-uppercase fs--2 fw-bold">Violator Info</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $challan->violator_name }}</p>
                        <p class="mb-1"><strong>CNIC:</strong> {{ $challan->violator_cnic }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $challan->violator_mobile }}</p>
                    </div>

                    <div class="col-12">
                        <hr class="my-2">
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-500 text-uppercase fs--2 fw-bold">Payment Details</h6>
                        <p class="mb-1"><strong>Amount:</strong> Rs. {{ number_format($challan->fine_amount) }}</p>
                        <p class="mb-1"><strong>Status:</strong> 
                            <span class="text-{{ $challan->isPaid() ? 'success' : 'danger' }} fw-bold">
                                {{ strtoupper($challan->payment_status) }}
                            </span>
                        </p>
                        @if($challan->transaction_id)
                            <p class="mb-1 text-500 fs--1">Trace ID: {{ $challan->transaction_id }}</p>
                        @endif
                    </div>

                    <div class="col-md-6 bg-light rounded-3 p-3">
                        <h6 class="text-uppercase fs--2 fw-bold">Officer Action</h6>
                        @if($challan->status === 'released')
                            <div class="alert alert-info py-2 mb-0 fs--1">
                                <p class="mb-1"><strong>Released to:</strong> {{ $challan->receiver_name }}</p>
                                <p class="mb-0 text-500">at {{ $challan->released_at->format('d M Y, h:i A') }}</p>
                                <p class="mb-0 text-500">by {{ $challan->releaseOfficer?->fullName() ?? 'System' }}</p>
                            </div>
                        @elseif($challan->isPaid())
                            <div class="d-grid">
                                <a href="{{ route('impound.release.form', $challan->id) }}" class="btn btn-primary">
                                    Proceed to Release Vehicle
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning py-2 mb-0 fs--1">
                                <span class="fas fa-exclamation-triangle me-2"></span>
                                Payment not found in system. Please ask user to pay via PSID.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('impound.check-status.form') }}" class="btn btn-link">Check Another PSID</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-link">Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
