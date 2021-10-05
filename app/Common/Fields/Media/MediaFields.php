<?php


namespace App\Common\Fields\Media;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class MediaFields extends FieldInterface
{
    protected static array $sortFields = [
        ['key' => 'author', 'title' => 'Authors'],
        ['key' => 'title', 'title' => 'Title'],
        ['key' => 'publisher', 'title' => 'Publisher'],
        ['key' => 'isbn', 'title' => 'ISBN'],
        ['key' => 'call_number', 'title' => 'Call Number'],
        ['key' => 'year', 'title' => 'Year'],
    ];

    protected static array $searchFields = [
        ['key' => 'author', 'title' => 'Author(s)', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'title', 'title' => 'Title', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'year', 'title' => 'Published year', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'city', 'title' => 'Published city', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'publisher', 'title' => 'Publisher', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'isbn', 'title' => 'ISBN', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'call_number', 'title' => 'Call Number', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'all', 'title' => 'Keywords', 'method' => SearchFieldConstants::LIKE],
    ];

    protected static array $filterFields = [
        ['key' => 'year', 'title_key' => 'year', 'title' => 'Year', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'type_key', 'title_key' => 'type', 'title' => 'Type', 'method' => SearchFieldConstants::IN],
        ['key' => 'language', 'title_key' => 'language', 'title' => 'Language', 'method' => SearchFieldConstants::IN],
    ];
}
