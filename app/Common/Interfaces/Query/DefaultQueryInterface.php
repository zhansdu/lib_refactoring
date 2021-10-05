<?php


namespace App\Common\Interfaces\Query;


use Illuminate\Database\Eloquent\Builder;

interface DefaultQueryInterface
{
    public static function defaultQuery(): Builder;

    public function getKeyName();
}
