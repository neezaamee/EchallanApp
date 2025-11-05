@extends('layout.cms-layout')
@section('page-title', 'Edit Dumping Point - ')
@section('cms-main-content')
    <a href=""></a>
    <div class="container mt-4">
        <h4 class="mb-4">Edit Dumping Point</h4>
        {{-- We pass the $dumpingPoint from the controller to the component --}}
        <livewire:dumping-points.edit-dumping-point :id="$dumpingPoint->id" />
    </div>
@endsection
