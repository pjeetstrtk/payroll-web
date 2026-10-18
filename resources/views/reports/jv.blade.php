@extends('layouts.app')
@section('title', 'Journal Voucher')
@section('content')
@include('reports._period')
<h3>Journal Voucher - {{ \App\Support\Period::label($period) }}</h3>
<table class="table table-bordered table-condensed t">
    <thead><tr><th>GL Code</th><th>Particulars</th><th class="num">Debit</th><th class="num">Credit</th></tr></thead>
    @foreach ($rows as $r)
        <tr><td>{{ $r->gl }}</td><td>{{ $r->pay_name_gl ?: $r->payname }}</td>
            <td class="num">{{ $r->debit > 0 ? number_format($r->debit, 2) : '' }}</td><td class="num">{{ $r->credit > 0 ? number_format($r->credit, 2) : '' }}</td></tr>
    @endforeach
    <tr><td>{{ config('payroll.jv_balance_gl') }}</td><td>{{ config('payroll.jv_balance_name') }} (net payable)</td><td></td><td class="num">{{ number_format($net, 2) }}</td></tr>
    <tr><th colspan="2">Total</th><th class="num">{{ number_format($debit, 2) }}</th><th class="num">{{ number_format($credit + $net, 2) }}</th></tr>
</table>
@endsection
