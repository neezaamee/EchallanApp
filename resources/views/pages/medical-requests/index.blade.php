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
                                <th>ID</th>
                                <th>Citizen</th>
                                <th>Medical Center</th>
                                <th>PSID</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                @role('doctor')
                                    <th>Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
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
                                    @role('doctor')
                                        <td>
                                            @if ($request->payment_status === 'paid' && $request->status === 'pending')
                                                <form action="{{ route('medical-requests.update', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" name="action" value="passed"
                                                        class="btn btn-sm btn-success me-1">Pass</button>
                                                </form>
                                                <form action="{{ route('medical-requests.update', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" name="action" value="failed"
                                                        class="btn btn-sm btn-danger">Fail</button>
                                                </form>
                                            @elseif($request->payment_status !== 'paid')
                                                <span class="text-muted small">Wait for Payment</span>
                                            @else
                                                <span class="text-muted small">Actioned</span>
                                            @endif
                                        </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasRole('doctor') ? 7 : 6 }}" class="text-center py-4">
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
