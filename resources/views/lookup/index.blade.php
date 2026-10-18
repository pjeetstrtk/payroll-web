@extends('layouts.app')
@section('title', $cfg['title'])
@section('content')
@php $editing = $edit !== null; $pk = $cfg['pk']; @endphp
<h3>{{ $cfg['title'] }}</h3>
<form method="POST" class="form-horizontal well" action="{{ $editing ? route('lookup.update', [$cfg['slug'], $edit->$pk]) : route('lookup.store', $cfg['slug']) }}">
    @csrf
    @if ($editing) @method('PUT') @endif
    @foreach ($cfg['fields'] as $name => $f)
        @php $val = old($name, $editing ? ($edit->$name ?? '') : ''); @endphp
        <div class="form-group">
            <label class="col-sm-3 control-label">{{ $f['label'] }}@if($f['required']) <span class="text-danger">*</span>@endif</label>
            <div class="col-sm-5">
                @if ($f['type'] === 'select')
                    <select name="{{ $name }}" class="form-control"><option value="">-- select --</option>
                        @foreach ($options[$name] as $k => $label) <option value="{{ $k }}" @selected((string) $val === (string) $k)>{{ $k }} - {{ $label }}</option> @endforeach
                    </select>
                @else
                    <input name="{{ $name }}" value="{{ $val }}" class="form-control" type="{{ $f['type'] === 'number' ? 'number' : ($f['type'] === 'date' ? 'date' : 'text') }}" @if($f['type'] === 'number') step="any" @endif>
                @endif
            </div>
        </div>
    @endforeach
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9">
        <button class="btn btn-primary">{{ $editing ? 'Update' : 'Save' }}</button>
        @if ($editing) <a href="{{ route('lookup.index', $cfg['slug']) }}" class="btn btn-default">Add New</a> @endif
    </div></div>
</form>

<form method="GET" class="form-inline noprint" style="margin-bottom:10px">
    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"> <button class="btn btn-default">Search</button>
</form>
<table class="table table-bordered table-condensed table-striped t">
    <thead><tr>
        @foreach ($cfg['fields'] as $f) <th>{{ $f['label'] }}</th> @endforeach <th style="width:120px"></th>
    </tr></thead>
    @foreach ($rows as $r)
        <tr>
            @foreach ($cfg['fields'] as $name => $f)
                <td>@if ($f['type'] === 'select') {{ $options[$name][$r->$name] ?? $r->$name }} @else {{ $r->$name }} @endif</td>
            @endforeach
            <td>
                <a class="btn btn-xs btn-default" href="{{ route('lookup.index', [$cfg['slug'], 'edit' => $r->$pk]) }}">Select</a>
                <form method="POST" action="{{ route('lookup.destroy', [$cfg['slug'], $r->$pk]) }}" style="display:inline" onsubmit="return confirm('Delete this record?')">
                    @csrf @method('DELETE') <button class="btn btn-xs btn-danger">Delete</button></form>
            </td>
        </tr>
    @endforeach
</table>
{{ $rows->links() }}
@endsection
