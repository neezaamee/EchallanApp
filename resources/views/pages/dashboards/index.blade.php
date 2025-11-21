@extends('layout.cms-layout')
@section('page-title', 'Dashboard Admin - ')
@section('cms-main-content')
    <div class="row mb-3">
        <div class="col">
            <div class="card bg-100 shadow-none border">
                <div class="row gx-0 flex-between-center">
                    <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2"
                            src="../assets/img/illustrations/crm-bar-chart.png" alt="" width="90" />
                        <div>
                            <h6 class="text-primary fs-10 mb-0">Welcome to </h6>
                            <h4 class="text-primary fw-bold mb-0">
                                {{ ucwords(str_replace('_', ' ', auth()->user()->getRoleNames()->first())) }} Dashboard
                                <span class="text-danger fw-medium"> - </span><span class="text-info fw-medium">Welfare CMS</span></h4>
                        </div><img class="ms-n4 d-md-none d-lg-block" src="{{ asset('assets/img/illustrations/crm-line-chart.png') }}"
                            alt="" width="150" />
                    </div>
                    <div class="col-md-auto p-3">
                        <form class="row align-items-center g-3">
                            <div class="col-auto">
                                <h6 class="text-700 mb-0">Showing Data For: </h6>
                            </div>
                            <div class="col-md-auto position-relative">
                                <input class="form-control form-control-sm datetimepicker ps-4" id="CRMDateRange"
                                    type="text"
                                    data-options="{&quot;mode&quot;:&quot;range&quot;,&quot;dateFormat&quot;:&quot;M d&quot;,&quot;disableMobile&quot;:true , &quot;defaultDate&quot;: [&quot;Jul 10&quot;, &quot;Jul 17&quot;] }" /><span
                                    class="fas fa-calendar-alt text-primary position-absolute top-50 translate-middle-y ms-2">
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @auth

    <p>Welcome, {{ auth()->user()->name }}</p>
    <p>Your CNIC: {{ auth()->user()->cnic }}</p>
    <p>Your Email: {{ auth()->user()->email }}</p>
    {{-- <p>Your Gender: {{ auth()->user()->citizen->gender }}</p>
    <p>Your Email: {{ auth()->user()->citizen->email }}</p>
    <p>Your Phone: {{ auth()->user()->citizen->phone }}</p> --}}

    @endauth

@endsection
