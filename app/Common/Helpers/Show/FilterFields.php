<?php


namespace App\Common\Helpers\Show;


use App\Common\Interfaces\Fields\FieldInterface;
use JetBrains\PhpStorm\Pure;

class FilterFields
{
    #[Pure] public static function filterFields(FieldInterface $field): array
    {
        return $field::getFilterFields();
    }
}
