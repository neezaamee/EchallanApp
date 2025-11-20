@extends('layouts.department')

@section('title','Staff')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Staff</h3>
  <a href="{{ route('staff.create') }}" class="btn btn-primary">Create Staff</a>
</div>

<form method="GET" class="mb-3">
  <div class="row g-2">
    <div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name / CNIC / email"></div>
    <div class="col-md-2"><button class="btn btn-outline-secondary">Search</button></div>
  </div>
</form>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>CNIC</th>
      <th>Designation</th>
      <th>Rank</th>
      <th>City</th>
      <th>Province</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @forelse($staff as $s)
      <tr>
        <td>{{ $s->id }}</td>
        <td>{{ $s->fullName() }}</td>
        <td>{{ $s->cnic }}</td>
        <td>{{ $s->designation?->name ?? '-' }}</td>
        <td>{{ $s->rank?->name ?? '-' }}</td>
        <td>{{ $s->city?->name ?? '-' }}</td>
        <td>{{ $s->province?->name ?? '-' }}</td>
        <td>
          <a href="{{ route('staff.edit', $s) }}" class="btn btn-sm btn-info">Edit</a>

          <form action="{{ route('staff.destroy', $s) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete staff?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="8" class="text-center">No staff found.</td></tr>
    @endforelse
  </tbody>
</table>

{{ $staff->links() }}
@endsection
