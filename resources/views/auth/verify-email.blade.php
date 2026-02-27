@extends('layouts.public')

@section('title', 'Verify Email')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body p-4">
        <h4>Verify your email</h4>
        <p>We sent a verification link to your email. Please check your inbox and click the link to verify your account.</p>

        @if (session('status') === 'verification-link-sent')
          <div class="alert alert-success">A new verification link has been sent to your email address.</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button class="btn btn-primary">Resend Verification Email</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
