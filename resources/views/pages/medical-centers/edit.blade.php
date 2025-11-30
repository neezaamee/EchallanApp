@extends('layout.cms-layout')
@section('page-title', 'Edit Medical Center - ')
@section('cms-main-content')
    <div class="container mt-4">
        <h4 class="mb-4">Edit Medical Center</h4>
        <livewire:medical-centers.edit-medical-center :id="$medicalCenter->id" />
    </div>
@endsection
