@extends('layouts.app')
@section('page-title', 'Refund Management - ')

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Refund Management</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Refund ID</th>
                        <th>Payment TXN</th>
                        <th>Citizen</th>
                        <th>Amount</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($refunds as $refund)
                        <tr>
                            <td class="ps-3"><span class="badge badge-soft-secondary text-dark fs--2 font-monospace">{{ $refund->refund_transaction_id }}</span></td>
                            <td><small class="text-muted font-monospace">{{ $refund->payment->transaction_id }}</small></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $refund->payment->medicalRequest->citizen->full_name ?? 'N/A' }}</div>
                            </td>
                            <td class="fw-bold text-dark">{{ number_format($refund->amount) }} PKR</td>
                            <td><small class="text-muted">{{ $refund->requestedBy->name }}</small></td>
                            <td>
                                @php
                                    $statusColor = 'secondary';
                                    if($refund->status === 'pending') $statusColor = 'warning';
                                    elseif($refund->status === 'approved') $statusColor = 'info';
                                    elseif($refund->status === 'completed') $statusColor = 'success';
                                    elseif($refund->status === 'rejected') $statusColor = 'danger';
                                @endphp
                                <span class="badge badge-soft-{{ $statusColor }} text-{{ $statusColor }} fs--2">{{ ucfirst($refund->status) }}</span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('refunds.show', $refund) }}" class="btn btn-link p-0 text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($refund->status === 'pending' && auth()->user()->hasRole('super_admin|admin|accountant'))
                                        <form action="{{ route('refunds.approve', $refund) }}" method="POST" class="d-inline ms-2">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 text-success" title="Approve Request" onclick="return confirm('Are you sure you want to approve this refund?')">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('refunds.reject', $refund) }}" method="POST" class="d-inline ms-2">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Reject Request" onclick="return confirm('Are you sure you want to reject this refund?')">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5 text-muted">
                                <i class="fas fa-undo fa-2x mb-3 d-block opacity-25"></i>
                                No refund requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($refunds->hasPages())
        <div class="card-footer bg-light py-2">
            <div class="d-flex justify-content-end">
                {{ $refunds->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
