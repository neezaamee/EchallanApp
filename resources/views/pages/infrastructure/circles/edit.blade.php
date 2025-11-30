@extends('layout.cms-layout')
@section('page-title', 'Edit Circle - ')
@section('add-css')
<link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-5-theme/select2-bootstrap-5-theme.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('cms-main-content')
<div class="container mt-4">
  <h4 class="mb-4">Edit Circle</h4>
  <livewire:circles.edit-circle :id="$id" />
</div>
@endsection
@section('add-js-bottom')
<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
