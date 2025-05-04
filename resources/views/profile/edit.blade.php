@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <!-- Nickname -->
        <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>
            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname', $user->nickname) }}" placeholder="Enter your nickname">
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter your email">
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
            <small class="form-text text-muted">Leave blank if you don't want to change.</small>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your new password">
        </div>

        <!-- Phone -->
        <div class="mb-3">
            <label for="phone" class="form-label">Phone No</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter your phone number">
        </div>

        <!-- City -->
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}" placeholder="Enter your city">
        </div>

        <!-- Avatar -->
        <div class="mb-3">
            <label for="avatar" class="form-label">Avatar</label>
            <input type="file" class="form-control" id="avatar" name="avatar">
            @if($user->avatar)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $user->avatar) }}" width="100" alt="Avatar">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <!-- Delete Account Form -->
    <form method="POST" action="{{ route('profile.destroy') }}" style="margin-top: 20px;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">
            Delete Account
        </button>
    </form>
</div>
@endsection
