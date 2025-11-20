@extends('layout.cms-layout')
@section('page-title', 'Medical Centers - ')
@section('cms-main-content')
<div class="container mt-4">
  <h4 class="mb-4">Medical Centers</h4>
  <livewire:medical-centers.medical-centers-table />
</div>
@endsection
