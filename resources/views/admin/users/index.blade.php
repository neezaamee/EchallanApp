@extends('layouts.app')
@section('page-title', 'Users Management - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>
        </div>
    </div>

    @livewire('users.users-table')
@endsection
