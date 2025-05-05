@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4>Verify Your Email Address</h4>
                </div>

                <div class="card-body text-center">
                    <p class="mb-4">
                        We've sent a 6-digit verification code to your email address.
                        Please check your inbox and enter the code below to continue.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="/mfa">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="text" name="code" class="form-control text-center" placeholder="Enter 6-digit code" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Verify</button>
                    </form>

                    <div class="mt-3 text-muted">
                        Didn't receive the code? <a href="{{ url('/mfa') }}">Resend</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
