<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/** Generic CRUD for every master/policy table declared in config/lookups.php. */
class LookupController extends Controller
{
    private function cfg(string $slug): array
    {
        $cfg = config("lookups.{$slug}");
        abort_unless($cfg, 404);
        $cfg['slug'] = $slug;

        return $cfg;
    }

    private function base(array $cfg)
    {
        $q = DB::table($cfg['table']);
        foreach ($cfg['fixed'] ?? [] as $col => $val) {
            $q->where($col, $val);
        }

        return $q;
    }

    private function options(array $cfg): array
    {
        $out = [];
        foreach ($cfg['fields'] as $name => $f) {
            if (($f['type'] ?? '') !== 'select') {
                continue;
            }
            $out[$name] = isset($f['static'])
                ? $f['static']
                : DB::table($f['options']['table'])->orderBy($f['options']['value'])->pluck($f['options']['label'], $f['options']['value'])->all();
        }

        return $out;
    }

    private function rules(array $cfg, ?string $key = null): array
    {
        $rules = [];
        foreach ($cfg['fields'] as $name => $f) {
            $r = [$f['required'] ? 'required' : 'nullable'];
            $r[] = match ($f['type']) {
                'number' => 'numeric',
                'date' => 'date',
                default => 'string',
            };
            if (isset($f['max'])) {
                $r[] = 'max:'.$f['max'];
            }
            if (($f['type'] ?? '') === 'select') {
                $r[] = Rule::in(array_map('strval', array_keys($this->options($cfg)[$name])));
            }
            if (! empty($f['pk'])) {
                $unique = Rule::unique($cfg['table'], $name);
                if ($key !== null) {
                    $unique = $unique->ignore($key, $name);
                }
                $r[] = $unique;
            }
            $rules[$name] = $r;
        }

        return $rules;
    }

    public function index(Request $request, string $slug)
    {
        $cfg = $this->cfg($slug);
        $query = $this->base($cfg);
        if ($term = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($cfg, $term) {
                foreach ($cfg['fields'] as $name => $f) {
                    if ($f['type'] === 'text') {
                        $q->orWhere($name, 'like', "%{$term}%");
                    }
                }
            });
        }
        $rows = $query->orderByRaw($cfg['order'])->paginate(20)->withQueryString();
        $edit = $request->query('edit') !== null ? $this->base($cfg)->where($cfg['pk'], $request->query('edit'))->first() : null;

        return view('lookup.index', ['cfg' => $cfg, 'rows' => $rows, 'edit' => $edit, 'options' => $this->options($cfg)]);
    }

    public function store(Request $request, string $slug)
    {
        $cfg = $this->cfg($slug);
        $data = $request->validate($this->rules($cfg));
        DB::table($cfg['table'])->insert(array_merge($this->clean($data), $cfg['fixed'] ?? []));

        return redirect()->route('lookup.index', $slug)->with('status', 'Saved.');
    }

    public function update(Request $request, string $slug, string $key)
    {
        $cfg = $this->cfg($slug);
        abort_unless($this->base($cfg)->where($cfg['pk'], $key)->exists(), 404);
        $rules = $this->rules($cfg, $key);
        $data = $request->validate($rules);
        $this->base($cfg)->where($cfg['pk'], $key)->update($this->clean($data));

        return redirect()->route('lookup.index', $slug)->with('status', 'Updated.');
    }

    public function destroy(string $slug, string $key)
    {
        $cfg = $this->cfg($slug);
        try {
            $this->base($cfg)->where($cfg['pk'], $key)->delete();
        } catch (\Throwable $e) {
            return back()->withErrors(['delete' => 'Cannot delete: the record is in use.']);
        }

        return redirect()->route('lookup.index', $slug)->with('status', 'Deleted.');
    }

    private function clean(array $data): array
    {
        return array_map(fn ($v) => $v === '' ? null : $v, $data);
    }
}
