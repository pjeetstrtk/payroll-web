@extends('layouts.app')
@section('title', $e->name)
@section('content')
<h3>{{ $e->name }} <small>{{ $e->employeeid }}</small> <a class="btn btn-sm btn-primary pull-right" href="{{ route('employees.edit', $e->employeeid) }}">Edit</a></h3>
<table class="table table-bordered table-condensed t" style="max-width:760px">
    <tr><th>Designation</th><td>{{ $e->dsg_description }}</td><th>Cadre</th><td>{{ $e->cadre_description }}</td></tr>
    <tr><th>Directorate</th><td>{{ $e->dir_description }}</td><th>Division / Cell</th><td>{{ $e->divcel_description }}</td></tr>
    <tr><th>Pay grade / increment</th><td>{{ $e->presentpaygrade }} / {{ $e->noofincrementspaid }}</td><th>Next increment due</th><td>{{ $e->nextincrementduedate }}</td></tr>
    <tr><th>Bank</th><td>{{ $e->bank_description }} - {{ $e->branch_description }}</td><th>Account</th><td>{{ $e->accountnumber }}</td></tr>
    <tr><th>Joined</th><td>{{ $e->dateoffirstjoining }}</td><th>Retire / end contract</th><td>{{ $e->retireendcontractdate }}</td></tr>
    <tr><th>Mobile</th><td>{{ $e->mob_no }}</td><th>Email</th><td>{{ $e->email }}</td></tr>
    <tr><th>NID</th><td>{{ $e->nid }}</td><th>TIN</th><td>{{ $e->tin }}</td></tr>
    <tr><th>Present address</th><td colspan="3">{{ $e->presentaddress }}</td></tr>
    <tr><th>Permanent address</th><td colspan="3">{{ $e->permanentaddress }}</td></tr>
</table>
<a href="{{ route('reports.payslip', ['employee' => $e->employeeid]) }}">View payslip</a>
@endsection
