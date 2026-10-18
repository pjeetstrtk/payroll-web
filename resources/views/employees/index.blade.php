@extends('layouts.app')
@section('title', $title)
@section('content')
<h3>{{ $title }} <a class="btn btn-sm btn-success pull-right" href="{{ route('employees.create') }}">Add New Employee</a></h3>
@if ($directorates->count())
<form method="GET" class="form-inline" style="margin-bottom:10px">
    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="ID / name / mobile / NID">
    <select name="directorate" class="form-control"><option value="">All directorates</option>
        @foreach ($directorates as $id => $d) <option value="{{ $id }}" @selected(request('directorate') == $id)>{{ $d }}</option> @endforeach</select>
    <button class="btn btn-default">Search</button>
</form>
@endif
<table class="table table-bordered table-condensed table-striped t">
    <thead><tr><th>ID</th><th>Name</th><th>Designation</th><th>Directorate / Division</th><th>Grade</th><th></th></tr></thead>
    @foreach ($employees as $e)
        <tr><td>{{ $e->employeeid }}</td><td>{{ $e->name }}</td><td>{{ $e->dsg_description }}</td>
            <td>{{ $e->dir_description }} / {{ $e->divcel_description }}</td><td>{{ $e->presentpaygrade }}/{{ $e->noofincrementspaid }}</td>
            <td><a href="{{ route('employees.show', $e->employeeid) }}">View</a> | <a href="{{ route('employees.edit', $e->employeeid) }}">Edit</a></td></tr>
    @endforeach
</table>
{{ $employees->links() }}
@endsection
