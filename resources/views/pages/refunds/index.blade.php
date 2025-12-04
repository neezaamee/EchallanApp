@extends('layout.cms-layout')
@section('page-title', 'Refund Management - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Refund Management</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Refund ID</th>
                                <th>Payment TXN</th>
                                <th>Citizen</th>
                                <th>Amount</th>
                                <th>Requested By</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($refunds as $refund)
                                <tr>
                                    <td><span class="font-monospace small">{{ $refund->refund_transaction_id }}</span></td>
                                    <td><span class="font-monospace small">{{ $refund->payment->transaction_id }}</span>
                                    </td>
                                    <td>{{ $refund->payment->medicalRequest->citizen->full_name ?? 'N/A' }}</td>
                                    <td>PKR {{ number_format($refund->amount) }}</td>
                                    <td>{{ $refund->requestedBy->name }}</td>
                                    <td>
                                        @if ($refund->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($refund->status === 'approved')
                                            <span class="badge bg-info">Approved</span>
                                        @elseif($refund->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $refund->created_at->format('d-M-Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('refunds.show', $refund) }}" class="btn btn-light">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @if ($refund->status === 'pending' && auth()->user()->hasRole('super_admin|admin|accountant'))
                                                <form action="{{ route('refunds.approve', $refund) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Approve this refund?')">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('refunds.reject', $refund) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Reject this refund?')">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No refund requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($refunds->hasPages())
                <div class="card-footer">
                    {{ $refunds->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
