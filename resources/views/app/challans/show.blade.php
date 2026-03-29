@extends('layouts.app')

@section('page-title', 'Challan Details')

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-sm-auto">
                <h5 class="mb-2 mb-md-0">Challan Details - PSID: <span class="text-primary">{{ $challan->psid }}</span></h5>
            </div>
            <div class="col-sm-auto">
                <a href="{{ route('dashboard') }}" class="btn btn-falcon-default btn-sm me-2">
                    <span class="fas fa-arrow-left me-1"></span> Back to Dashboard
                </a>
                @if($challan->payment_status === 'paid')
                    <span class="badge badge-soft-success fs-9 rounded-pill"><span class="fas fa-check-circle me-1"></span> Paid</span>
                @else
                    <span class="badge badge-soft-warning fs-9 rounded-pill"><span class="fas fa-clock me-1"></span> Unpaid</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-uppercase text-600 fs-11">Vehicle Information</h6>
                        <div class="p-3 bg-light rounded-3">
                            <p class="mb-1 text-900 fw-semi-bold">{{ strtoupper($challan->vehicle_number) }}</p>
                            <p class="mb-0 text-700 fs-10">{{ ucfirst($challan->vehicle_type) }} | {{ $challan->violation_name }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-uppercase text-600 fs-11">Impound Location</h6>
                        <div class="p-3 bg-light rounded-3">
                            <p class="mb-1 text-900 fw-semi-bold">{{ $challan->dumpingPoint?->name ?? 'N/A' }}</p>
                            <p class="mb-0 text-700 fs-10">{{ $challan->dumpingPoint?->location ?? 'City Traffic Center' }}</p>
                        </div>
                    </div>
                </div>

                <h6 class="text-uppercase text-600 fs-11 mt-4">Fine Details</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless fs-10">
                        <tbody>
                            <tr>
                                <td class="ps-0 py-1 text-700">Violation Fee:</td>
                                <td class="pe-0 py-1 text-end fw-bold">Rs. {{ number_format($challan->fine_amount) }}</td>
                            </tr>
                            <tr class="border-top border-200">
                                <td class="ps-0 py-2 text-900 fw-bold fs-9">Total Amount:</td>
                                <td class="pe-0 py-2 text-end fw-bold fs-9">Rs. {{ number_format($challan->fine_amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($challan->payment_status !== 'paid')
                    <div class="alert alert-info border-2 d-flex align-items-center mb-0 mt-4" role="alert">
                        <div class="bg-info me-3 icon-item"><span class="fas fa-info-circle text-white fs-8"></span></div>
                        <p class="mb-0 flex-1">Please pay this challan via <strong>1Link Channel</strong> (any bank mobile app or ATM) using your PSID. Once the payment is processed at the bank, our system will update automatically.</p>
                    </div>
                @else
                   <div class="alert alert-success border-2 d-flex align-items-center mb-0 mt-4" role="alert">
                        <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-8"></span></div>
                        <p class="mb-0 flex-1">This challan has been paid via 1Link. You can now visit the impound center to release your vehicle.</p>
                    </div>
                @endif
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Violator Summary</h6>
                    </div>
                    <div class="card-body fs-10">
                        <p class="mb-2"><strong>Name:</strong> {{ $challan->violator_name }}</p>
                        <p class="mb-2"><strong>CNIC:</strong> {{ $challan->violator_cnic }}</p>
                        <p class="mb-2"><strong>Mobile:</strong> {{ $challan->violator_mobile }}</p>
                        <hr>
                        <p class="mb-1 text-500">Issued On:</p>
                        <p class="mb-0">{{ $challan->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
