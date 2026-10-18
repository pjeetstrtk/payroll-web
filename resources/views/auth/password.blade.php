@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
<h3>Change Password</h3>
<form method="POST" action="{{ route('password.update') }}" style="max-width:380px">
    @csrf
    <div class="form-group"><label>Current password</label><input type="password" name="current_password" class="form-control"></div>
    <div class="form-group"><label>New password</label><input type="password" name="new_password" class="form-control">
        <p class="help-block">5-15 characters with upper case, lower case, a number and a special character.</p></div>
    <div class="form-group"><label>Confirm new password</label><input type="password" name="new_password_confirmation" class="form-control"></div>
    <button class="btn btn-primary">Change Password</button>
</form>
@endsection
