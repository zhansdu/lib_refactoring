<?php


namespace App\Common\Fields\Acquisition;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class BatchFields extends FieldInterface
{
    protected static array $sortFields = [['key' => 'create_date', 'title' => 'Filled Date'],
        ['key' => 'status', 'title' => 'Status'],
        ['key' => 'id', 'title' => 'Batch number'],
        ['key' => 'items_no', 'title' => 'Quantity of items'],
        ['key' => 'titles_no', 'title' => 'Quantity of titles'],
        ['key' => 'created_by', 'title' => 'Created by'],
        ['key' => 'edited_by', 'title' => 'Edited by']];

    protected static array $addSearchFields = [
        ['key' => 'id', 'title' => 'Batch No', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'sup_id', 'title' => 'Supplier', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'status_key', 'title' => 'Statuses', 'method' => SearchFieldConstants::IN],
        ['key' => 'create_date', 'title' => 'Create date', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'edit_date', 'title' => 'Edit date', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'invoice_date', 'title' => 'Invoice date', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'cost', 'title' => 'Costs', 'method' => SearchFieldConstants::RANGE_NUMBER]
    ];
}
