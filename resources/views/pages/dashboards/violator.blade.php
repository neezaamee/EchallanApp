@extends('layout.cms-layout')

@section('cms-main-content')
  <h1>Violator Dashboard</h1>
  <p>Welcome, {{ auth()->user()->name }}</p>
  <p>Your CNIC: {{ auth()->user()->cnic }}</p>
@endsection
