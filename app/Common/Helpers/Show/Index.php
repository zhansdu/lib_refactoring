<?php


namespace App\Common\Helpers\Show;


use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Interfaces\Query\DefaultQueryInterface;
use App\Exceptions\ReturnResponseException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Index
{
    public static function index(Request $request, DefaultQueryInterface $query, array $sortFields): LengthAwarePaginator
    {
        $perPage = $request->get('per_page') ?? 10;
        $orderBy = $request->get('order_by');
        $orderMode = $request->get('order_mode') ?? 'desc';
        $data = $query::defaultQuery();

        if (!empty($orderBy)) {
            if (array_search($orderBy, array_column($sortFields, 'key')) === false) {
                throw new ReturnResponseException('Incorrect sort field', 400);
            }

            $data = $data->orderBy($orderBy, $orderMode);
        }

        return CustomPaginate::getPaginate($data->get(), $request, $perPage);
    }
}
