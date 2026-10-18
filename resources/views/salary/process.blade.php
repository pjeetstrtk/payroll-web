@extends('layouts.app')
@section('title', 'Process Salary')
@section('content')
<h3>Process Salary <small>{{ \App\Support\Period::label($period) }}</small></h3>
<p>{{ $pending }} employee(s) not yet processed in this period.</p>
<div class="alert alert-warning">
    The calculation was reconstructed from the compiled original. Items marked <b>VERIFY</b> in
    <code>app/Services/SalaryProcessor.php</code> must be checked against real payslips before payroll is run for real.
</div>
@if ($result)
    <div class="alert alert-info"><b>{{ $result['ok'] }}</b> employee(s) processed.</div>
    @if ($result['errors']) <div class="alert alert-danger"><b>Errors</b><ul>@foreach ($result['errors'] as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
    @if ($result['warnings']) <div class="alert alert-warning"><b>Warnings</b><ul>@foreach ($result['warnings'] as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
@endif
<form method="POST" action="{{ route('salary.run') }}" class="form-inline" onsubmit="return confirm('Process salary for the selected employees? Existing results for this period are replaced.')">
    @csrf
    <select name="scope" class="form-control" id="scope">
        <option value="all">All employees</option><option value="directorate">One directorate</option><option value="employee">One employee</option></select>
    <select name="directorate" class="form-control"><option value="">-- directorate --</option>
        @foreach ($directorates as $id => $d)<option value="{{ $id }}">{{ $d }}</option>@endforeach</select>
    <input name="employee" class="form-control" placeholder="Employee ID">
    <button class="btn btn-primary">Process</button>
</form>
@endsection
