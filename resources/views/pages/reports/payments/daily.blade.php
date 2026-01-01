@extends('layout.cms-layout')
@section('page-title', 'Daily Payment Report - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daily Payment Report (Last 30 Days)</h4>
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
                                <th>Date</th>
                                <th>Total Transactions</th>
                                <th>Successful</th>
                                <th>Failed</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyPayments as $stat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($stat->date)->format('d M, Y') }}</td>
                                    <td>{{ number_format($stat->total_transactions) }}</td>
                                    <td class="text-success">{{ number_format($stat->successful) }}</td>
                                    <td class="text-danger">{{ number_format($stat->failed) }}</td>
                                    <td class="fw-bold">PKR {{ number_format($stat->total_revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No data available for the last 30 days.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
