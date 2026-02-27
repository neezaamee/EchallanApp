@extends('layouts.app')
@section('page-title', 'Permission Management - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Permission Management</h2>
            </div>
        </div>
    </div>

    @livewire('permissions.permissions-table')
@endsection
