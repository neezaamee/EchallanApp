@extends('layout.cms-layout')
@section('page-title', 'Medical Requests - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Medical Requests</h4>
            @role('citizen')
                <a href="{{ route('medical-requests.create') }}" class="btn btn-primary">
                    Create New Request
                </a>
            @endrole
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sr No</th>
                                <th>Citizen</th>
                                <th>Medical Center</th>
                                <th>PSID</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                                    <td>
                                        <div>{{ $request->citizen->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $request->citizen->cnic ?? '' }}</small>
                                    </td>
                                    <td>{{ $request->medicalCenter->name ?? 'N/A' }}</td>
                                    <td>{{ $request->psid }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $request->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($request->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $request->status === 'passed' ? 'bg-success' : ($request->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($request->payment_status === 'unpaid')
                                            {{-- Unpaid: Show Pay Now button --}}
                                            <a href="{{ route('payments.create', $request->psid) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-credit-card"></i> Pay Now
                                            </a>
                                        @else
                                            {{-- Paid: Show payment info and receipt options --}}
                                            @php
                                                $latestPayment = $request->latestPayment;
                                            @endphp
                                            @if ($latestPayment)
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Paid
                                                        </span>
                                                        <a href="{{ route('payments.success', $latestPayment->id) }}"
                                                            class="btn btn-outline-primary btn-sm"
                                                            title="View Payment Details">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-download"></i> Receipt
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('payments.receipt.download', $latestPayment->id) }}">
                                                                        <i class="bi bi-file-pdf"></i> Standard PDF
                                                                    </a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('payments.receipt.thermal', $latestPayment->id) }}">
                                                                        <i class="bi bi-printer"></i> Thermal Receipt
                                                                    </a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        TXN: {{ $latestPayment->transaction_id }}
                                                    </small>

                                                    @role('doctor')
                                                        {{-- Doctor-specific actions --}}
                                                        @if ($request->status === 'pending')
                                                            <div class="btn-group btn-group-sm mt-1" role="group">
                                                                <form
                                                                    action="{{ route('medical-requests.update', $request->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" name="action" value="passed"
                                                                        class="btn btn-sm btn-success">
                                                                        <i class="bi bi-check-lg"></i> Pass
                                                                    </button>
                                                                </form>
                                                                <form
                                                                    action="{{ route('medical-requests.update', $request->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" name="action" value="failed"
                                                                        class="btn btn-sm btn-danger">
                                                                        <i class="bi bi-x-lg"></i> Fail
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <span class="badge bg-secondary">Actioned</span>
                                                        @endif
                                                    @endrole
                                                </div>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        No requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($requests->hasPages())
                <div class="card-footer">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
