<?php

namespace App\Http\Controllers;

use App\Models\ComparisonOperatorType;
use App\Models\Criterium;
use App\Models\CriteriumGroup;
use App\Models\CriteriumType;
use App\Models\OperatorType;
use App\Models\Station;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'criteriumTypes' => CriteriumType::all(),
            'operators' => OperatorType::all(),
            'comparisons' => ComparisonOperatorType::all(),
            'queries' => Query::all(),
            'groupCount' => (int) $request->get('groups', 1),
            'criteriaCounts' => $request->get('criteria', [0 => 1]),
        ];

        return view('query-builder', $data);
    }

    public function store(Request $request)
    {
        $query = DB::transaction(function () use ($request) {
            $q = Query::create(['contract_id' => 1, 'omschrijving' => $request->omschrijving]);
            foreach ($request->input('groups_data', []) as $gIndex => $gData) {
                $group = CriteriumGroup::create([
                    'query' => $q->id,
                    'operator' => $gData['operator'],
                    'group_level' => 1,
                    'type' => 1,
                ]);
                foreach ($gData['criteria'] as $cData) {
                    Criterium::create([
                        'group' => $group->id,
                        'value_type' => $cData['type'],
                        'value_comparison' => $cData['comparison'],
                        'string_value' => $cData['value'],
                        'int_value' => is_numeric($cData['value']) ? (int) $cData['value'] : null,
                        'operator' => 1,
                    ]);
                }
            }

            return $q;
        });

        return redirect()->route('query.execute', $query->id);
    }

    public function execute($id)
    {
        $queryRecord = Query::with('groups.criteria.type')->findOrFail($id);

        $sql = Station::query();

        foreach ($queryRecord->groups as $group) {
            $method = ($group->operator == 1) ? 'where' : 'orWhere';

            $sql->$method(function ($sub) use ($group) {
                foreach ($group->criteria as $c) {

                    $column = $c->type->referenced_field;
                    $table = $c->type->referenced_table;

                    $val = $c->int_value ?? $c->string_value;

                    $operator = [
                        1 => '=',
                        2 => '<',
                        3 => '<=',
                        4 => '>',
                        5 => '>=',
                        6 => '!=',
                    ][$c->value_comparison] ?? '=';

                    if ($column === 'country_code' || $column === 'country') {

                        $sub->whereHas('geolocation', function ($geo) use ($column, $operator, $val) {
                            $geo->where($column, $operator, $val);
                        });

                    } else {

                        $sub->where($column, $operator, $val);
                    }
                }
            });
        }

        $results = $sql
            ->with('geolocation') 
            ->distinct()
            ->get();

        return view('query-results', compact('results', 'queryRecord'));
    }
}
