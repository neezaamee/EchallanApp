@extends('layouts.app')

@section('page-title', 'Staff Profile - ' . $staff->fullName())

@section('cms-main-content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header position-relative min-vh-25 mb-7">
                <div class="bg-holder rounded-3 rounded-bottom-0" style="background-image:url({{ asset('assets/img/generic/4.jpg') }});"></div>
                <!--/.bg-holder-->
                <div class="avatar avatar-5xl avatar-profile">
                    <img class="rounded-circle img-thumbnail shadow-sm" src="{{ $staff->photo ? asset($staff->photo) : asset('assets/img/team/avatar.png') }}" width="200" alt="" />
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-1 fw-bold text-dark">{{ $staff->fullName() }}
                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="Verified">
                                <small class="fa fa-check-circle text-primary ms-1 fs--1"></small>
                            </span>
                        </h4>
                        <h5 class="fs-0 fw-normal text-700">{{ $staff->rank->name ?? 'No Rank' }} — {{ $staff->getRoleNames()->first() ?? 'No Role' }}</h5>
                        <p class="text-500 fs--1">{{ $staff->city->name ?? 'No City' }}, {{ $staff->province->name ?? 'No Province' }}</p>
                        <hr class="my-4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <x-falcon.card title="Posting History" description="Historical log of active duty assignments.">
            <x-falcon.table>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Location</th>
                        <th>Type</th>
                        <th>Assigned Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($postings as $p)
                        <tr>
                            <td class="ps-3 fw-bold text-dark">
                                @if($p->medical_center_id)
                                    {{ $p->medicalCenter->name }}
                                @elseif($p->dumping_point_id)
                                    {{ $p->dumpingPoint->name }}
                                @elseif($p->sector_id)
                                    {{ $p->sector->name }}
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
                                    <x-falcon.badge variant="primary">Medical Center</x-falcon.badge>
                                @elseif($p->dumping_point_id)
                                    <x-falcon.badge variant="warning">Dumping Point</x-falcon.badge>
                                @elseif($p->sector_id)
                                    <x-falcon.badge variant="success">Sector</x-falcon.badge>
                                @else
                                    <x-falcon.badge variant="info">Administrative</x-falcon.badge>
                                @endif
                            </td>
                            <td class="text-muted fs--2">{{ $p->created_at->format('d M, Y') }}</td>
                            <td>
                                <x-falcon.badge :variant="$p->status === 'active' ? 'success' : 'secondary'">
                                    {{ ucfirst($p->status) }}
                                </x-falcon.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted p-4">No posting history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-falcon.table>
        </x-falcon.card>
    </div>
    
    <div class="col-lg-4">
        <x-falcon.card title="Staff Details" bodyClass="p-3">
            <x-slot name="headerActions">
                @can('staff:edit')
                <x-falcon.button href="{{ route('staff.edit', $staff->id) }}" variant="falcon-default" size="xs" icon="fas fa-edit">
                    Edit
                </x-falcon.button>
                @endcan
            </x-slot>

            <div class="mb-3">
                <label class="text-500 fs--2 fw-bold text-uppercase">Belt Number</label>
                <p class="mb-0 fw-semi-bold text-dark fs--1">{{ $staff->belt_no ?? '—' }}</p>
            </div>
            <div class="mb-3">
                <label class="text-500 fs--2 fw-bold text-uppercase">CNIC</label>
                <p class="mb-0 fw-semi-bold text-dark fs--1">{{ $staff->cnic }}</p>
            </div>
            <div class="mb-3">
                <label class="text-500 fs--2 fw-bold text-uppercase">E-mail</label>
                <p class="mb-0 fs--1"><a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a></p>
            </div>
            <div class="mb-3">
                <label class="text-500 fs--2 fw-bold text-uppercase">Phone</label>
                <p class="mb-0 fw-semi-bold text-dark fs--1">{{ $staff->phone }}</p>
            </div>
            <div class="mb-0">
                <label class="text-500 fs--2 fw-bold text-uppercase">Gender</label>
                <p class="mb-0 fw-semi-bold text-dark fs--1">{{ ucfirst($staff->gender) }}</p>
            </div>
        </x-falcon.card>

        @if(!$staff->activePosting)
            <div class="card bg-soft-primary mb-3">
                <div class="card-body">
                    <h5 class="text-primary fw-bold"><span class="fas fa-info-circle me-2"></span>Not Posted</h5>
                    <p class="fs--1 text-primary mb-3">This staff member is currently not assigned to any active station.</p>
                    @can('staff-postings:create')
                    <x-falcon.button href="{{ route('staff-postings.create', ['staff_id' => $staff->id]) }}" variant="primary" class="w-100">
                        Assign Posting Now
                    </x-falcon.button>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
