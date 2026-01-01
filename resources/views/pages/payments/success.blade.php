@extends('layout.cms-layout')
@section('page-title', 'Payment Successful - ')

@section('cms-main-content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-success">
                    <div class="card-header bg-success text-white text-center">
                        <i class="bi bi-check-circle fs-1"></i>
                        <h4 class="mb-0 mt-2">Payment Successful!</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <strong>Congratulations!</strong> Your payment has been processed successfully.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <strong>Transaction ID:</strong>
                                <div class="text-primary">{{ $payment->transaction_id }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>PSID:</strong>
                                <div>{{ $payment->psid }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <strong>Amount Paid:</strong>
                                <div class="text-success fs-5">{{ $payment->formatted_amount }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Payment Method:</strong>
                                <div>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <strong>Citizen:</strong>
                                <div>{{ $payment->medicalRequest->citizen->full_name }}</div>
                                <small class="text-muted">{{ $payment->medicalRequest->citizen->cnic }}</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Medical Center:</strong>
                                <div>{{ $payment->medicalRequest->medicalCenter->name }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <strong>Payment Date:</strong>
                                <div>{{ $payment->paid_at->format('F d, Y h:i A') }}</div>
                            </div>
                        </div>

                        <hr>

                        <!-- Receipt Download Section -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-receipt"></i> Payment Receipt
                                </h6>
                                <p class="text-muted small mb-3">Download your payment receipt for your records</p>
                                <div class="d-grid gap-2 d-md-flex">
                                    <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-outline-primary"
                                        target="_blank">
                                        <i class="bi bi-eye"></i> View Receipt
                                    </a>
                                    <a href="{{ route('payments.receipt.download', $payment->id) }}"
                                        class="btn btn-primary">
                                        <i class="bi bi-download"></i> Download PDF
                                    </a>
                                    <a href="{{ route('payments.receipt.thermal', $payment->id) }}"
                                        class="btn btn-outline-secondary">
                                        <i class="bi bi-printer"></i> Thermal Receipt
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Next Steps:</strong> Your medical request is now paid and will be processed by the
                            medical center.
                            Please wait for the doctor's evaluation.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('medical-requests.index') }}" class="btn btn-primary">
                                <i class="bi bi-list"></i> View All Requests
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                <i class="bi bi-house"></i> Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
