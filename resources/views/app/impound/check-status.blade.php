@extends('layouts.app')

@section('cms-main-content')
<div class="row min-vh-75 flex-center g-0">
    <div class="col-lg-6 col-xxl-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <div class="row flex-between-center">
                    <div class="col-auto">
                        <h5 class="mb-0">Verify PSID Status</h5>
                    </div>
                    <div class="col-auto">
                        <span class="fas fa-search text-primary"></span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('impound.check-status.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="psid">Enter 18-Digit PSID</label>
                        <input class="form-control @error('psid') is-invalid @enderror" id="psid" name="psid" type="text" placeholder="e.g. 100000000012345678" value="{{ old('psid') }}" required autofocus />
                        @error('psid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">
                            Check Status
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-link text-500">Back to Dashboard</a>
                    </div>
                </form>

                @if(session('error'))
                    <div class="alert alert-danger mt-3 mb-0" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="card-footer bg-light text-center py-2">
                <p class="fs--1 mb-0 text-600">Enter the PSID printed on the challan or provided by the violator.</p>
            </div>
        </div>
    </div>
</div>
@endsection
