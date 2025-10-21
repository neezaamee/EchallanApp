@extends('layouts.department')

@section('content')
  <h1>Super Admin Dashboard</h1>
  <p>Welcome, {{ auth()->user()->name }}</p>
  <p>Use this page to manage admins, permissions, and system settings.</p>
@endsection
