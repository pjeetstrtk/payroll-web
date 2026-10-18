@extends('layouts.app')
@section('title', 'Payslip')
@section('content')
@include('reports._period', ['withEmployee' => auth()->user()->isAdmin()])
<h3>Payslip - {{ \App\Support\Period::label($period) }}</h3>
@if (! $emp)
    <p class="text-muted">No processed salary found for the selection.</p>
@else
<table class="table table-bordered table-condensed" style="max-width:720px">
    <tr><th>Employee</th><td>{{ $emp->employeeid }} - {{ $emp->name }}</td><th>Designation</th><td>{{ $emp->dsg_description }}</td></tr>
    <tr><th>Directorate</th><td>{{ $emp->dir_description }}</td><th>Division / Cell</th><td>{{ $emp->divcel_description }}</td></tr>
    <tr><th>Bank</th><td>{{ $emp->bank_description }} - {{ $emp->branch_description }}</td><th>Account</th><td>{{ $emp->accountnumber }}</td></tr>
</table>
<table class="table table-bordered table-condensed t" style="max-width:720px">
    <thead><tr><th>Code</th><th>Particulars</th><th class="num">Earnings</th><th class="num">Deductions</th></tr></thead>
    @foreach ($lines as $l)
        <tr><td>{{ $l->payid }}</td><td>{{ $l->payname }} @if($l->remarks)<small class="text-muted">{{ $l->remarks }}</small>@endif</td>
            <td class="num">{{ $l->ad === 'A' ? number_format($l->amount, 2) : '' }}</td><td class="num">{{ $l->ad === 'D' ? number_format($l->amount, 2) : '' }}</td></tr>
    @endforeach
    <tr><th colspan="2">Total</th><th class="num">{{ number_format($emp->trcv, 2) }}</th><th class="num">{{ number_format($emp->tdeduct, 2) }}</th></tr>
    <tr><th colspan="2">Net payable</th><th colspan="2" class="num">{{ number_format($emp->trcv - $emp->tdeduct, 2) }}</th></tr>
</table>
@endif
@endsection
