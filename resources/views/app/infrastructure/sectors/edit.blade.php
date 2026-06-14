@extends('layouts.app')
@section('page-title', 'Edit Sector')
@section('cms-main-content')
  @livewire('sectors.edit-sector', ['id' => $id])
@endsection
