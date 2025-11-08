@extends('layout.cms-layout')
@section('page-title', 'Medical Centers - ')
@section('cms-main-content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-primary">🏥 Location Management</h1>

    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.provinces') }}" class="card shadow-sm text-center p-4 hover:bg-light">
                <h5 class="fw-bold">Provinces</h5>
                <p class="text-muted small">Manage all provinces</p>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.cities') }}" class="card shadow-sm text-center p-4 hover:bg-light">
                <h5 class="fw-bold">Cities</h5>
                <p class="text-muted small">Manage cities by province</p>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.circles') }}" class="card shadow-sm text-center p-4 hover:bg-light">
                <h5 class="fw-bold">Circles</h5>
                <p class="text-muted small">Assign circles to cities</p>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.medical-centers') }}" class="card shadow-sm text-center p-4 hover:bg-light">
                <h5 class="fw-bold">Medical Centers</h5>
                <p class="text-muted small">Manage centers by circle</p>
            </a>
        </div>
    </div>
</div>
@endsection
