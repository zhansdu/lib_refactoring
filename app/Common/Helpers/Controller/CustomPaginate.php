<?php


namespace App\Common\Helpers\Controller;


use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomPaginate
{
    public static function getPaginate($items, Request $request, int $perPage): LengthAwarePaginator
    {
        $page = $request->get('page', 1);
        $offset = ($page * $perPage) - $perPage;

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
