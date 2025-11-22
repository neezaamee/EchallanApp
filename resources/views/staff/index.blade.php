@extends('layouts.department')
@section('content')
<div class="container">
<div class="container">
    <h2 class="mb-4">Staff List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('staff.create') }}" class="btn btn-primary mb-3">Add Staff</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Rank</th>
                <th>City</th>
                <th>Circle</th>
                <th>Dumping Point</th>
                <th>Medical Center</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->first_name }} {{ $s->last_name }}</td>
                    <td>{{ $s->email }}</td>
                    <td>{{ $s->phone }}</td>
                    <td>{{ optional($s->user->getRoleNames()->first())->toString() ?? '-' }}</td>
                    <td>{{ $s->rank?->name ?? '-' }}</td>{{-- operator ran?-> null values ko handle krta hai --}}
                    <td>{{ optional($s->activePosting->city)->name ?? '-' }}</td>
                    <td>{{ optional($s->activePosting->circle)->name ?? '-' }}</td>
                    <td>{{ optional($s->activePosting->dumpingPoint)->name ?? '-' }}</td>
                    <td>{{ optional($s->activePosting->medicalCenter)->name ?? '-' }}</td>
                    <td>{{ ucfirst($s->status) }}</td>
                    <td>
                        <a href="{{ route('staff.edit', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('staff.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
