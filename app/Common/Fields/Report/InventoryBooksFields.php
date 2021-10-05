<?php


namespace App\Common\Fields\Report;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class InventoryBooksFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'inventory_no', 'title' => 'Item No', 'method' => SearchFieldConstants::GREATER_OR_EQUAL],
        ['key' => 'rownum', 'title' => 'Count', 'method' => SearchFieldConstants::LESS_OR_EQUAL],
    ];
}
