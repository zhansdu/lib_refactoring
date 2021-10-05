<?php


namespace App\Common\Fields\Cataloging;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class MediaFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'type', 'title' => 'Type of materials', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'query', 'title' => 'Query', 'method' => SearchFieldConstants::LIKE],
    ];
}
