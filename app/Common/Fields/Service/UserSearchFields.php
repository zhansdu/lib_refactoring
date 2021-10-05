<?php


namespace App\Common\Fields\Service;

use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class UserSearchFields extends FieldInterface
{
    protected static array $sortFields = [
        ['key' => 'full_name', 'title' => 'Name'],
        ['key' => 'id', 'title' => 'User ID'],
        ['key' => 'faculty', 'title' => 'Faculty'],
        ['key' => 'department', 'title' => 'Department'],
    ];

    protected static array $addSearchFields = [
        ['key' => 'full_name', 'title' => 'Name', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'username', 'title' => 'Username|ID', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'all', 'title' => 'All', 'method' => SearchFieldConstants::LIKE],
    ];

    protected static array $filterFields = [
        ['key' => 'faculty', 'title_key' => 'faculty', 'title' => 'Faculty', 'method' => SearchFieldConstants::EQUALS],
    ];
}
