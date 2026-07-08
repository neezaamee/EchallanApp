@extends('layouts.app')
@section('page-title', 'My Issued Challans')
@section('cms-main-content')
@php
    $currentSort = request('sort', 'created_at');
    $currentDir = request('direction', 'desc');
    $allowedSorts = ['id', 'psid', 'vehicle_number', 'violator_name', 'violation_name', 'fine_amount', 'status', 'created_at'];
    
    if (!in_array($currentSort, $allowedSorts)) {
        $currentSort = 'created_at';
    }
    
    if (!function_exists('sortUrl')) {
        function sortUrl($column, $currentSort, $currentDir) {
            $direction = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery([
                'sort' => $column,
                'direction' => $direction,
                'page' => 1
            ]);
        }
    }

    if (!function_exists('sortIcon')) {
        function sortIcon($column, $currentSort, $currentDir) {
            if ($currentSort !== $column) {
                return '<span class="fas fa-sort ms-1 text-400 fs--2"></span>';
            }
            return $currentDir === 'asc' 
                ? '<span class="fas fa-sort-up ms-1 text-primary fs--2"></span>' 
                : '<span class="fas fa-sort-down ms-1 text-primary fs--2"></span>';
        }
    }
@endphp
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Issued Challans</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('challans.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                        <div class="position-relative">
                            <input class="form-control form-control-sm shadow-none search" type="search" name="search" placeholder="Search Challans..." value="{{ request('search') }}" style="padding-right: 2rem;" />
                            <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                        </div>
                        <select name="per_page" class="form-select form-select-sm shadow-none w-auto" onchange="this.form.submit()">
                            <option value="20" {{ request('per_page', 50) == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
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
                        <th class="white-space-nowrap ps-3" style="width: 1%;">S.No</th>
                        <th class="white-space-nowrap" style="width: 1%;">
                            <a href="{!! sortUrl('psid', $currentSort, $currentDir) !!}" class="text-900 d-inline-flex align-items-center">
                                PSID {!! sortIcon('psid', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th class="white-space-nowrap" style="width: 1%;">
                            <a href="{!! sortUrl('vehicle_number', $currentSort, $currentDir) !!}" class="text-900 d-inline-flex align-items-center">
                                Vehicle {!! sortIcon('vehicle_number', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th class="white-space-nowrap">
                            <a href="{!! sortUrl('violator_name', $currentSort, $currentDir) !!}" class="text-900 d-inline-flex align-items-center">
                                Violator {!! sortIcon('violator_name', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th class="white-space-nowrap">
                            <a href="{!! sortUrl('violation_name', $currentSort, $currentDir) !!}" class="text-900 d-inline-flex align-items-center">
                                Violation {!! sortIcon('violation_name', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th class="white-space-nowrap" style="width: 1%;">
                            <a href="{!! sortUrl('fine_amount', $currentSort, $currentDir) !!}" class="text-900 d-inline-flex align-items-center">
                                Fine {!! sortIcon('fine_amount', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th class="white-space-nowrap" style="width: 1%;">
                            <a href="{!! sortUrl('status', $currentSort, $currentDir) !!}" class="text-900 d-inline-flex align-items-center">
                                Status {!! sortIcon('status', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th class="text-end pe-3 white-space-nowrap" style="width: 1%;">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($challans as $challan)
                    <tr>
                        <td class="text-muted fw-bold ps-3 white-space-nowrap" style="width: 1%;">{{ $loop->iteration + ($challans->currentPage() - 1) * $challans->perPage() }}</td>
                        <td class="white-space-nowrap" style="width: 1%;"><span class="badge badge-soft-secondary text-dark fs--2">{{ $challan->psid }}</span></td>
                        <td class="white-space-nowrap" style="width: 1%;">
                            <span class="badge badge-soft-primary text-dark fs--2 me-1">{{ $challan->vehicle_type }}</span><br>
                            <span class="text-dark fw-semi-bold text-uppercase">{{ strtoupper($challan->vehicle_number) }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $challan->violator_name }}</div>
                            <small class="text-muted">{{ $challan->violator_cnic }}</small>
                        </td>
                        <td><small class="text-truncate d-block" style="max-width: 250px;">{{ $challan->violation_name }}</small></td>
                        <td class="text-dark fw-bold white-space-nowrap" style="width: 1%;">{{ number_format($challan->fine_amount) }} PKR</td>
                        <td class="white-space-nowrap" style="width: 1%;">
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

                                @if(auth()->user()->hasRole('super_admin') && $challan->isUnpaid())
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
        <x-falcon.pagination>
            {{ $challans->withQueryString()->links() }}
        </x-falcon.pagination>
    </div>
</div>
@endsection
