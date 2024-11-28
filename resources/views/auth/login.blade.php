<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
<div class="auth-container">
  <div class="auth-header">
    <h2>Login to KMS</h2>
  </div>
  <form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>

  <div class="form-divider"><span>or</span></div>

  <div class="social-login">
    <button class="btn btn-outline-primary">Google</button>
    <button class="btn btn-outline-secondary">Facebook</button>
  </div>

  <div class="row">
      <p class="text-center mt-3">Don't have an account?

             <a href="{{ route('register') }}">Sign Up</a>
    
            <a class="ml ml-2" href="{{ route('password.request') }}">forgot password?</a>
        </p>
      <p class="text-center mt-3">

             <a href="{{ route('password.request') }}">Forgot Password?</a>
    
        </p>


  </div>
</div>
@endsection
