@extends('layout.cms-layout')
@section('page-title', 'Payment - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Payment for Medical Request</h5>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Medical Request Details -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Request Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>PSID:</strong>
                                    <div class="text-primary fs-5">{{ $medicalRequest->psid }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Amount:</strong>
                                    <div class="text-success fs-5">PKR {{ number_format($medicalRequest->amount, 2) }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Citizen:</strong>
                                    <div>{{ $medicalRequest->citizen->full_name }}</div>
                                    <small class="text-muted">{{ $medicalRequest->citizen->cnic }}</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Medical Center:</strong>
                                    <div>{{ $medicalRequest->medicalCenter->name }}</div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Payment Form -->
                        <form action="{{ route('payments.process') }}" method="POST" id="paymentForm">
                            @csrf
                            <input type="hidden" name="psid" value="{{ $medicalRequest->psid }}">

                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Payment Method</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="credit_card" value="credit_card" checked>
                                            <label class="form-check-label" for="credit_card">
                                                <i class="bi bi-credit-card"></i> Credit Card
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="debit_card" value="debit_card">
                                            <label class="form-check-label" for="debit_card">
                                                <i class="bi bi-credit-card-2-front"></i> Debit Card
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="bank_transfer" value="bank_transfer">
                                            <label class="form-check-label" for="bank_transfer">
                                                <i class="bi bi-bank"></i> Bank Transfer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="mobile_wallet" value="mobile_wallet">
                                            <label class="form-check-label" for="mobile_wallet">
                                                <i class="bi bi-phone"></i> Mobile Wallet
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Note:</strong> This is a dummy payment gateway for testing purposes.
                                The payment will be simulated and may succeed or fail randomly.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('medical-requests.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-credit-card"></i> Pay PKR
                                    {{ number_format($medicalRequest->amount, 2) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
