@extends('layouts.app')
@section('page-title', 'System Logs - ')

@section('cms-main-content')
<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">System Activity Logs</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <span class="badge badge-soft-info text-info"><i class="fas fa-shield-alt me-1"></i>Audit Trail</span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Time</th>
                        <th>User / Causer</th>
                        <th>Activity</th>
                        <th>Subject</th>
                        <th class="text-end pe-3">Changes / Details</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse($activities as $activity)
                        <tr>
                            <td class="ps-3 text-nowrap">{{ $activity->created_at->format('d M, Y H:i:s') }}</td>
                            <td>
                                @if ($activity->causer)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-l me-2">
                                            <div class="avatar-name rounded-circle"><span>{{ substr($activity->causer->name, 0, 1) }}</span></div>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 text-dark fw-semi-bold">{{ $activity->causer->name }}</h6>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge badge-soft-secondary text-dark fs--2">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-soft-primary text-primary fs--2">{{ ucfirst($activity->description) }}</span>
                            </td>
                            <td>
                                <small class="text-muted fw-semi-bold">{{ class_basename($activity->subject_type) }}</small>
                                <span class="badge badge-soft-secondary text-dark fs--2 ms-1">ID: {{ $activity->subject_id }}</span>
                            </td>
                            <td class="text-end pe-3">
                                @if ($activity->properties->has('old') || $activity->properties->has('attributes'))
                                    <button class="btn btn-link btn-sm p-0 text-primary text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#log-{{ $activity->id }}">
                                        View Changes <i class="fas fa-chevron-down ms-1 fs--2"></i>
                                    </button>
                                    <div class="collapse mt-2 text-start" id="log-{{ $activity->id }}">
                                        <pre class="bg-100 p-3 rounded fs--2 border text-dark"><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    </div>
                                @else
                                    <span class="text-400">---</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-5 text-muted">
                                <i class="fas fa-list-ul fa-2x mb-3 d-block opacity-25"></i>
                                No activity logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($activities->hasPages())
        <div class="card-footer bg-light py-2">
            <div class="d-flex justify-content-end">
                {{ $activities->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
