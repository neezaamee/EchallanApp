@extends('layout.cms-layout')
@section('page-title', 'Medical Center Revenue - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Medical Center Revenue Report</h4>
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
                                <th>Medical Center</th>
                                <th>Total Successful Transactions</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($centerStats as $stat)
                                <tr>
                                    <td>{{ $stat->name }}</td>
                                    <td>{{ number_format($stat->total_transactions) }}</td>
                                    <td class="fw-bold">PKR {{ number_format($stat->total_revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
