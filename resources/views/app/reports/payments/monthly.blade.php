@extends('layouts.app')

@section('page-title', 'Monthly Revenue Report - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    <x-falcon.card title="Monthly Revenue Report" bodyClass="p-0">
        <x-slot name="headerActions">
            <x-falcon.button onclick="window.print()" variant="falcon-default" size="sm" icon="fas fa-print">
                Print Report
            </x-falcon.button>
        </x-slot>

        <x-falcon.table>
            <thead class="bg-200 text-900">
                <tr>
                    <th class="ps-3">Month</th>
                    <th>Total Transactions</th>
                    <th>Average Amount</th>
                    <th class="text-end pe-3">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlyPayments as $stat)
                    <tr>
                        <td class="ps-3 align-middle text-dark fw-semi-bold">{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y') }}</td>
                        <td class="align-middle text-700">{{ number_format($stat->total_transactions) }}</td>
                        <td class="align-middle text-800">PKR {{ number_format($stat->average_amount, 2) }}</td>
                        <td class="align-middle text-end pe-3 fw-bold text-dark">PKR {{ number_format($stat->total_revenue) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted fs--1">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-falcon.table>
    </x-falcon.card>
</div>
@endsection
