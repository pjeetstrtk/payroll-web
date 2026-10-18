@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h3>Dashboard <small>Current period: {{ \App\Support\Period::label($period) }}</small></h3>
@if (auth()->user()->isAdmin())
<div class="row" style="margin-bottom:15px">
    <div class="col-sm-4"><div class="well well-sm"><b>{{ $employees }}</b> employees</div></div>
    <div class="col-sm-4"><div class="well well-sm"><b>{{ $processed }}</b> salaries processed this period</div></div>
</div>
@endif
<h4>My salary - {{ \App\Support\Period::label($period) }}</h4>
@if ($lines->isEmpty())
    <p class="text-muted">No salary has been processed for you in this period.</p>
@else
<table class="table table-bordered table-condensed t" style="max-width:600px">
    <thead><tr><th>Code</th><th>Particulars</th><th class="num">Earnings</th><th class="num">Deductions</th></tr></thead>
    @foreach ($lines as $l)
        <tr><td>{{ $l->payid }}</td><td>{{ $l->payname }}</td>
            <td class="num">{{ $l->ad === 'A' ? number_format($l->amount, 2) : '' }}</td>
            <td class="num">{{ $l->ad === 'D' ? number_format($l->amount, 2) : '' }}</td></tr>
    @endforeach
    <tr><th colspan="2">Total</th><th class="num">{{ number_format($totals->allowances, 2) }}</th><th class="num">{{ number_format($totals->deductions, 2) }}</th></tr>
    <tr><th colspan="2">Net payable</th><th colspan="2" class="num">{{ number_format($totals->allowances - $totals->deductions, 2) }}</th></tr>
</table>
@endif
@endsection
