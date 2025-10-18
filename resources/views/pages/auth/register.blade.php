@extends('layout.layout')
@section('page-title', 'Register - ')
@section('main-content')
<div class="row min-vh-100 flex-center g-0">
          <div class="col-lg-8 col-xxl-5 py-3 position-relative"><img class="bg-auth-circle-shape" src="../../../assets/img/icons/spot-illustrations/bg-shape.png" alt="" width="250"><img class="bg-auth-circle-shape-2" src="../../../assets/img/icons/spot-illustrations/shape-1.png" alt="" width="150">
            <div class="card overflow-hidden z-1">
              <div class="card-body p-0">
                <div class="row g-0 h-100">
                  <div class="col-md-5 text-center bg-card-gradient">
                    <div class="position-relative p-4 pt-md-5 pb-md-7" data-bs-theme="light">
                      <div class="bg-holder bg-auth-card-shape" style="background-image:url(../../../assets/img/icons/spot-illustrations/half-circle.png);">
                      </div>
                      <!--/.bg-holder-->

                      @include('partials.components.auth-card-heading-sub-heading')
                    </div>
                    <div class="mt-3 mb-4 mt-md-4 mb-md-5" data-bs-theme="light">
                      <p class="pt-3 text-white">Have an account?<br><a class="btn btn-outline-light mt-2 px-4" href="../../../pages/authentication/card/login.html">Log In</a></p>
                    </div>
                  </div>
                  <div class="col-md-7 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                      <h3>Register</h3>
                      <livewire:forms.registration />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@endsection
