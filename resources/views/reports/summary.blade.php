@extends('layouts.app')
@section('title', 'Salary Summary')
@section('content')
@include('reports._period')
<h3>Salary Summary - {{ \App\Support\Period::label($period) }}</h3>
<table class="table table-bordered table-condensed t">
    <thead><tr><th>Directorate</th><th class="num">Employees</th><th class="num">Earnings</th><th class="num">Deductions</th><th class="num">Net</th></tr></thead>
    @foreach ($rows as $r)
        <tr><td>{{ $r->dir_description }}</td><td class="num">{{ $r->employees }}</td><td class="num">{{ number_format($r->earnings, 2) }}</td>
            <td class="num">{{ number_format($r->deductions, 2) }}</td><td class="num">{{ number_format($r->net, 2) }}</td></tr>
    @endforeach
    <tr><th>Total</th><th class="num">{{ $rows->sum('employees') }}</th><th class="num">{{ number_format($rows->sum('earnings'), 2) }}</th>
        <th class="num">{{ number_format($rows->sum('deductions'), 2) }}</th><th class="num">{{ number_format($rows->sum('net'), 2) }}</th></tr>
</table>
@endsection
