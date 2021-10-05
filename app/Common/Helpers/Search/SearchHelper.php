<?php


namespace App\Common\Helpers\Search;


use App\Common\Helpers\Query\QuerySearchBuilder;
use App\Exceptions\ReturnResponseException;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder;

class SearchHelper
{
    public static function search(EBuilder|Builder $builder, array $fields, array $option): EBuilder|Builder
    {
        $methodName = self::getMethodName($option['key'], $fields);
        return self::buildByField($builder, $methodName, $option);
    }

    public static function getMethodName(string $key, array $fields)
    {
        $index = array_search($key, array_column($fields, 'key'));
        if ($index === false) throw new ReturnResponseException('Incorrect search field', 400);
        return $fields[$index]['method'];
    }

    public static function buildByField(EBuilder|Builder $builder, string $methodName, array $option): EBuilder|Builder
    {
        $option['operator'] = $option['operator'] ?? 'and';
        switch ($option['operator']) {
            case 'and':
                $builder = QuerySearchBuilder::$methodName($builder, $option['key'], $option['value']);
                break;
            default:
                $methodName = mb_strtolower($option['operator']) . ucfirst($methodName);
                $builder = QuerySearchBuilder::$methodName($builder, $option['key'], $option['value']);
        }

        return $builder;
    }
}
