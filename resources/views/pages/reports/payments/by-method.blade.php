@extends('layout.cms-layout')
@section('page-title', 'Payment Method Analysis - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Payment Method Analysis</h4>
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
                                <th>Payment Method</th>
                                <th>Total Transactions</th>
                                <th>Successful</th>
                                <th>Failed</th>
                                <th>Success Rate</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($methodStats as $stat)
                                @php
                                    $successRate =
                                        $stat->total_transactions > 0
                                            ? round(($stat->successful / $stat->total_transactions) * 100, 1)
                                            : 0;
                                @endphp
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $stat->payment_method)) }}</td>
                                    <td>{{ number_format($stat->total_transactions) }}</td>
                                    <td class="text-success">{{ number_format($stat->successful) }}</td>
                                    <td class="text-danger">{{ number_format($stat->failed) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $successRate }}%</span>
                                            <div class="progress flex-grow-1" style="height: 5px; width: 50px;">
                                                <div class="progress-bar bg-{{ $successRate >= 80 ? 'success' : ($successRate >= 50 ? 'warning' : 'danger') }}"
                                                    role="progressbar" style="width: {{ $successRate }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-bold">PKR {{ number_format($stat->total_revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
