@extends('layouts.app')

@section('page-title', 'Show Role - ')

@section('cms-main-content')
<div class="container-fluid mt-2">
    <x-falcon.card title="Show Role: {{ ucwords(str_replace('_', ' ', $role->name)) }}" bodyClass="p-4">
        <x-slot name="headerActions">
            <x-falcon.button href="{{ route('roles.index') }}" variant="secondary" icon="fas fa-arrow-left">
                Back to List
            </x-falcon.button>
        </x-slot>

        <div class="mb-4">
            <h6 class="text-500 fs--2 fw-bold text-uppercase">Role System Name</h6>
            <p class="mb-0 fw-semi-bold text-dark fs-0">{{ $role->name }}</p>
        </div>

        <div>
            <h6 class="text-500 fs--2 fw-bold text-uppercase mb-2">Assigned Permissions</h6>
            <div class="d-flex flex-wrap gap-2">
                @forelse ($rolePermissions as $permission)
                    <x-falcon.badge variant="success">{{ $permission->name }}</x-falcon.badge>
                @empty
                    <span class="text-muted fs--1">No permissions assigned to this role.</span>
                @endforelse
            </div>
        </div>
    </x-falcon.card>
</div>
@endsection
