<?php


namespace App\Common\Fields\Report;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class LoanFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'borrow_date', 'title' => 'Date', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'dep_id', 'title' => 'Department', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'user_type', 'title' => 'Count', 'method' => SearchFieldConstants::EQUALS],
    ];
}
