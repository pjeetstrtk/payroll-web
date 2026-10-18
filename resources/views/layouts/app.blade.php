<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BREB Payroll - @yield('title', 'Payroll')</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        body { background:#f4f6f8; }
        .topbar { background:#2f323a; color:#fff; padding:12px 20px; }
        .topbar a { color:#ddd; }
        .topbar .brand { font-weight:bold; font-size:17px; letter-spacing:.3px; }
        .side { background:#fff; min-height:calc(100vh - 50px); border-right:1px solid #e3e6e9; padding:0; }
        .side .grp { padding:9px 15px; background:#eef1f3; font-weight:bold; border-bottom:1px solid #e3e6e9; cursor:pointer; }
        .side a.it { display:block; padding:6px 15px 6px 28px; color:#444; border-bottom:1px solid #f2f2f2; }
        .side a.it:hover, .side a.it.on { background:#fff3e0; text-decoration:none; color:#b35900; }
        .content { padding:20px; }
        table.t th { background:#eef1f3; }
        .num { text-align:right; }
        @media print { .topbar,.side,.noprint { display:none !important; } .content { padding:0; } body { background:#fff; } }
    </style>
    @stack('head')
</head>
<body>
@php
    $admin = auth()->user()->isAdmin();
    $lk = fn ($slug) => route('lookup.index', $slug);
    $menu = [];
    if ($admin) {
        $menu['Employee'] = [
            ['Add New Employee', route('employees.create')], ['Search / Edit Employee', route('employees.index')],
            ['Promotion', $lk('promotions')], ['Joined This Month', route('employees.month', 'joined')],
            ['End Contract This Month', route('employees.month', 'ending')], ['CPF / GPF %', $lk('cpf-gpf')],
        ];
        $menu['Salary Process'] = [
            ['Process Salary', route('salary.process')], ['Month Close', route('salary.close')], ['Employees Not Paid', $lk('not-paid')],
        ];
        $menu['Overtime & Allowances'] = [
            ['Overtime / Leave', $lk('overtime')], ['Allowances, Deductions & Bonus', $lk('special-pay')],
        ];
        $menu['Loan & Advance'] = [['Loan Headers', $lk('loans')]];
        $menu['Management'] = [
            ['Cadre', $lk('cadres')], ['Management', $lk('managements')], ['Designation', $lk('designations')],
            ['Directorate', $lk('directorates')], ['Div / Cell', $lk('divcells')], ['Location / District', $lk('locations')],
            ['Revenue Projects', $lk('projects')], ['Allowances / Deductions', $lk('paycodes')],
        ];
        $menu['Bank'] = [['Banks', $lk('banks')], ['Bank Branches', $lk('bank-branches')]];
        $menu['Payscale Policy'] = [['Pay Scale', $lk('payscale')], ['House Rent', $lk('house-rent')], ['Tax Rate', $lk('tax-rate')]];
        $menu['Reports'] = [
            ['Payslip', route('reports.payslip')], ['Salary Summary', route('reports.summary')],
            ['Bank Advice', route('reports.bank')], ['Journal Voucher', route('reports.jv')],
        ];
    } else {
        $menu['My Salary'] = [['Payslip', route('reports.payslip')]];
    }
@endphp
<div class="topbar clearfix">
    <span class="brand pull-left"><i class="fa fa-bars"></i>&nbsp; BREB PAYROLL MANAGEMENT SYSTEM</span>
    <span class="pull-right">
        {{ auth()->user()->user_name }} ({{ auth()->user()->user_type }}) &nbsp;|&nbsp;
        <a href="{{ route('password') }}">Change Password</a> &nbsp;|&nbsp;
        <form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf
            <button class="btn btn-link btn-xs" style="color:#ddd;padding:0">Logout</button></form>
    </span>
</div>
<div class="container-fluid">
<div class="row">
    <div class="col-sm-3 col-md-2 side">
        <div class="grp"><a href="{{ route('dashboard') }}" style="color:inherit">Dashboard</a></div>
        @foreach ($menu as $title => $items)
            <div class="grp" data-toggle="collapse" data-target="#m{{ $loop->index }}">{{ $title }}</div>
            <div id="m{{ $loop->index }}" class="collapse {{ collect($items)->contains(fn ($i) => url()->current() === strtok($i[1], '?')) ? 'in' : '' }}">
                @foreach ($items as [$label, $url])
                    <a class="it {{ url()->current() === strtok($url, '?') ? 'on' : '' }}" href="{{ $url }}">{{ $label }}</a>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="col-sm-9 col-md-10 content">
        @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul style="margin:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </div>
</div>
</div>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
@stack('scripts')
</body>
</html>
