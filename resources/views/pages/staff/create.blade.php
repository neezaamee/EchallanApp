@extends('layouts.department')

@section('title','Create Staff')

@section('content')
<div class="card">
  <div class="card-body">
    <h4>Create Staff</h4>

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('staff.store') }}">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">First name</label>
          <input name="first_name" value="{{ old('first_name') }}" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Last name</label>
          <input name="last_name" value="{{ old('last_name') }}" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Belt No</label>
          <input name="belt_no" value="{{ old('belt_no') }}" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Phone</label>
          <input name="phone" value="{{ old('phone') }}" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Email</label>
          <input name="email" value="{{ old('email') }}" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">CNIC</label>
          <input name="cnic" value="{{ old('cnic') }}" class="form-control" required>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Province</label>
          <select name="province_id" class="form-select">
            <option value="">-- select --</option>
            @foreach($provinces as $p)
              <option value="{{ $p->id }}" @selected(old('province_id') == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">City</label>
          <select name="city_id" class="form-select">
            <option value="">-- select --</option>
            @foreach($cities as $c)
              <option value="{{ $c->id }}" @selected(old('city_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Designation</label>
          <select name="designation_id" class="form-select">
            <option value="">-- select --</option>
            @foreach($designations as $d)
              <option value="{{ $d->id }}" @selected(old('designation_id') == $d->id)>{{ $d->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Rank</label>
          <select name="rank_id" class="form-select">
            <option value="">-- select --</option>
            @foreach($ranks as $r)
              <option value="{{ $r->id }}" @selected(old('rank_id') == $r->id)>{{ $r->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <button class="btn btn-primary">Create Staff</button>
      <a href="{{ route('staff.index') }}" class="btn btn-link">Cancel</a>
    </form>
  </div>
</div>
@endsection
