@extends('layout.cms-layout')
@section('page-title', 'Request Refund - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Request Refund</h4>
            <a href="{{ route('refunds.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Refund Request Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('refunds.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                            <div class="mb-3">
                                <label class="form-label">Refund Amount (PKR)</label>
                                <input type="number" name="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $payment->amount) }}" step="0.01"
                                    max="{{ $payment->amount }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum: PKR {{ number_format($payment->amount, 2) }}</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Reason for Refund</label>
                                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Submit Refund Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Payment Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Transaction ID:</th>
                                <td><span class="font-monospace small">{{ $payment->transaction_id }}</span></td>
                            </tr>
                            <tr>
                                <th>PSID:</th>
                                <td><span class="font-monospace small">{{ $payment->psid }}</span></td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td>PKR {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Payment Date:</th>
                                <td>{{ $payment->paid_at->format('d-M-Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Citizen:</th>
                                <td>{{ $payment->medicalRequest->citizen->full_name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
