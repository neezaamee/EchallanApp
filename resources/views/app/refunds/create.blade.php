@extends('layouts.app')

@section('page-title', 'Request Refund - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    @if (session('error'))
        <x-falcon.alert variant="danger">
            {{ session('error') }}
        </x-falcon.alert>
    @endif

    <div class="row g-3">
        <div class="col-md-8">
            <x-falcon.card title="Refund Request Form" bodyClass="p-4">
                <x-slot name="headerActions">
                    <x-falcon.button href="{{ route('refunds.index') }}" variant="secondary" size="sm" icon="fas fa-arrow-left">
                        Back to List
                    </x-falcon.button>
                </x-slot>

                <form action="{{ route('refunds.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                    <x-falcon.form-group label="Refund Amount (PKR)" name="amount" required="true" helpText="Maximum allowable refund: PKR {{ number_format($payment->amount, 2) }}">
                        <input type="number" name="amount"
                            class="form-control shadow-none @error('amount') is-invalid @enderror"
                            value="{{ old('amount', $payment->amount) }}" step="0.01"
                            max="{{ $payment->amount }}" required>
                    </x-falcon.form-group>

                    <x-falcon.form-group label="Reason for Refund" name="reason" required="true">
                        <textarea name="reason" class="form-control shadow-none @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason') }}</textarea>
                    </x-falcon.form-group>

                    <div class="d-grid mt-4">
                        <x-falcon.button type="submit" variant="primary" icon="fas fa-undo">
                            Submit Refund Request
                        </x-falcon.button>
                    </div>
                </form>
            </x-falcon.card>
        </div>

        <div class="col-md-4">
            <x-falcon.card title="Payment Details" bodyClass="p-3" headerClass="bg-light">
                <table class="table table-sm table-borderless fs--1 mb-0">
                    <tr>
                        <th class="text-700 py-1" style="width: 120px;">Transaction ID:</th>
                        <td class="font-monospace text-dark py-1">{{ $payment->transaction_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-700 py-1">PSID:</th>
                        <td class="font-monospace text-dark py-1">{{ $payment->psid }}</td>
                    </tr>
                    <tr>
                        <th class="text-700 py-1">Amount:</th>
                        <td class="fw-bold text-dark py-1">PKR {{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-700 py-1">Payment Date:</th>
                        <td class="text-800 py-1">{{ $payment->paid_at->format('d-M-Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th class="text-700 py-1">Citizen:</th>
                        <td class="text-dark py-1">{{ $payment->medicalRequest->citizen->full_name }}</td>
                    </tr>
                </table>
            </x-falcon.card>
        </div>
    </div>
</div>
@endsection
