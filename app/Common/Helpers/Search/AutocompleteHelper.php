<?php


namespace App\Common\Helpers\Search;


use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AutocompleteHelper
{
    public static function autocomplete(EBuilder|Builder $builder, array $fields, array $option): array
    {
        $option['operator'] = 'and';
        $builder = SearchHelper::search($builder, $fields, $option);
        $max = $option['max'] ?? 20;

        return DB::select(
            DB::raw("select id, {$option['key']} as result from ({$builder->toSql()}) FETCH NEXT {$max} ROWS ONLY"),
            $builder->getBindings()
        );
    }
}
