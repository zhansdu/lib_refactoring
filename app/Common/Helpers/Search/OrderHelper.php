<?php


namespace App\Common\Helpers\Search;


use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder;

class OrderHelper
{
    public static function order(EBuilder|Builder $builder, array $options): EBuilder|Builder
    {
        return $builder->orderBy($options['key'] ?? 'id', $options['mode'] ?? 'desc');
    }
}
