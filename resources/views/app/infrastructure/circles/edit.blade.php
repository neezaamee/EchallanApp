@extends('layouts.app')
@section('page-title', 'Edit Circle')
@section('add-css')
<link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-5-theme/select2-bootstrap-5-theme.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('cms-main-content')
    <livewire:circles.edit-circle :id="$id" />
@endsection

@section('add-js-bottom')
<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
