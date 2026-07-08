@extends('layouts.app')
@section('page-title', 'Medical Requests - ')

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Medical Requests</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('medical-requests.index') }}" method="GET" class="position-relative">
                        <input class="form-control form-control-sm shadow-none search" type="search" name="search" placeholder="Search Requests..." value="{{ request('search') }}" />
                        <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                    </form>
                    @role('citizen')
                        <a href="{{ route('medical-requests.create') }}" class="btn btn-falcon-default btn-sm">
                            <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">Create New</span>
                        </a>
                    @endrole
                </div>
            </div>
        </div>
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

    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="white-space-nowrap ps-3">No.</th>
                        <th>Citizen</th>
                        <th>Medical Center</th>
                        <th>PSID</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($requests as $request)
                        <tr>
                            <td class="text-muted ps-3">{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $request->citizen->full_name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $request->citizen->cnic ?? '' }}</small>
                            </td>
                            <td>{{ $request->medicalCenter->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-soft-secondary text-dark fs--2 font-monospace">{{ $request->psid }}</span></td>
                            <td>
                                <span class="badge badge-soft-{{ $request->payment_status === 'paid' ? 'success' : 'danger' }} text-{{ $request->payment_status === 'paid' ? 'success' : 'danger' }} fs--2">
                                    {{ ucfirst($request->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusColor = 'warning';
                                    if($request->status === 'passed') $statusColor = 'success';
                                    elseif($request->status === 'failed') $statusColor = 'danger';
                                @endphp
                                <span class="badge badge-soft-{{ $statusColor }} text-{{ $statusColor }} fs--2">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    @if ($request->payment_status === 'unpaid')
                                        @can('payments:process')
                                        <a href="{{ route('payments.create', $request->psid) }}" class="btn btn-link p-0 text-success" title="Pay Now">
                                            <i class="fas fa-credit-card"></i>
                                        </a>
                                        @endcan
                                    @else
                                        @php $latestPayment = $request->latestPayment; @endphp
                                        @if ($latestPayment)
                                            <a href="{{ route('payments.success', $latestPayment->id) }}" class="btn btn-link p-0 text-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <div class="dropdown font-sans-serif ms-2">
                                                <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-file-invoice"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end border py-0">
                                                    <div class="bg-white py-2">
                                                        <a class="dropdown-item" href="{{ route('payments.receipt.download', $latestPayment->id) }}">Standard PDF</a>
                                                        <a class="dropdown-item" href="{{ route('payments.receipt.thermal', $latestPayment->id) }}">Thermal Receipt</a>
                                                    </div>
                                                </div>
                                            </div>

                                            @role('doctor')
                                                @if ($request->status === 'pending')
                                                    <form action="{{ route('medical-requests.update', $request->id) }}" method="POST" class="d-inline ms-2">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="action" value="passed">
                                                        <button type="submit" class="btn btn-link p-0 text-success" title="Pass"><i class="fas fa-check"></i></button>
                                                    </form>
                                                    <form action="{{ route('medical-requests.update', $request->id) }}" method="POST" class="d-inline ms-1">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="action" value="failed">
                                                        <button type="submit" class="btn btn-link p-0 text-danger" title="Fail"><i class="fas fa-times"></i></button>
                                                    </form>
                                                @endif
                                            @endrole
                                        @endif
                                    @endif
                                    
                                    @if(auth()->user()->hasRole('super_admin') && $request->isUnpaid())
                                        <form action="{{ route('medical-requests.destroy', $request->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to delete this medical request?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5 text-muted">
                                <i class="fas fa-notes-medical fa-2x mb-3 d-block opacity-25"></i>
                                No medical requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($requests->hasPages())
        <div class="card-footer bg-light py-2">
            <x-falcon.pagination>
                {{ $requests->appends(request()->query())->links() }}
            </x-falcon.pagination>
        </div>
    @endif
</div>
@endsection
