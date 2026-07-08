@extends('layouts.app')
@section('page-title', 'Edit Province')
@section('cms-main-content')
    @livewire('provinces.edit-province', ['id' => $id])
@endsection
