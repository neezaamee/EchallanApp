@extends('layouts.app')

@section('page-title', 'Payment Details')

@section('cms-main-content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <x-falcon.card title="Payment Details" bodyClass="p-4">
                <x-slot name="headerActions">
                    <x-falcon.button href="{{ route('payments.index') }}" variant="secondary" size="sm" icon="fas fa-arrow-left">
                        Back to List
                    </x-falcon.button>
                </x-slot>

                <div class="text-center mb-4">
                    @if($payment->isSuccess())
                        <div class="fs-4 text-success mb-2"><i class="fas fa-check-circle fs-3"></i></div>
                        <h3 class="text-success fw-bold">Payment Successful</h3>
                    @else
                        <div class="fs-4 text-danger mb-2"><i class="fas fa-times-circle fs-3"></i></div>
                        <h3 class="text-danger fw-bold">Payment Failed</h3>
                    @endif
                    <p class="text-muted fs--1 font-monospace">Transaction ID: {{ $payment->transaction_id }}</p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-500 fs--2 fw-bold text-uppercase">PSID</h6>
                        <p class="fs-1 fw-bold text-dark font-monospace mb-0">{{ $payment->psid }}</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <h6 class="text-500 fs--2 fw-bold text-uppercase">Amount</h6>
                        <p class="fs-1 fw-bold text-dark mb-0">{{ number_format($payment->amount, 2) }} PKR</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-500 fs--2 fw-bold text-uppercase mb-1">Payer Name</h6>
                        <p class="fw-bold text-dark mb-0 fs--1">{{ $payment->challan_id ? $payment->challan->violator_name : ($payment->medicalRequest?->citizen?->full_name ?? 'N/A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-500 fs--2 fw-bold text-uppercase mb-1">Payer CNIC / Vehicle</h6>
                        <p class="text-800 mb-0 fs--1">{{ $payment->challan_id ? $payment->challan->vehicle_number : ($payment->medicalRequest?->citizen?->cnic ?? 'N/A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-500 fs--2 fw-bold text-uppercase mb-1">Payment Method</h6>
                        <p class="text-800 mb-0 fs--1">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-500 fs--2 fw-bold text-uppercase mb-1">Date & Time</h6>
                        <p class="text-800 mb-0 fs--1">{{ $payment->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                </div>

                @if($payment->isSuccess())
                    <hr class="my-4">
                    <div class="d-flex justify-content-center gap-2">
                        <x-falcon.button href="{{ route('payments.receipt.download', $payment->id) }}" variant="primary" icon="fas fa-file-pdf">
                            Download PDF
                        </x-falcon.button>
                        <x-falcon.button href="{{ route('payments.receipt.thermal', $payment->id) }}" variant="secondary" icon="fas fa-print">
                            Thermal Print
                        </x-falcon.button>
                    </div>
                @endif
            </x-falcon.card>
        </div>
    </div>
</div>
@endsection
