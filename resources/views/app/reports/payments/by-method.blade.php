@extends('layouts.app')

@section('page-title', 'Payment Method Analysis - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    <x-falcon.card title="Payment Method Analysis" bodyClass="p-0">
        <x-slot name="headerActions">
            <x-falcon.button onclick="window.print()" variant="falcon-default" size="sm" icon="fas fa-print">
                Print Report
            </x-falcon.button>
        </x-slot>

        <x-falcon.table>
            <thead class="bg-200 text-900">
                <tr>
                    <th class="ps-3">Payment Method</th>
                    <th>Total Transactions</th>
                    <th>Successful</th>
                    <th>Failed</th>
                    <th>Success Rate</th>
                    <th class="text-end pe-3">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($methodStats as $stat)
                    @php
                        $successRate = $stat->total_transactions > 0 
                            ? round(($stat->successful / $stat->total_transactions) * 100, 1) 
                            : 0;
                    @endphp
                    <tr>
                        <td class="ps-3 align-middle text-dark fw-bold">{{ ucwords(str_replace('_', ' ', $stat->payment_method)) }}</td>
                        <td class="align-middle text-700">{{ number_format($stat->total_transactions) }}</td>
                        <td class="align-middle text-success font-medium">{{ number_format($stat->successful) }}</td>
                        <td class="align-middle text-danger font-medium">{{ number_format($stat->failed) }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <span class="me-2 fw-semi-bold text-dark fs--1">{{ $successRate }}%</span>
                                <div class="progress flex-grow-1" style="height: 5px; width: 80px; min-width: 50px;">
                                    <div class="progress-bar bg-{{ $successRate >= 80 ? 'success' : ($successRate >= 50 ? 'warning' : 'danger') }}" 
                                        role="progressbar" style="width: {{ $successRate }}%" aria-valuenow="{{ $successRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-end pe-3 fw-bold text-dark">PKR {{ number_format($stat->total_revenue) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted fs--1">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-falcon.table>
    </x-falcon.card>
</div>
@endsection
