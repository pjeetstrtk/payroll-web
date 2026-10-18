@extends('layouts.app')
@section('title', 'Bank Advice')
@section('content')
@include('reports._period')
<h3>Bank Advice - {{ \App\Support\Period::label($period) }}</h3>
<table class="table table-bordered table-condensed t">
    <thead><tr><th>Bank / Branch</th><th>ID</th><th>Name</th><th>Account</th><th class="num">Net amount</th></tr></thead>
    @foreach ($rows as $r)
        <tr><td>{{ $r->bank_description }} - {{ $r->branch_description }}</td><td>{{ $r->employeeid }}</td><td>{{ $r->name }}</td>
            <td>{{ $r->accountnumber }}</td><td class="num">{{ number_format($r->trcv - $r->tdeduct, 2) }}</td></tr>
    @endforeach
    <tr><th colspan="4">Total</th><th class="num">{{ number_format($rows->sum(fn ($r) => $r->trcv - $r->tdeduct), 2) }}</th></tr>
</table>
@endsection
