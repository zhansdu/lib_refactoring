<?php


namespace App\Common\Fields\Acquisition;

use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class ItemFields extends FieldInterface
{
    protected static array $sortFields = [['key' => 'barcode', 'title' => 'Barcode'],
        ['key' => 'id', 'title' => 'Inventory No'],
        ['key' => 'batch_id', 'title' => 'Batch No'],
        ['key' => 'author', 'title' => 'Author'],
        ['key' => 'title', 'title' => 'Title'],
        ['key' => 'create_date', 'title' => 'Filled Date'],
        ['key' => 'created_by', 'title' => 'Created By'],
        ['key' => 'edited_by', 'title' => 'Edited By']];

    protected static array $searchFields = [
        ['key' => 'id', 'title' => 'Inventory No', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'author', 'title' => 'Author', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'title', 'title' => 'Title', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'barcode', 'title' => 'Barcode', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'batch_id', 'title' => 'Batch No', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'isbn', 'title' => 'ISBN', 'method' => SearchFieldConstants::EQUALS],
    ];

    protected static array $addSearchFields = [
        ['key' => 'publisher_id', 'title' => 'Publisher', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'pub_year', 'title' => 'Publisher year', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'pub_city', 'title' => 'Publisher city', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'supplier_id', 'title' => 'Supplier', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'sup_type', 'title' => 'Supplier type', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'location', 'title' => 'Location', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'item_type', 'title' => 'Item type', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'user_cid', 'title' => 'User', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'create_date', 'title' => 'Dates', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'cost', 'title' => 'Costs', 'method' => SearchFieldConstants::RANGE_NUMBER],
        ['key' => 'prog_code', 'title' => 'Speciality', 'method' => SearchFieldConstants::EQUALS],
    ];
}
