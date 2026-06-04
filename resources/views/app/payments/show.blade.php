@extends('layouts.app')
@section('page-title', 'Payment Details')

@section('cms-main-content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Details</h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($payment->isSuccess())
                            <div class="display-1 text-success mb-2"><i class="bi bi-check-circle"></i></div>
                            <h3 class="text-success">Payment Successful</h3>
                        @else
                            <div class="display-1 text-danger mb-2"><i class="bi bi-x-circle"></i></div>
                            <h3 class="text-danger">Payment Failed</h3>
                        @endif
                        <p class="text-muted">Transaction ID: {{ $payment->transaction_id }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>PSID:</strong>
                            <p class="lead">{{ $payment->psid }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Amount:</strong>
                            <p class="lead">{{ number_format($payment->amount, 2) }} PKR</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Payer Name</label>
                            <p class="fw-bold">{{ $payment->challan_id ? $payment->challan->violator_name : ($payment->medicalRequest?->citizen?->full_name ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Payer CNIC / Vehicle</label>
                            <p>{{ $payment->challan_id ? $payment->challan->vehicle_number : ($payment->medicalRequest?->citizen?->cnic ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Payment Method</label>
                            <p>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Date & Time</label>
                            <p>{{ $payment->created_at->format('d M, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($payment->isSuccess())
                    <hr>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('payments.receipt.download', $payment->id) }}" class="btn btn-primary">
                            <i class="bi bi-file-pdf"></i> Download PDF
                        </a>
                        <a href="{{ route('payments.receipt.thermal', $payment->id) }}" class="btn btn-secondary">
                            <i class="bi bi-printer"></i> Thermal Print
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
