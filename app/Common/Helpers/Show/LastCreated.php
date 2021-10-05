<?php


namespace App\Common\Helpers\Show;


use App\Common\Interfaces\Query\DefaultQueryInterface;

class LastCreated
{
    public static function lastCreated(DefaultQueryInterface $query): array
    {
        return [$query::defaultQuery()->orderBy('id', 'desc')->first()->toArray()];
    }
}
