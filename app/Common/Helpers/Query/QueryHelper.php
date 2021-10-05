<?php


namespace App\Common\Helpers\Query;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class QueryHelper
{
    public static function unionAll(DefaultQueryInterface ...$queries): Builder
    {
        $union = $queries[0]::defaultQuery()->getQuery();

        for ($i = 1; $i < sizeof($queries); $i++) {
            $union = $union->unionAll($queries[$i]::defaultQuery()->getQuery());
        }

        return DB::table(DB::raw("({$union->toSql()})"));
    }

    public static function nestedQuery(DefaultQueryInterface $query): Builder
    {
        return DB::table(DB::raw("({$query::defaultQuery()->getQuery()->toSql()})"));
    }

    public static function nestedQueryBuilder(EBuilder|Builder $builder): Builder
    {
        return DB::table(DB::raw("({$builder->toSql()})"));
    }
}
