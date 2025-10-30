@extends('layout.cms-layout')
@section('page-title', 'Provinces -')
@section('cms-main-content')
<div class="container py-4">
    <h4 class="mb-3">Edit Province</h4>
    @livewire('edit-province', ['id' => $id])
</div>
@endsection
