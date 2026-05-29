<?php

namespace App\Services;


class QueryRecordFilterService
{
    public function apply($sql, $queryRecord)
    {
        foreach ($queryRecord->groups as $group) {

            $method = ($group->operator == 1) ? 'where' : 'orWhere';

            $sql->$method(function ($sub) use ($group) {

                foreach ($group->criteria as $c) {

                    $column = $c->type->referenced_field;
                    $val = $c->int_value ?? $c->string_value;

                    $operator = [
                        1 => '=',
                        2 => '<',
                        3 => '<=',
                        4 => '>',
                        5 => '>=',
                        6 => '!=',
                    ][$c->value_comparison] ?? '=';

                    if (in_array($column, ['country_code', 'country'])) {
                        $sub->whereHas('geolocation', function ($geo) use ($column, $operator, $val) {
                            $geo->where($column, $operator, $val);
                        });
                    } else {
                        $sub->where($column, $operator, $val);
                    }
                }
            });
        }
        return $sql;
    }

}
