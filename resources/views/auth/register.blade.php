<!-- resources/views/auth/signup.blade.php -->

@extends('layouts.app')

@section('content')
<div class="auth-container">
  <div class="auth-header">
    <h2>Sign Up for KMS</h2>
  </div>
  <form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
  </form>

  <div class="form-divider"><span>or</span></div>

  <div class="social-login">
    <button class="btn btn-outline-primary">Google</button>
    <button class="btn btn-outline-secondary">Facebook</button>
  </div>

  <p class="text-center mt-3">Already have an account? <a href="{{ route('login') }}">Login</a></p>
</div>
@endsection
