<?php


namespace App\Common\Interfaces\Fields;


abstract class FieldInterface
{
    protected static array $sortFields = [];
    protected static array $searchFields = [];
    protected static array $addSearchFields = [];
    protected static array $filterFields = [];

    public static function getSortFields(): array
    {
        return static::$sortFields;
    }

    public static function getSearchFields(): array
    {
        return static::$searchFields;
    }

    public static function getAddSearchFields(): array
    {
        return static::$addSearchFields;
    }

    public static function getFilterFields(): array
    {
        return static::$filterFields;
    }
}
