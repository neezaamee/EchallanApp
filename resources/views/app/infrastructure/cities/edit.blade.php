@extends('layouts.app')
@section('page-title', 'Edit City')
@section('cms-main-content')
    @livewire('cities.edit-city', ['id' => $id])
@endsection
