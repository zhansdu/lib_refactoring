<?php


namespace App\Common\Fields\Report;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class BarcodeFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'barcode', 'title' => 'By barcode', 'method' => SearchFieldConstants::RANGE_NUMBER],
        ['key' => 'author', 'title' => 'Author', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'title', 'title' => 'Title', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'barcode_like', 'title' => 'Barcode', 'method' => SearchFieldConstants::LIKE],
    ];
}
