@extends('layout.cms-layout')
@section('page-title', 'Payment Dashboard - ')

@section('cms-main-content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Payment Dashboard</h4>
            <div>
                <span class="text-muted">Last updated: {{ now()->format('h:i A') }}</span>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Today's Revenue</h6>
                                <h2 class="mt-2 mb-0">PKR {{ number_format($todayRevenue) }}</h2>
                            </div>
                            <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Monthly Revenue</h6>
                                <h2 class="mt-2 mb-0">PKR {{ number_format($monthlyRevenue) }}</h2>
                            </div>
                            <i class="bi bi-graph-up fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Today's Transactions</h6>
                                <h2 class="mt-2 mb-0">{{ number_format($todayTransactions) }}</h2>
                            </div>
                            <i class="bi bi-receipt fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div
                    class="card text-white {{ $successRate >= 80 ? 'bg-success' : ($successRate >= 50 ? 'bg-warning' : 'bg-danger') }} h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Success Rate</h6>
                                <h2 class="mt-2 mb-0">{{ $successRate }}%</h2>
                            </div>
                            <i class="bi bi-activity fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Transactions -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time</th>
                                        <th>Transaction ID</th>
                                        <th>Citizen</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('h:i A') }}</td>
                                            <td>
                                                <span class="font-monospace small">{{ $payment->transaction_id }}</span>
                                            </td>
                                            <td>{{ $payment->medicalRequest->citizen->full_name ?? 'N/A' }}</td>
                                            <td>PKR {{ number_format($payment->amount) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $payment->status === 'success' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('payments.show', $payment->id) }}"
                                                    class="btn btn-sm btn-light">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No transactions found today.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Chart -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Methods</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('paymentMethodsChart').getContext('2d');
                const data = @json($paymentMethods);

                const labels = Object.keys(data).map(key => key.replace('_', ' ').toUpperCase());
                const values = Object.values(data);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
