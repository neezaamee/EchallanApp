@extends('layouts.app')
@section('page-title', 'Create Permission - ')
@section('cms-main-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Create New Permission</h5>
             <a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}"> Back</a>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection
