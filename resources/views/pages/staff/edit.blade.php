@extends('layout.cms-layout')
@section('page-title', 'Edit Staff - ')
@section('cms-main-content')
    <div class="container mt-4">
        <h4 class="mb-4">Edit Staff</h4>
        <livewire:staff.edit-staff :id="$staff->id" />
    </div>
@endsection
