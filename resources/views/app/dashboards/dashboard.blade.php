@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('cms-main-content')
    {{-- Smart Welcome Banner --}}
    <div class="card bg-100 shadow-none border mb-3">
        <div class="card-body py-3">
            <div class="row flex-between-center g-0">
                <div class="col-sm-auto d-flex align-items-center">
                    <img class="ms-n2" src="{{ asset('assets/img/illustrations/crm-bar-chart.png') }}" width="90" />
                    <div>
                        <h6 class="text-primary fs-10 mb-0">Welcome back,</h6>
                        <h4 class="text-primary fw-bold mb-1">
                            {{ auth()->user()->name }}
                            @php
                                $formattedRole = $role ? ucwords(str_replace(['_', '-'], ' ', $role)) : 'User';
                                $postingPlace = '';
                                $user = auth()->user();
                                if ($user->staff && $user->staff->activePosting) {
                                    $posting = $user->staff->activePosting;
                                    if ($posting->medical_center_id && $posting->medicalCenter) {
                                        $postingPlace = $posting->medicalCenter->name;
                                    } elseif ($posting->dumping_point_id && $posting->dumpingPoint) {
                                        $postingPlace = $posting->dumpingPoint->name;
                                    } elseif ($posting->sector_id && $posting->sector) {
                                        $postingPlace = $posting->sector->name;
                                    } elseif ($posting->circle_id && $posting->circle) {
                                        $postingPlace = $posting->circle->name;
                                    } elseif ($posting->city_id && $posting->city) {
                                        $postingPlace = $posting->city->name;
                                    } elseif ($posting->province_id && $posting->province) {
                                        $postingPlace = $posting->province->name;
                                    }
                                }

                                if (empty($postingPlace)) {
                                    if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                                        $postingPlace = 'Headquarters';
                                    } elseif ($user->hasRole('citizen')) {
                                        $postingPlace = 'Citizen Portal';
                                    }
                                }
                            @endphp
                            <span class="text-secondary fw-medium fs-9">
                                | {{ $formattedRole }}
                                @if($postingPlace)
                                    | {{ $postingPlace }}
                                @else
                                    | Access
                                @endif
                            </span>
                        </h4>
                        <p class="fs-10 mb-0">
                            Today is {{ now()->format('l, F j, Y') }}.
                            You are currently logged in to the <span class="fw-bold">Welfare CMS</span>.
                        </p>
                    </div>
                </div>
                <div class="col-sm-auto mt-3 mt-sm-0">
                    <a class="btn btn-outline-primary btn-sm shadow-sm" href="{{ route('profile') }}">
                        <span class="fas fa-user-circle me-1"></span> View Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Role-specific Content --}}
    @php
        $partialPath = "app.dashboards.partials._" . str_replace('-', '_', $role);
    @endphp

    @if(view()->exists($partialPath))
        @include($partialPath)
    @else
        @include('app.dashboards.partials._default')
    @endif
@endsection
