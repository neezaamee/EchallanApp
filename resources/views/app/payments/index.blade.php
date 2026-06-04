@extends('layouts.app')
@section('page-title', 'All Payments - ')

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Payments Overview</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
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
                    <a href="{{ route('payments.search') }}" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-search" data-fa-transform="shrink-3"></span>
                        <span class="d-none d-sm-inline-block ms-1">Advanced Search</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 border-bottom bg-light">
            <form action="{{ route('payments.index') }}" method="GET" class="row g-2 justify-content-start align-items-center">
                <div class="col-sm-auto">
                    <div class="position-relative">
                        <input class="form-control form-control-sm shadow-none search" type="search" name="search" placeholder="Search PSID/TxID..." value="{{ request('search') }}" />
                        <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                    </div>
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
                    <button type="submit" class="btn btn-falcon-primary btn-sm">Filter</button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('payments.index') }}" class="btn btn-falcon-default btn-sm ms-1">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
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
                <tbody class="list">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="ps-3 text-nowrap">{{ $payment->created_at->format('d M, Y h:i A') }}</td>
                            <td><span class="font-monospace fw-semi-bold">{{ $payment->psid }}</span></td>
                            <td><span class="font-monospace small text-muted text-truncate d-inline-block" style="max-width: 120px;">{{ $payment->transaction_id }}</span></td>
                            <td>
                                @if($payment->challan_id)
                                    <div class="fw-bold text-dark">{{ $payment->challan->violator_name }}</div>
                                    <small class="text-muted">{{ $payment->challan->vehicle_number }} (Challan)</small>
                                @else
                                    <div class="fw-bold text-dark">{{ $payment->medicalRequest?->citizen?->full_name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $payment->medicalRequest?->citizen?->cnic ?? '' }}</small>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">PKR {{ number_format($payment->amount) }}</td>
                            <td><span class="badge badge-soft-secondary text-dark fs--2">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</span></td>
                            <td>
                                @php
                                    $statusClass = $payment->status === 'success' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger');
                                @endphp
                                <span class="badge badge-soft-{{ $statusClass }} text-{{ $statusClass }} fs--2">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="dropdown font-sans-serif">
                                    <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" id="paymentAction{{ $payment->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fas fa-ellipsis-h fs--1"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end border py-0" aria-labelledby="paymentAction{{ $payment->id }}">
                                        <div class="bg-white py-2">
                                            <a class="dropdown-item" href="{{ route('payments.show', $payment->id) }}">View Details</a>
                                            @if ($payment->isSuccess())
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('payments.receipt', $payment->id) }}">View Receipt</a>
                                                <a class="dropdown-item" href="{{ route('payments.receipt.download', $payment->id) }}">Download PDF</a>
                                                <a class="dropdown-item text-danger" href="{{ route('refunds.create', $payment->id) }}">Request Refund</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-5 text-muted">
                                <i class="fas fa-money-bill-wave fa-2x mb-3 d-block opacity-25"></i>
                                No payments found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($payments->hasPages())
        <div class="card-footer bg-light py-2">
            <div class="d-flex justify-content-end">
                {{ $payments->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
