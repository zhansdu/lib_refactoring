<?php


namespace App\Common\Helpers\Show;


use App\Common\Interfaces\Fields\FieldInterface;
use JetBrains\PhpStorm\ArrayShape;

class SearchFields
{
    #[ArrayShape(['search_options' => "array", 'add_options' => "array"])] public static function searchFields(FieldInterface $field): array
    {
        return [
            'search_options' => self::getFields($field::getSearchFields()),
            'add_options' => self::getFields($field::getAddSearchFields())
        ];
    }

    private static function getFields(array $fields): array
    {
        return array_map(function ($item) {
            return ['key' => $item['key'], 'title' => $item['title']];
        }, $fields);
    }
}
