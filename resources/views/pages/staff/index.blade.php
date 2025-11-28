@extends('layout.cms-layout')
@section('page-title', 'Staff - ')
@section('cms-main-content')
<div class="container mt-4">
  <h4 class="mb-4">Staff Management</h4>
  <livewire:staff.staff-table />
</div>
@endsection
