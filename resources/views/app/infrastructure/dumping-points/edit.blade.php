@extends('layouts.app')
@section('page-title', 'Edit Dumping Point')
@section('cms-main-content')
    <livewire:dumping-points.edit-dumping-point :id="$dumpingPoint->id" />
@endsection
