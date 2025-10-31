@extends('layout.cms-layout')
@section('page-title', 'Edit City - ')
@section('cms-main-content')
<div class="container mt-4">
    <h4 class="mb-3">Edit City</h4>
    @livewire('edit-city', ['id' => $id])
</div>
@endsection
