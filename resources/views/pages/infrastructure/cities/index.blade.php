@extends('layout.cms-layout')
@section('page-title', 'Cities - ')
@section('cms-main-content')
<div class="container mt-4">
  <h4 class="mb-4">Cities</h4>
  <livewire:cities-table />
</div>
@endsection
