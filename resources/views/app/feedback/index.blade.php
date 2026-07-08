@extends('layouts.app')
@section('page-title', 'Feedback - ')

@php
    $currentSort = $sortField ?? 'created_at';
    $currentDir = $sortDirection ?? 'desc';

    if (!function_exists('sortFeedbackUrl')) {
        function sortFeedbackUrl($column, $currentSort, $currentDir) {
            $direction = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery([
                'sort' => $column,
                'direction' => $direction,
                'page' => 1
            ]);
        }
    }

    if (!function_exists('sortFeedbackIcon')) {
        function sortFeedbackIcon($column, $currentSort, $currentDir) {
            if ($currentSort !== $column) {
                return '<span class="fas fa-sort ms-1 text-400 fs--2"></span>';
            }
            return $currentDir === 'asc' 
                ? '<span class="fas fa-sort-up ms-1 text-primary fs--2"></span>' 
                : '<span class="fas fa-sort-down ms-1 text-primary fs--2"></span>';
        }
    }
@endphp

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Feedback & Support</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div class="d-flex align-items-center gap-2 justify-content-end">
                    <form action="{{ route('feedback.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                        <select name="per_page" class="form-select form-select-sm shadow-none w-auto" onchange="this.form.submit()">
                            <option value="20" {{ request('per_page', 50) == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </form>
                    <a href="{{ route('feedback.create') }}" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                        <span class="d-none d-sm-inline-block ms-1">Submit Feedback</span>
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
                        <th class="ps-3"><a href="{{ sortFeedbackUrl('id', $currentSort, $currentDir) }}" class="text-900">S.No {!! sortFeedbackIcon('id', $currentSort, $currentDir) !!}</a></th>
                        @role(['super_admin', 'admin'])
                            <th>Sender</th>
                        @endrole
                        <th><a href="{{ sortFeedbackUrl('type', $currentSort, $currentDir) }}" class="text-900">Type {!! sortFeedbackIcon('type', $currentSort, $currentDir) !!}</a></th>
                        <th><a href="{{ sortFeedbackUrl('subject', $currentSort, $currentDir) }}" class="text-900">Subject {!! sortFeedbackIcon('subject', $currentSort, $currentDir) !!}</a></th>
                        <th><a href="{{ sortFeedbackUrl('status', $currentSort, $currentDir) }}" class="text-900">Status {!! sortFeedbackIcon('status', $currentSort, $currentDir) !!}</a></th>
                        <th><a href="{{ sortFeedbackUrl('created_at', $currentSort, $currentDir) }}" class="text-900">Date {!! sortFeedbackIcon('created_at', $currentSort, $currentDir) !!}</a></th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($feedbacks as $feedback)
                        <tr>
                            <td class="ps-3 text-muted fw-bold">{{ ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $loop->iteration }}</td>
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
            <x-falcon.pagination>
                {{ $feedbacks->appends(request()->query())->links() }}
            </x-falcon.pagination>
        </div>
    @endif
</div>
@endsection
