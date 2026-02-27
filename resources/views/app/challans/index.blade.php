@extends('layouts.app')
@section('page-title', 'My Issued Challans')
@section('cms-main-content')
<div class="row mb-3 justify-content-between align-items-center">
    <div class="col-auto">
        <a href="{{ route('challans.create') }}" class="btn btn-primary"><span class="fas fa-plus me-1"></span> Issue Challan</a>
    </div>
    <div class="col-auto">
        <form action="{{ route('challans.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search Vehicle, CNIC, PSID..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary">Search</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>PSID</th>
                        <th>Vehicle</th>
                        <th>Violator</th>
                        <th>Mobile</th>
                        <th>Violation</th>
                        <th>Fine</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($challans as $challan)
                    <tr>
                        <td><small class="fw-bold">#{{ $challan->id }}</small></td>
                        <td><span class="badge bg-light text-dark">{{ $challan->psid }}</span></td>
                        <td>
                            <span class="badge bg-secondary">{{ $challan->vehicle_type }}</span>
                            <br>
                            {{ $challan->vehicle_number }}
                        </td>
                        <td>
                            {{ $challan->violator_name }}
                            <br>
                            <small class="text-muted">{{ $challan->violator_cnic }}</small>
                        </td>
                        <td>{{ $challan->violator_mobile ?? 'N/A' }}</td>
                        <td>{{ $challan->violation_name }}</td>
                        <td>{{ number_format($challan->fine_amount) }} PKR</td>
                        <td>
                            @if($challan->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($challan->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($challan->status == 'released')
                                <span class="badge bg-info">Released</span>
                            @endif
                        </td>
                        <td>
                            @if($challan->status == 'pending')
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $challan->id }}">
                                    Verify Payment
                                </button>
                            @elseif($challan->status == 'paid')
                                <form action="{{ route('challans.release', $challan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Release Vehicle</button>
                                </form>
                            @endif

                            @if(auth()->user()->hasRole('super_admin'))
                                <form action="{{ route('challans.destroy', $challan) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this challan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Challan"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                            
                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal{{ $challan->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('challans.validate-payment', $challan) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Verify Payment for #{{ $challan->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Transaction ID</label>
                                                    <input type="text" name="transaction_id" class="form-control" required placeholder="Enter bank/app transaction ID">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Verify & Mark Paid</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">No challans found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $challans->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
