@extends('layouts.app')
@section('page-title', 'Feedback - ')

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Feedback & Support</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <a href="{{ route('feedback.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Submit Feedback</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">ID</th>
                        @role(['super_admin', 'admin'])
                            <th>Sender</th>
                        @endrole
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($feedbacks as $feedback)
                        <tr>
                            <td class="ps-3 text-muted fw-bold">#{{ $feedback->id }}</td>
                            @role(['super_admin', 'admin'])
                                <td>
                                    <div class="fw-bold text-dark">{{ $feedback->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $feedback->user->email ?? '' }}</small>
                                </td>
                            @endrole
                            <td>
                                <span class="badge badge-soft-info text-info fs--2">{{ ucwords(str_replace('_', ' ', $feedback->type)) }}</span>
                            </td>
                            <td><span class="text-dark">{{ Str::limit($feedback->subject, 50) }}</span></td>
                            <td>
                                @php
                                    $statusColor = 'secondary';
                                    if($feedback->status === 'resolved') $statusColor = 'success';
                                    elseif($feedback->status === 'reviewed') $statusColor = 'warning';
                                @endphp
                                <span class="badge badge-soft-{{ $statusColor }} text-{{ $statusColor }} fs--2">
                                    {{ ucfirst($feedback->status) }}
                                </span>
                            </td>
                            <td class="text-nowrap">{{ $feedback->created_at->format('M d, Y') }}</td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-link p-0 text-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @role(['super_admin', 'admin'])
                                        <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Delete Feedback">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole(['super_admin', 'admin'])? 7: 6 }}" class="text-center p-5 text-muted">
                                <i class="fas fa-comments fa-2x mb-3 d-block opacity-25"></i>
                                No feedback found. <a href="{{ route('feedback.create') }}" class="fw-semi-bold">Submit your first feedback</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($feedbacks->hasPages())
        <div class="card-footer bg-light py-2">
            <div class="d-flex justify-content-end">
                {{ $feedbacks->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
