<?php


namespace App\Common\Helpers\Show;


use App\Common\Interfaces\Query\DefaultQueryInterface;
use App\Exceptions\ReturnResponseException;

class Show
{
    public static function show(DefaultQueryInterface $query, mixed $id): array
    {
        $data = $query::defaultQuery()->find($id);

        if (empty($data)) throw new ReturnResponseException(__('general.data_not_found'), 404);

        return $data->toArray();
    }
}
