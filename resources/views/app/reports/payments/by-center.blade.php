@extends('layouts.app')

@section('page-title', 'Medical Center Revenue - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    <x-falcon.card title="Medical Center Revenue Report" bodyClass="p-0">
        <x-slot name="headerActions">
            <x-falcon.button onclick="window.print()" variant="falcon-default" size="sm" icon="fas fa-print">
                Print Report
            </x-falcon.button>
        </x-slot>

        <x-falcon.table>
            <thead class="bg-200 text-900">
                <tr>
                    <th class="ps-3">Medical Center</th>
                    <th>Total Successful Transactions</th>
                    <th class="text-end pe-3">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centerStats as $stat)
                    <tr>
                        <td class="ps-3 align-middle text-dark fw-bold">{{ $stat->name }}</td>
                        <td class="align-middle text-700">{{ number_format($stat->total_transactions) }}</td>
                        <td class="align-middle text-end pe-3 fw-bold text-dark">PKR {{ number_format($stat->total_revenue) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted fs--1">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-falcon.table>
    </x-falcon.card>
</div>
@endsection
