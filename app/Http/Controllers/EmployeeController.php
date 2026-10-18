<?php

namespace App\Http\Controllers;

use App\Support\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /** form field => [label, type, required] */
    private const FIELDS = [
        'employeeid' => ['Employee ID', 'text', true], 'name' => ['Name', 'text', true], 'fathername' => ["Father's Name", 'text', false],
        'sex' => ['Sex', 'text', false], 'm_staus' => ['Marital Status', 'text', false], 'religion' => ['Religion', 'text', false],
        'dateofbirth' => ['Date of Birth', 'date', false], 'nid' => ['NID', 'text', false], 'tin' => ['TIN', 'text', false],
        'mob_no' => ['Mobile', 'text', false], 'email' => ['Email', 'text', false], 'lastdegree' => ['Last Degree', 'text', false],
        'presentaddress' => ['Present Address', 'text', false], 'permanentaddress' => ['Permanent Address', 'text', false],
        'designationid' => ['Designation', 'select', true], 'cadreid' => ['Cadre', 'select', false],
        'dir_divcell_id' => ['Directorate / Division', 'select', true], 'locationid' => ['Location', 'select', false],
        'revenueprojectid' => ['Revenue Project', 'select', false], 'employeetypeid' => ['Employee Type', 'text', false],
        'dateoffirstjoining' => ['Date of First Joining', 'date', false], 'joiningdatetopost' => ['Joining Date to Post', 'date', false],
        'dateofregularization' => ['Date of Regularization', 'date', false], 'retireendcontractdate' => ['Retire / End Contract Date', 'date', false],
        'presentpaygrade' => ['Pay Grade', 'number', true], 'noofincrementspaid' => ['Increments Paid', 'number', true],
        'nextincrementduedate' => ['Next Increment Due', 'date', false], 'incrementstatus' => ['Increment Status (1 = eligible)', 'number', false],
        'accommodation' => ['Accommodation', 'text', false], 'cpfgpfstatus' => ['CPF/GPF Status', 'text', false],
        'chargestatus' => ['Charge Status', 'text', false], 'suspendstatus' => ['Suspend Status', 'text', false],
        'bankbr_id' => ['Bank Branch', 'select', false], 'accountnumber' => ['Account Number', 'text', false],
        'overtimedailyrate' => ['Overtime Daily Rate', 'number', false], 'overtimehourlyrate' => ['Overtime Hourly Rate', 'number', false],
        'bloodgroup' => ['Blood Group', 'text', false], 'district_id' => ['District ID', 'text', false],
    ];

    private function options(): array
    {
        return [
            'designationid' => DB::table('tbl_designation')->orderBy('designationid')->pluck('dsg_description', 'designationid')->all(),
            'cadreid' => DB::table('tbl_cadre')->orderBy('cadreid')->pluck('cadre_description', 'cadreid')->all(),
            'dir_divcell_id' => DB::table('vw_division_cell')->orderBy('dir_divcell_id')->get()->mapWithKeys(fn ($r) => [$r->dir_divcell_id => $r->dir_description.' / '.$r->divcel_description])->all(),
            'locationid' => DB::table('tbl_location')->orderBy('locationid')->pluck('location_description', 'locationid')->all(),
            'revenueprojectid' => DB::table('tbl_project_revenue')->orderBy('revenueprojectid')->pluck('revenue_proj_description', 'revenueprojectid')->all(),
            'bankbr_id' => DB::table('vw_bank_branch')->orderBy('bankbr_id')->get()->mapWithKeys(fn ($r) => [$r->bankbr_id => $r->bank_description.' - '.$r->branch_description])->all(),
        ];
    }

    private function rules(?string $id = null): array
    {
        $rules = [];
        foreach (self::FIELDS as $f => [$label, $type, $req]) {
            $r = [$req ? 'required' : 'nullable', match ($type) { 'number' => 'numeric', 'date' => 'date', default => 'string' }];
            if ($type === 'text') {
                $r[] = 'max:500';
            }
            $rules[$f] = $r;
        }
        $rules['employeeid'][] = 'max:20';
        $rules['employeeid'][] = $id ? 'in:'.$id : 'unique:tbl_employeemaster,employeeid';

        return $rules;
    }

    public function index(Request $request)
    {
        $q = DB::table('vw_employee_all');
        if ($t = trim((string) $request->query('q'))) {
            $q->where(fn ($w) => $w->where('employeeid', 'like', "%{$t}%")->orWhere('name', 'like', "%{$t}%")->orWhere('mob_no', 'like', "%{$t}%")->orWhere('nid', 'like', "%{$t}%"));
        }
        if ($d = $request->query('directorate')) {
            $q->where('directorateid', $d);
        }

        return view('employees.index', [
            'employees' => $q->orderBy('employeeid')->paginate(25)->withQueryString(),
            'directorates' => DB::table('tbl_directorate')->orderBy('directorateid')->pluck('dir_description', 'directorateid'),
            'title' => 'Employees',
        ]);
    }

    /** "Joined This Month" / "End Contract This Month" lists. */
    public function monthList(string $kind)
    {
        abort_unless(in_array($kind, ['joined', 'ending'], true), 404);
        $p = Period::current();
        $col = $kind === 'joined' ? 'dateoffirstjoining' : 'retireendcontractdate';
        $rows = DB::table('vw_employee_all')->whereRaw("DATE_FORMAT({$col}, '%Y%m') = ?", [$p])->orderBy('employeeid')->paginate(50);

        return view('employees.index', [
            'employees' => $rows, 'directorates' => collect(), 'title' => ($kind === 'joined' ? 'Joined' : 'End of Contract').' in '.Period::label($p),
        ]);
    }

    public function show(string $id)
    {
        $e = DB::table('vw_employee_all')->where('employeeid', $id)->first();
        abort_unless($e, 404);

        return view('employees.show', ['e' => $e, 'fields' => self::FIELDS]);
    }

    public function create()
    {
        return view('employees.form', ['e' => null, 'fields' => self::FIELDS, 'options' => $this->options()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['incrementstatus'] = $data['incrementstatus'] ?? 1;
        $data['suspendstatus'] = $data['suspendstatus'] ?? '0';
        DB::table('tbl_employeemaster')->insert(array_map(fn ($v) => $v === '' ? null : $v, $data));

        return redirect()->route('employees.show', $data['employeeid'])->with('status', 'Employee saved.');
    }

    public function edit(string $id)
    {
        $e = DB::table('tbl_employeemaster')->where('employeeid', $id)->first();
        abort_unless($e, 404);

        return view('employees.form', ['e' => $e, 'fields' => self::FIELDS, 'options' => $this->options()]);
    }

    public function update(Request $request, string $id)
    {
        abort_unless(DB::table('tbl_employeemaster')->where('employeeid', $id)->exists(), 404);
        $data = $request->validate($this->rules($id));
        unset($data['employeeid']);
        DB::table('tbl_employeemaster')->where('employeeid', $id)->update(array_map(fn ($v) => $v === '' ? null : $v, $data));

        return redirect()->route('employees.show', $id)->with('status', 'Employee updated.');
    }
}
