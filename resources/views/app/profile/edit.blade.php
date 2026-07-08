@extends('layouts.app')
@section('page-title', 'Profile Settings - ')
@section('cms-main-content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-dark fw-bold">Profile Information</h5>
            </div>
            <div class="card-body p-4">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-dark fw-bold">Update Password</h5>
            </div>
            <div class="card-body p-4">
                <livewire:profile.update-password-form />
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3 border border-300">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0 text-danger fw-bold">Danger Zone</h5>
            </div>
            <div class="card-body p-4">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</div>
@endsection
