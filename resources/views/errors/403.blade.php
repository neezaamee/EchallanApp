@extends('layout.cms-layout')
@section('page-title', 'Dashboard Super Admin - ')
@section('cms-main-content')

    @auth
    <h1>You are not allowed to use this page</h1>
    @endauth

    <p>Use this page to manage admins, permissions, and system settings.</p>
@endsection
