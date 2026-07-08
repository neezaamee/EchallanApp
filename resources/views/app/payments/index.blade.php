@extends('layouts.app')

@section('page-title', 'All Payments - ')

@section('cms-main-content')
<x-falcon.card title="Payments Overview" bodyClass="p-0">
    <x-slot name="headerActions">
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown font-sans-serif">
                <button class="btn btn-falcon-default btn-sm dropdown-toggle" id="exportDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fas fa-external-link-alt" data-fa-transform="shrink-3"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="exportDropdown">
                    <div class="bg-white py-2">
                        <a class="dropdown-item" href="{{ route('payments.export.excel') }}">Export to Excel</a>
                        <a class="dropdown-item" href="{{ route('payments.export.csv') }}">Export to CSV</a>
                    </div>
                </div>
            </div>
            <x-falcon.button href="{{ route('payments.search') }}" variant="falcon-default" size="sm" icon="fas fa-search">
                Advanced Search
            </x-falcon.button>
        </div>
    </x-slot>

    {{-- Filter Panel --}}
    <x-falcon.filter-panel>
        <form action="{{ route('payments.index') }}" method="GET" class="row g-2 justify-content-start align-items-center w-100 m-0 p-0">
            <div class="col-sm-auto">
                <x-falcon.search-box placeholder="Search PSID/TxID..." name="search" value="{{ request('search') }}" />
            </div>
            <div class="col-sm-auto">
                <select name="status" class="form-select form-select-sm shadow-none">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <x-falcon.button type="submit" variant="falcon-primary" size="sm">
                    Filter
                </x-falcon.button>
                @if(request()->anyFilled(['search', 'status']))
                    <x-falcon.button href="{{ route('payments.index') }}" variant="falcon-default" size="sm" class="ms-1">
                        Clear
                    </x-falcon.button>
                @endif
            </div>
        </form>
    </x-falcon.filter-panel>

    {{-- Table Component --}}
    <x-falcon.table>
        <thead class="bg-200 text-900">
            <tr>
                <th class="ps-3">Date</th>
                <th>PSID</th>
                <th>Transaction ID</th>
                <th>Citizen</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td class="ps-3 text-muted fs--2">{{ $payment->created_at->format('d M, Y h:i A') }}</td>
                    <td><span class="font-monospace fw-bold text-dark">{{ $payment->psid }}</span></td>
                    <td><span class="font-monospace small text-muted text-truncate d-inline-block" style="max-width: 120px;" title="{{ $payment->transaction_id }}">{{ $payment->transaction_id }}</span></td>
                    <td>
                        @if($payment->challan_id)
                            <div class="fw-bold text-dark">{{ $payment->challan->violator_name }}</div>
                            <small class="text-muted fs--2">{{ $payment->challan->vehicle_number }} (Challan)</small>
                        @else
                            <div class="fw-bold text-dark">{{ $payment->medicalRequest?->citizen?->full_name ?? 'N/A' }}</div>
                            <small class="text-muted fs--2">{{ $payment->medicalRequest?->citizen?->cnic ?? '' }}</small>
                        @endif
                    </td>
                    <td class="fw-bold text-dark">PKR {{ number_format($payment->amount) }}</td>
                    <td><x-falcon.badge variant="secondary">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</x-falcon.badge></td>
                    <td>
                        @php
                            $statusClass = $payment->status === 'success' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger');
                        @endphp
                        <x-falcon.badge :variant="$statusClass">{{ ucfirst($payment->status) }}</x-falcon.badge>
                    </td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown :viewRoute="route('payments.show', $payment->id)">
                            @if ($payment->isSuccess())
                                <a class="dropdown-item text-800" href="{{ route('payments.receipt', $payment->id) }}">
                                    <span class="fas fa-receipt text-info me-2 fs--2"></span>View Receipt
                                </a>
                                <a class="dropdown-item text-800" href="{{ route('payments.receipt.download', $payment->id) }}">
                                    <span class="fas fa-file-pdf text-danger me-2 fs--2"></span>Download PDF
                                </a>
                                <a class="dropdown-item text-danger" href="{{ route('refunds.create', $payment->id) }}">
                                    <span class="fas fa-undo text-danger me-2 fs--2"></span>Request Refund
                                </a>
                            @endif
                        </x-falcon.action-dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center p-5 text-muted fs--1">
                        <i class="fas fa-money-bill-wave fa-2x mb-3 d-block opacity-25"></i>
                        No payments found matching your criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    @if ($payments->hasPages())
        <x-slot name="footer">
            <x-falcon.pagination>
                {{ $payments->links() }}
            </x-falcon.pagination>
        </x-slot>
    @endif
</x-falcon.card>
@endsection
