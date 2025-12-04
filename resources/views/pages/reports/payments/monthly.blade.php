@extends('layout.cms-layout')
@section('page-title', 'Monthly Revenue Report - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Monthly Revenue Report</h4>
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th>Total Transactions</th>
                                <th>Average Amount</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyPayments as $stat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y') }}</td>
                                    <td>{{ number_format($stat->total_transactions) }}</td>
                                    <td>PKR {{ number_format($stat->average_amount, 2) }}</td>
                                    <td class="fw-bold">PKR {{ number_format($stat->total_revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
