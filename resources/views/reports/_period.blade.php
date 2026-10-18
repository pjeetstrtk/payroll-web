<form method="GET" class="form-inline noprint" style="margin-bottom:12px">
    @isset($withEmployee) <input name="employee" class="form-control" placeholder="Employee ID" value="{{ $empId ?? '' }}"> @endisset
    <input name="period" class="form-control" value="{{ $period }}" placeholder="yyyymm" size="8">
    <button class="btn btn-default">Show</button> <button type="button" class="btn btn-default" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</form>
