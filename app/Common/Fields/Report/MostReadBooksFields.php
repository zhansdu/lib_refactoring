<?php


namespace App\Common\Fields\Report;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class MostReadBooksFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'borrow_date', 'title' => 'Date', 'method' => SearchFieldConstants::RANGE_DATE]
    ];
}
