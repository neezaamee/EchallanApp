@extends('layout.cms-layout')
@section('page-title', 'System Logs - ')
@section('cms-main-content')
    <div class="row mb-3">
        <div class="col">
            <div class="card bg-100 shadow-none border">
                <div class="row gx-0 flex-between-center">
                    <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2"
                            src="{{ asset('assets/img/illustrations/crm-bar-chart.png') }}" alt="" width="90" />
                        <div>
                            <h6 class="text-primary fs-10 mb-0">System Administration</h6>
                            <h4 class="text-primary fw-bold mb-0">
                                Activity Logs
                                <span class="text-danger fw-medium"> - </span><span class="text-info fw-medium"> Audit
                                    Trail</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-none">
        <div class="card-body p-0 pb-3">
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped fs-10 mb-0">
                    <thead class="bg-200">
                        <tr>
                            <th class="text-900 border-0">Time</th>
                            <th class="text-900 border-0">User</th>
                            <th class="text-900 border-0">Activity</th>
                            <th class="text-900 border-0">Subject</th>
                            <th class="text-900 border-0">Changes</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse($activities as $activity)
                            <tr>
                                <td class="align-middle white-space-nowrap py-2">
                                    {{ $activity->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="align-middle py-2">
                                    @if ($activity->causer)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-l me-2">
                                                <div class="avatar-name rounded-circle">
                                                    <span>{{ substr($activity->causer->name, 0, 1) }}</span></div>
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="mb-0 text-1000">{{ $activity->causer->name }}</h6>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-400">System</span>
                                    @endif
                                </td>
                                <td class="align-middle py-2">
                                    <span class="badge badge-subtle-primary">{{ ucfirst($activity->description) }}</span>
                                </td>
                                <td class="align-middle py-2">
                                    <small class="text-600">
                                        {{ class_basename($activity->subject_type) }} (ID: {{ $activity->subject_id }})
                                    </small>
                                </td>
                                <td class="align-middle py-2">
                                    @if ($activity->properties->has('old') || $activity->properties->has('attributes'))
                                        <button class="btn btn-link btn-sm p-0" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#log-{{ $activity->id }}">
                                            View Details
                                        </button>
                                        <div class="collapse mt-2" id="log-{{ $activity->id }}">
                                            <pre class="bg-100 p-2 rounded fs-11"><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                        </div>
                                    @else
                                        <span class="text-400">---</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No activity logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection
