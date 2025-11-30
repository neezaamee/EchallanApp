@extends('layout.cms-layout')
@section('page-title', 'Staff Postings - ')
@section('cms-main-content')
<div class="container mt-4">
    <h2 class="mb-4">Staff Postings</h2>
    @livewire('staff-postings.staff-postings-table')
</div>
@endsection