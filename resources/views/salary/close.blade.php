@extends('layouts.app')
@section('title', 'Month Close')
@section('content')
<h3>Month Close <small>{{ \App\Support\Period::label($period) }} &rarr; {{ \App\Support\Period::label($next) }}</small></h3>
<p><b>Not processed: {{ $count }} employee(s)</b></p>
@if ($count)
<table class="table table-condensed table-bordered t" style="max-width:700px"><thead><tr><th>ID</th><th>Name</th><th>Designation</th></tr></thead>
    @foreach ($pending as $e)<tr><td>{{ $e->employeeid }}</td><td>{{ $e->name }}</td><td>{{ $e->dsg_description }}</td></tr>@endforeach</table>
    @if ($count > 200) <p class="text-muted">Showing the first 200.</p> @endif
@endif
<form method="POST" action="{{ route('salary.doclose') }}" onsubmit="return confirm('Close {{ \App\Support\Period::label($period) }}? This cannot be undone.')">
    @csrf <button class="btn btn-danger">Close this month</button>
</form>
@endsection
