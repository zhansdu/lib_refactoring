<?php


namespace App\Common\Fields\Acquisition;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class PublisherFields extends FieldInterface
{
    protected static array $sortFields = [
        ['key' => 'id', 'title' => 'ID'],
        ['key' => 'name', 'title' => 'Name'],
        ['key' => 'com_name', 'title' => 'Commercial name'],
        ['key' => 'address', 'title' => 'Address'],
        ['key' => 'email', 'title' => 'E-mail'],
        ['key' => 'phone', 'title' => 'Phone number'],
        ['key' => 'fax', 'title' => 'Fax']
    ];

    protected static array $addSearchFields = [
        ['key' => 'name', 'title' => 'Name', 'method' => SearchFieldConstants::LIKE],
    ];
}
