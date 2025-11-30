@extends('layout.cms-layout')
@section('page-title', 'Transfer/Post Staff - ')
@section('cms-main-content')
<div class="container mt-4">
    <h2 class="mb-4">Transfer / Post Staff</h2>
    @livewire('staff-postings.create-posting')
</div>
@endsection


