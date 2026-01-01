@extends('layout.cms-layout')
@section('page-title', 'Super Admin Dashboard')
@section('cms-main-content')
    <div class="row mb-3">
        <div class="col">
            <div class="card bg-100 shadow-none border">
                <div class="row gx-0 flex-between-center">
                    <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2"
                            src="{{ asset('assets/img/illustrations/crm-bar-chart.png') }}" alt="" width="90" />
                        <div>
                            <h6 class="text-primary fs-10 mb-0">Welcome to </h6>
                            <h4 class="text-primary fw-bold mb-0">
                                Super Admin
                                <span class="text-danger fw-medium"> - </span><span class="text-info fw-medium"> Dashboard
                                    Welfare CMS</span>
                            </h4>
                        </div><img class="ms-n4 d-md-none d-lg-block"
                            src="{{ asset('assets/img/illustrations/crm-line-chart.png') }}" alt=""
                            width="150" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">Systems</h5>
                            <h6 class="text-700 mb-0">Configuration</h6>
                        </div>
                        <div class="fs-4 text-primary"><span class="fas fa-cog"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">Users</h5>
                            <h6 class="text-700 mb-0">Management</h6>
                        </div>
                        <div class="fs-4 text-info"><span class="fas fa-users"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">Logs</h5>
                            <h6 class="text-700 mb-0">Audit Trail</h6>
                        </div>
                        <div class="fs-4 text-warning"><span class="fas fa-history"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">Backups</h5>
                            <h6 class="text-700 mb-0">System Security</h6>
                        </div>
                        <div class="fs-4 text-success"><span class="fas fa-shield-alt"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">System Overview</h5>
                    <p class="card-text">Welcome to the Super Admin control panel. Use the sidebar to manage all aspects of
                        the Welfare CMS system including roles, permissions, users, and infrastructure settings.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
