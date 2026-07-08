<div class="row g-3 mb-3">
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="PKR {{ number_format($monthlyRevenue ?? 0, 0) }}"
            label="Monthly Revenue"
            icon="fas fa-money-bill-wave"
            color="success"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="PKR {{ number_format($totalRevenue ?? 0, 0) }}"
            label="Total Revenue"
            icon="fas fa-coins"
            color="primary"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="{{ $pendingRefunds ?? 0 }}"
            label="Pending Refunds"
            icon="fas fa-hand-holding-usd"
            color="danger"
        />
    </div>
    <div class="col-md-6 col-xxl-3">
        <x-falcon.statistic-card 
            value="Secure"
            label="Payment Gateway"
            icon="fas fa-lock"
            color="info"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <x-falcon.card title="Recent Successful Payments" bodyClass="p-0">
            <x-slot name="headerActions">
                <a href="{{ url('/payments') }}" class="btn btn-link btn-sm px-0 fw-bold">
                    View All <span class="fas fa-chevron-right ms-1 fs--2"></span>
                </a>
            </x-slot>

            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Transaction ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th class="text-end pe-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments ?? [] as $payment)
                        @php /** @var \App\Models\Payment $payment */ @endphp
                        <tr>
                            <td class="ps-3 align-middle fw-bold text-dark">{{ $payment->transaction_id }}</td>
                            <td class="align-middle text-dark">PKR {{ number_format($payment->amount, 2) }}</td>
                            <td class="align-middle">
                                <x-falcon.badge variant="success">
                                    {{ ucfirst($payment->payment_method) }}
                                </x-falcon.badge>
                            </td>
                            <td class="align-middle text-end pe-3 text-muted fs--2">{{ $payment->paid_at ? $payment->paid_at->format('M d, H:i') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No recent payments recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>
        </x-falcon.card>
    </div>

    <div class="col-lg-4">
        <x-falcon.card title="Financial Tools" bodyClass="p-3" headerClass="bg-light">
            <div class="d-grid gap-2">
                <x-falcon.button href="{{ route('reports.payments.daily') }}" variant="falcon-success" class="text-start" icon="fas fa-file-invoice-dollar">
                    Daily Revenue Report
                </x-falcon.button>
                <x-falcon.button href="{{ url('/refunds') }}" variant="falcon-danger" class="text-start" icon="fas fa-hand-holding-usd">
                    Manage Refunds
                </x-falcon.button>
            </div>
        </x-falcon.card>
    </div>
</div>
