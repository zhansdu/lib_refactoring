<?php


namespace App\Common\Helpers\Show;


use App\Common\Interfaces\Fields\FieldInterface;
use JetBrains\PhpStorm\ArrayShape;

class SortFields
{
    #[ArrayShape(['fields' => "array", 'modes' => "\string[][]"])] public static function sortFields(FieldInterface $field): array
    {
        return [
            // ... "sort by" fields of table
            'fields' => $field::getSortFields(),
            // ... mode of sorting -> ascending or descending
            'modes' => [
                ['key' => 'asc', 'title' => 'Ascending'],
                ['key' => 'desc', 'title' => 'Descending'],
            ],
        ];
    }
}
