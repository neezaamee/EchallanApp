@extends('layout.cms-layout')
@section('page-title', 'Dumping Points - ')
@section('cms-main-content')
<div class="container mt-4">
  <h4 class="mb-4">Dumping Points</h4>
  <livewire:dumping-points.dumping-points-table />
</div>
@endsection
