@extends('layouts.app')
@section('page-title', 'My Issued Challans')
@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Issued Challans</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('challans.index') }}" method="GET" class="position-relative">
                        <input class="form-control form-control-sm shadow-none search" type="search" name="search" placeholder="Search Challans..." value="{{ request('search') }}" />
                        <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                    </form>
                    <a href="{{ route('challans.create') }}" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                        <span class="d-none d-sm-inline-block ms-1">Issue Challan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="white-space-nowrap ps-3"># ID</th>
                        <th>PSID</th>
                        <th>Vehicle</th>
                        <th>Violator</th>
                        <th>Violation</th>
                        <th>Fine</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($challans as $challan)
                    <tr>
                        <td class="text-muted fw-bold ps-3">#{{ $challan->id }}</td>
                        <td><span class="badge badge-soft-secondary text-dark fs--2">{{ $challan->psid }}</span></td>
                        <td>
                            <span class="badge badge-soft-primary text-dark fs--2 me-1">{{ $challan->vehicle_type }}</span><br>
                            <span class="text-dark fw-semi-bold">{{ $challan->vehicle_number }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $challan->violator_name }}</div>
                            <small class="text-muted">{{ $challan->violator_cnic }}</small>
                        </td>
                        <td><small class="text-truncate d-block" style="max-width: 150px;">{{ $challan->violation_name }}</small></td>
                        <td class="text-dark fw-bold">{{ number_format($challan->fine_amount) }} PKR</td>
                        <td>
                            @php
                                $statusColor = 'secondary';
                                if($challan->status == 'pending') $statusColor = 'warning';
                                elseif($challan->status == 'paid') $statusColor = 'success';
                                elseif($challan->status == 'released') $statusColor = 'success';
                            @endphp
                            <span class="badge badge-soft-{{ $statusColor }} text-{{ $statusColor }} fs--2">{{ ucfirst($challan->status) }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                @if($challan->status == 'pending')
                                    <button type="button" class="btn btn-link p-0 text-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $challan->id }}" title="Verify Payment">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @elseif($challan->status == 'paid')
                                    <a href="{{ route('impound.release.form', $challan->id) }}" class="btn btn-link p-0 text-primary" title="Release Vehicle">
                                        <i class="fas fa-car-side"></i>
                                    </a>
                                @endif

                                @if(auth()->user()->hasRole('super_admin'))
                                    <form action="{{ route('challans.destroy', $challan) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this challan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-danger ms-2" title="Delete Challan">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal{{ $challan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="{{ route('challans.validate-payment', $challan) }}" method="POST">
                                        @csrf
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title text-white"><span class="fas fa-check-circle me-2"></span>Verify Payment: #{{ $challan->psid }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Transaction Reference ID</label>
                                                    <input type="text" name="transaction_id" class="form-control" required placeholder="Enter bank or app transaction ID">
                                                    <div class="form-text mt-2 text-muted">Verify the transaction from your bank portal antes marking as paid.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-falcon-default btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success btn-sm">Confirm & Mark Paid</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-5 text-muted">
                            <i class="fas fa-receipt fa-2x mb-3 d-block opacity-25"></i>
                            No issued challans found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light py-2">
        <div class="d-flex justify-content-end">
            {{ $challans->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
