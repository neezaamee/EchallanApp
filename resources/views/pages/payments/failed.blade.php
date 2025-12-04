@extends('layout.cms-layout')
@section('page-title', 'Payment Failed - ')

@section('cms-main-content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        <i class="bi bi-x-circle fs-1"></i>
                        <h4 class="mb-0 mt-2">Payment Failed</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <strong>Payment Unsuccessful!</strong> Your payment could not be processed at this time.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <strong>Transaction ID:</strong>
                                <div class="text-muted">{{ $payment->transaction_id }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>PSID:</strong>
                                <div>{{ $payment->psid }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <strong>Amount:</strong>
                                <div class="fs-5">{{ $payment->formatted_amount }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Payment Method:</strong>
                                <div>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</div>
                            </div>
                        </div>

                        @if (isset($payment->payment_gateway_response['response_message']))
                            <div class="alert alert-warning">
                                <strong>Error Message:</strong> {{ $payment->payment_gateway_response['response_message'] }}
                            </div>
                        @endif

                        <hr>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Common Reasons for Payment Failure:</h6>
                                <ul class="mb-0">
                                    <li>Insufficient funds in your account</li>
                                    <li>Incorrect payment details</li>
                                    <li>Network connectivity issues</li>
                                    <li>Card expired or blocked</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>What to do next:</strong> Please check your payment details and try again.
                            If the problem persists, contact your bank or try a different payment method.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('medical-requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Requests
                            </a>
                            <a href="{{ route('payments.create', $payment->psid) }}" class="btn btn-primary">
                                <i class="bi bi-arrow-repeat"></i> Try Again
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
