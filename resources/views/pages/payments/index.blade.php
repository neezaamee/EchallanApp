@extends('layout.cms-layout')
@section('page-title', 'All Payments - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>All Payments</h4>
            <div>
                <a href="{{ route('payments.search') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-search"></i> Advanced Search
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('payments.export.excel') }}">Export to Excel</a></li>
                        <li><a class="dropdown-item" href="{{ route('payments.export.csv') }}">Export to CSV</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Search & Filter Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('payments.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by PSID or Transaction ID" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-light">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>PSID</th>
                                <th>Citizen</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d-M-Y h:i A') }}</td>
                                    <td><span class="font-monospace small">{{ $payment->transaction_id }}</span></td>
                                    <td><span class="font-monospace small">{{ $payment->psid }}</span></td>
                                    <td>
                                        <div>{{ $payment->medicalRequest->citizen->full_name ?? 'N/A' }}</div>
                                        <small
                                            class="text-muted">{{ $payment->medicalRequest->citizen->cnic ?? '' }}</small>
                                    </td>
                                    <td>PKR {{ number_format($payment->amount) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $payment->status === 'success' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-light"
                                                title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if ($payment->isSuccess())
                                                <button type="button"
                                                    class="btn btn-light dropdown-toggle dropdown-toggle-split"
                                                    data-bs-toggle="dropdown"></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('payments.receipt', $payment->id) }}">View
                                                            Receipt</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('payments.receipt.download', $payment->id) }}">Download
                                                            PDF</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('payments.receipt.thermal', $payment->id) }}">Thermal
                                                            Receipt</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('refunds.create', $payment->id) }}">Request
                                                            Refund</a></li>
                                                </ul>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($payments->hasPages())
                <div class="card-footer">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
