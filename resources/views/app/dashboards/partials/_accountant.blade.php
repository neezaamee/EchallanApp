<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">PKR {{ number_format($monthlyRevenue ?? 0, 0) }}</h5>
                        <h6 class="text-700 mb-0">Monthly Revenue</h6>
                    </div>
                    <div class="fs-4 text-success"><span class="fas fa-money-bill-wave"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">PKR {{ number_format($totalRevenue ?? 0, 0) }}</h5>
                        <h6 class="text-700 mb-0">Total Revenue</h6>
                    </div>
                    <div class="fs-4 text-primary"><span class="fas fa-coins"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ $pendingRefunds ?? 0 }}</h5>
                        <h6 class="text-700 mb-0">Pending Refunds</h6>
                    </div>
                    <div class="fs-4 text-danger"><span class="fas fa-undo"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1">Secure</h5>
                        <h6 class="text-700 mb-0">Payment Gateway</h6>
                    </div>
                    <div class="fs-4 text-info"><span class="fas fa-lock"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Successful Payments</h5>
                <a href="{{ url('/admin/payments') }}" class="btn btn-link btn-sm px-0">View All <span class="fas fa-chevron-right ms-1 fs-11"></span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-10 mb-0">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th class="text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments ?? [] as $payment)
                                <tr>
                                    <td class="align-middle white-space-nowrap">{{ $payment->transaction_id }}</td>
                                    <td class="align-middle white-space-nowrap">PKR {{ number_format($payment->amount, 2) }}</td>
                                    <td class="align-middle white-space-nowrap">{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="align-middle white-space-nowrap text-end">{{ $payment->paid_at ? $payment->paid_at->format('M d, H:i') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No recent payments recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Financial Tools</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.payments.daily') }}" class="btn btn-soft-success btn-sm text-start">
                        <span class="fas fa-file-invoice-dollar me-2"></span> Daily Revenue Report
                    </a>
                    <a href="{{ url('/admin/refunds') }}" class="btn btn-soft-danger btn-sm text-start">
                        <span class="fas fa-hand-holding-usd me-2"></span> Manage Refunds
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
