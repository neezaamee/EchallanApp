@extends('layouts.app')

@section('page-title', 'Staff Profile - ' . $staff->fullName())

@section('cms-main-content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header position-relative min-vh-25 mb-7">
                <div class="bg-holder rounded-3 rounded-bottom-0" style="background-image:url({{ asset('assets/img/generic/4.jpg') }});"></div>
                <!--/.bg-holder-->
                <div class="avatar avatar-5xl avatar-profile"><img class="rounded-circle img-thumbnail shadow-sm" src="{{ $staff->photo ? asset($staff->photo) : asset('assets/img/team/avatar.png') }}" width="200" alt="" /></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-1">{{ $staff->fullName() }}<span data-bs-toggle="tooltip" data-bs-placement="right" title="Verified"><small class="fa fa-check-circle text-primary ms-1 fs--1"></small></span></h4>
                        <h5 class="fs-0 fw-normal">{{ $staff->rank->name ?? 'No Rank' }} - {{ $staff->getRoleNames()->first() ?? 'No Role' }}</h5>
                        <p class="text-500">{{ $staff->city->name ?? 'No City' }}, {{ $staff->province->name ?? 'No Province' }}</p>
                        <hr class="my-4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Posting History</h5>
            </div>
            <div class="card-body fs--1">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Assigned Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postings as $p)
                                <tr>
                                    <td>
                                        @if($p->medical_center_id)
                                            {{ $p->medicalCenter->name }}
                                        @elseif($p->dumping_point_id)
                                            {{ $p->dumpingPoint->name }}
                                        @elseif($p->circle_id)
                                            {{ $p->circle->name }}
                                        @elseif($p->city_id)
                                            {{ $p->city->name }}
                                        @elseif($p->province_id)
                                            {{ $p->province->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->medical_center_id)
                                            <span class="badge badge-soft-primary">Medical Center</span>
                                        @elseif($p->dumping_point_id)
                                            <span class="badge badge-soft-warning">Dumping Point</span>
                                        @else
                                            <span class="badge badge-soft-info">Administrative</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->created_at->format('d M, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $p->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No posting history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Staff Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-500 fs--2 fw-bold text-uppercase">Belt Number</label>
                    <p class="mb-0 fw-semi-bold">{{ $staff->belt_no ?? '—' }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-500 fs--2 fw-bold text-uppercase">CNIC</label>
                    <p class="mb-0 fw-semi-bold">{{ $staff->cnic }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-500 fs--2 fw-bold text-uppercase">E-mail</label>
                    <p class="mb-0"><a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a></p>
                </div>
                <div class="mb-3">
                    <label class="text-500 fs--2 fw-bold text-uppercase">Phone</label>
                    <p class="mb-0 fw-semi-bold">{{ $staff->phone }}</p>
                </div>
                <div class="mb-0">
                    <label class="text-500 fs--2 fw-bold text-uppercase">Gender</label>
                    <p class="mb-0 fw-semi-bold">{{ ucfirst($staff->gender) }}</p>
                </div>
            </div>
            @can('staff:edit')
            <div class="card-footer bg-light text-end">
                <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-edit me-1"></span>Edit Profile
                </a>
            </div>
            @endcan
        </div>

        @if(!$staff->activePosting)
            <div class="card bg-soft-primary mb-3">
                <div class="card-body">
                    <h5 class="text-primary"><span class="fas fa-info-circle me-2"></span>Not Posted</h5>
                    <p class="fs--1 text-primary">This staff member is currently not assigned to any station.</p>
                    @can('staff-postings:create')
                    <a href="{{ route('staff-postings.create', ['staff_id' => $staff->id]) }}" class="btn btn-primary btn-sm w-100">
                        Assign Posting Now
                    </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
