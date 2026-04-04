@extends('layouts.app')
@section('page-title', 'Add Pick Up Point')
@section('cms-main-content')
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h5 class="mb-0">Add New Pick Up Point</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pick-up-points.store') }}" method="POST">
                @csrf
                <div class="row gx-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="dumping_point_id">Dumping Point</label>
                        <select name="dumping_point_id" id="dumping_point_id" class="form-select @error('dumping_point_id') is-invalid @enderror" required>
                            <option value="">Select Dumping Point</option>
                            @foreach($dumpingPoints as $dp)
                                <option value="{{ $dp->id }}">{{ $dp->name }}</option>
                            @endforeach
                        </select>
                        @error('dumping_point_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name">Point Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required placeholder="e.g. Liberty Market Point">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="location">Location Address / Coordinates (Optional)</label>
                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" placeholder="e.g. Near Main Gate, Model Town">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                        <label class="form-check-label fw-bold" for="isActive">Is Active?</label>
                    </div>
                </div>

                <div class="border-top mt-3 pt-3 text-end">
                    <a href="{{ route('pick-up-points.index') }}" class="btn btn-falcon-default btn-sm me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">Save Pick Up Point</button>
                </div>
            </form>
        </div>
    </div>
@endsection
