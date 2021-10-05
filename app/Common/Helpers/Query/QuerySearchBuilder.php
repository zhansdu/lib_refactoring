<?php


namespace App\Common\Helpers\Query;


use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuerySearchBuilder
{
    public static function equals(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("{$column}"), $query);
    }

    public static function orEquals(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->orWhere(DB::raw("{$column}"), $query);
    }

    public static function notEquals(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("{$column}"), '!=', $query);
    }

    public static function like(EBuilder|Builder|ECollection|Collection $builder, string $column, ?string $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("lower({$column})"), 'like', '%' . mb_strtolower($query) . '%');
    }

    public static function orLike(EBuilder|Builder|ECollection|Collection $builder, string $column, ?string $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->orWhere(DB::raw("lower({$column})"), 'like', '%' . mb_strtolower($query) . '%');
    }

    public static function notLike(EBuilder|Builder|ECollection|Collection $builder, string $column, ?string $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("lower({$column})"), 'not like', '%' . mb_strtolower($query) . '%');
    }

    public static function in(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->whereIn(DB::raw("{$column}"), $query);
    }

    public static function orIn(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->orWhereIn(DB::raw("{$column}"), $query);
    }

    public static function notIn(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->whereNotIn(DB::raw("{$column}"), $query);
    }

    public static function rangeDate(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        if (!empty($query['from']) && !empty($query['to'])) {
            $builder = $builder->whereBetween($column,
                [$query['from'], $query['to']]);
        } else if (!empty($query['from'])) {
            $builder = $builder->whereRaw("{$column} >= to_date(?, 'YYYY-MM-DD')",
                [$query['from']]);
        } else if (!empty($query['to'])) {
            $builder = $builder->whereRaw("{$column} <= to_date(?, 'YYYY-MM-DD')",
                [$query['to']]);
        }

        return $builder;
    }

    public static function orRangeDate(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        if (!empty($query['from']) && !empty($query['to'])) {
            $builder = $builder->orWhereBetween($column,
                [$query['from'], $query['to']]);
        } else if (!empty($query['from'])) {
            $builder = $builder->orWhereRaw("{$column} >= to_date(?, 'YYYY-MM-DD')",
                [$query['from']]);
        } else if (!empty($query['to'])) {
            $builder = $builder->WhereRaw("{$column} <= to_date(?, 'YYYY-MM-DD')",
                [$query['to']]);
        }

        return $builder;
    }

    public static function rangeNumber(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        if (!empty($query['from']) && !empty($query['to'])) {
            $builder = $builder->whereBetween($column,
                [$query['from'], $query['to']]);
        } else if (!empty($query['from'])) {
            $builder = $builder->whereRaw("{$column} >= ?",
                [$query['from']]);
        } else if (!empty($query['to'])) {
            $builder = $builder->whereRaw("{$column} <= ?",
                [$query['to']]);
        }

        return $builder;
    }

    public static function orRangeNumber(EBuilder|Builder|ECollection|Collection $builder, string $column, ?array $query): EBuilder|Builder|ECollection|Collection
    {
        if (!empty($query['from']) && !empty($query['to'])) {
            $builder = $builder->orWhereBetween($column,
                [$query['from'], $query['to']]);
        } else if (!empty($query['from'])) {
            $builder = $builder->orWhereRaw("{$column} >= ?",
                [$query['from']]);
        } else if (!empty($query['to'])) {
            $builder = $builder->orWhereRaw("{$column} <= ?",
                [$query['to']]);
        }

        return $builder;
    }

    public static function lessThan(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("{$column}"), '<', $query);
    }

    public static function greaterThan(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("{$column}"), '>', $query);
    }

    public static function lessOrEqual(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("{$column}"), '<=', $query);
    }

    public static function greaterOrEqual(EBuilder|Builder|ECollection|Collection $builder, string $column, mixed $query): EBuilder|Builder|ECollection|Collection
    {
        return $builder->where(DB::raw("{$column}"), '>=', $query);
    }
}
