@extends('layout.cms-layout')
@section('page-title', 'Edit Circle - ')
@section('cms-main-content')
<div class="container mt-4">
  <h4 class="mb-4">Edit Circle</h4>
  <livewire:circles.edit-circle :id="$id" />
</div>
@endsection
