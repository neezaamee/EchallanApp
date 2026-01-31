@extends('layouts.app')
@section('page-title', 'Role Management - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Role Management</h2>
            </div>
        </div>
    </div>

    @livewire('roles.roles-table')
@endsection
