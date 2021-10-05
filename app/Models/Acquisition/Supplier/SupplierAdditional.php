<?php


namespace App\Models\Acquisition\Supplier;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

trait SupplierAdditional
{
    public static function names(): Builder
    {
        return static::query()->select('supplier_id as id', 'supplier_name as name')->orderBy('supplier_id');
    }

    public static function types(): QueryBuilder
    {
        return DB::table('lib_supply_types')
            ->select('key', 'title_' . app()->getLocale() . ' as title');
    }
}
