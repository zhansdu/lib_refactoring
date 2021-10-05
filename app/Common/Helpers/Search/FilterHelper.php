<?php


namespace App\Common\Helpers\Search;


use App\Common\Helpers\Query\QuerySearchBuilder;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Support\Collection;

class FilterHelper
{
    public static function filter(ECollection|Collection $data, array $fields, array $option)
    {
        $methodName = SearchHelper::getMethodName($option['key'], $fields);
        return QuerySearchBuilder::$methodName($data, $option['key'], $option['value']);
    }

    public static function forFilter(ECollection|Collection $data, array $fields): array
    {
        $res = [];
        $data->each(function ($item) use ($fields, &$res) {
            foreach ($fields as $field) {
                $key = $field['key'];
                $titleKey = $field['title_key'];

                if (!empty($item->$key)) {
                    if ($key === $titleKey) {
                        $value = $item->$key;
                    } else {
                        $value = [
                            'value' => $item->$key,
                            'title' => $item->$titleKey,
                        ];
                    }

                    if (!key_exists($key, $res)) $res[$key] = [];

                    if (!in_array($value, $res[$key])) {
                        $res[$key][] = $value;
                    }
                }
            }
        });
        return $res;
    }
}
