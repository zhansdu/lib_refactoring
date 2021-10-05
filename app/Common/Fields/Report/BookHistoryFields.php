<?php


namespace App\Common\Fields\Report;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class BookHistoryFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'barcode', 'title' => 'Barcode', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'id', 'title' => 'Inventory No', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'author', 'title' => 'Author', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'title', 'title' => 'Barcode', 'method' => SearchFieldConstants::LIKE],
    ];
}
