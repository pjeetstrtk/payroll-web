<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BREB Payroll Management System</title>
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <style>body{background:#2f323a url('{{ asset('assets/img/login-bg.jpg') }}') center/cover;} .box{max-width:340px;margin:90px auto;background:#fff;padding:28px;border-radius:4px;box-shadow:0 4px 18px rgba(0,0,0,.35)}</style>
</head>
<body>
<div class="box">
    <h3 style="margin-top:0">Log in</h3>
    @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="form-group"><input class="form-control" name="emp_id" placeholder="User ID" value="{{ old('emp_id') }}" autofocus></div>
        <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password"></div>
        <button class="btn btn-warning btn-block"><i class="fa fa-lock"></i>&nbsp; Log-in</button>
    </form>
</div>
</body>
</html>
