@extends('layouts.app')
@section('title', $e ? 'Edit Employee' : 'Add Employee')
@section('content')
<h3>{{ $e ? 'Edit Employee '.$e->employeeid : 'Add New Employee' }}</h3>
<form method="POST" class="form-horizontal" action="{{ $e ? route('employees.update', $e->employeeid) : route('employees.store') }}">
    @csrf @if ($e) @method('PUT') @endif
    @foreach ($fields as $name => [$label, $type, $req])
        @php $val = old($name, $e->$name ?? ''); @endphp
        <div class="form-group">
            <label class="col-sm-3 control-label">{{ $label }}@if($req) <span class="text-danger">*</span>@endif</label>
            <div class="col-sm-5">
                @if ($type === 'select')
                    <select name="{{ $name }}" class="form-control"><option value="">-- select --</option>
                        @foreach ($options[$name] as $k => $l) <option value="{{ $k }}" @selected((string) $val === (string) $k)>{{ $k }} - {{ $l }}</option> @endforeach</select>
                @else
                    <input name="{{ $name }}" value="{{ $val }}" class="form-control" type="{{ $type }}" @if($type === 'number') step="any" @endif @if($e && $name === 'employeeid') readonly @endif>
                @endif
            </div>
        </div>
    @endforeach
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><button class="btn btn-primary">Save</button>
        <a class="btn btn-default" href="{{ route('employees.index') }}">Cancel</a></div></div>
</form>
@endsection
