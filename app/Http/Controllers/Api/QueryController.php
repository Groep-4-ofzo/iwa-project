<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Query;

use App\Models\Station;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function getStations(Request $request)
    {
        $contractId = $request->route('identifier');
        $queryId = $request->route('queryID');
        $queryRecord = Query::with('groups.criteria.type')
            ->where('id', $queryId)
            ->where('contract_id', $contractId)
            ->firstOrFail();

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

        return response()->json($results);
    }

    public function show(Request $request)
    {
        $contractId = $request->route('identifier');
        $queryId = $request->route('queryID');

        $queryRecord = Query::with('groups.criteria.type')
            ->where('id', $queryId)
            ->where('contract_id', $contractId)
            ->get();

        return response()->json($queryRecord);
    }
}
