
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ request('token') }}">
    <input type="hidden" name="email" value="{{ request('email') }}">

    <input type="password" name="password" required placeholder="New Password">
    <input type="password" name="password_confirmation" required placeholder="Confirm Password">

    <button type="submit">Reset Password</button>
</form>

