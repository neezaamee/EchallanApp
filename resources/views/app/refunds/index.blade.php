@extends('layouts.app')

@section('page-title', 'Refund Management - ')

@section('cms-main-content')
<x-falcon.card title="Refund Management" bodyClass="p-0">
    <x-falcon.table>
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
        <tbody>
            @forelse($refunds as $refund)
                <tr>
                    <td class="ps-3"><x-falcon.badge variant="secondary" class="font-monospace fs--2">{{ $refund->refund_transaction_id }}</x-falcon.badge></td>
                    <td><small class="text-muted font-monospace fs--2">{{ $refund->payment->transaction_id }}</small></td>
                    <td>
                        <div class="fw-bold text-dark">{{ $refund->payment->medicalRequest->citizen->full_name ?? 'N/A' }}</div>
                    </td>
                    <td class="fw-bold text-dark">{{ number_format($refund->amount) }} PKR</td>
                    <td><small class="text-700">{{ $refund->requestedBy->name }}</small></td>
                    <td>
                        @php
                            $statusColor = 'secondary';
                            if($refund->status === 'pending') $statusColor = 'warning';
                            elseif($refund->status === 'approved') $statusColor = 'info';
                            elseif($refund->status === 'completed') $statusColor = 'success';
                            elseif($refund->status === 'rejected') $statusColor = 'danger';
                        @endphp
                        <x-falcon.badge :variant="$statusColor">{{ ucfirst($refund->status) }}</x-falcon.badge>
                    </td>
                    <td class="text-end pe-3">
                        <x-falcon.action-dropdown :viewRoute="route('refunds.show', $refund)">
                            @if ($refund->status === 'pending' && auth()->user()->hasRole('super_admin|admin|accountant'))
                                <a class="dropdown-item text-success" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to approve this refund?')) document.getElementById('approve-form-{{ $refund->id }}').submit();">
                                    <span class="fas fa-check-circle text-success me-2 fs--2"></span>Approve
                                </a>
                                <form id="approve-form-{{ $refund->id }}" action="{{ route('refunds.approve', $refund) }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to reject this refund?')) document.getElementById('reject-form-{{ $refund->id }}').submit();">
                                    <span class="fas fa-times-circle text-danger me-2 fs--2"></span>Reject
                                </a>
                                <form id="reject-form-{{ $refund->id }}" action="{{ route('refunds.reject', $refund) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        </x-falcon.action-dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-5 text-muted fs--1">
                        <i class="fas fa-undo fa-2x mb-3 d-block opacity-25"></i>
                        No refund requests found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-falcon.table>

    @if ($refunds->hasPages())
        <x-slot name="footer">
            <x-falcon.pagination>
                {{ $refunds->links() }}
            </x-falcon.pagination>
        </x-slot>
    @endif
</x-falcon.card>
@endsection
