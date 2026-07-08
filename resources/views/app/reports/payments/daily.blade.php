@extends('layouts.app')

@section('page-title', 'Daily Payment Report - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    <x-falcon.card title="Daily Payment Report (Last 30 Days)" bodyClass="p-0">
        <x-slot name="headerActions">
            <x-falcon.button onclick="window.print()" variant="falcon-default" size="sm" icon="fas fa-print">
                Print Report
            </x-falcon.button>
        </x-slot>

        <x-falcon.table>
            <thead class="bg-200 text-900">
                <tr>
                    <th class="ps-3">Date</th>
                    <th>Total Transactions</th>
                    <th>Successful</th>
                    <th>Failed</th>
                    <th class="text-end pe-3">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyPayments as $stat)
                    <tr>
                        <td class="ps-3 align-middle text-dark fw-semi-bold">{{ \Carbon\Carbon::parse($stat->date)->format('d M, Y') }}</td>
                        <td class="align-middle text-700">{{ number_format($stat->total_transactions) }}</td>
                        <td class="align-middle text-success font-medium">{{ number_format($stat->successful) }}</td>
                        <td class="align-middle text-danger font-medium">{{ number_format($stat->failed) }}</td>
                        <td class="align-middle text-end pe-3 fw-bold text-dark">PKR {{ number_format($stat->total_revenue) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted fs--1">No data available for the last 30 days.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-falcon.table>
    </x-falcon.card>
</div>
@endsection
