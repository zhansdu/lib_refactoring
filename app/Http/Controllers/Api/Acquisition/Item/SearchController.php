<?php

namespace App\Http\Controllers\Api\Acquisition\Item;

use App\Common\Fields\Acquisition\ItemFields;
use App\Common\Fields\Media\MediaFields;
use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Controller\Search;
use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Query\QueryHelper;
use App\Common\Helpers\Search\SearchHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Acquisition\Item\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(SearchRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;
        $data = Search::search($request, QueryHelper::nestedQuery(new Item()), new ItemFields());
        return response()->json([
            'res' => CustomPaginate::getPaginate($data, $request, $perPage),
            'all' => $data->pluck('id')->toArray()
        ]);
    }

    public function autocomplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
            'max' => 'nullable|integer',
        ]);

        $validated['operator'] = 'and';
        $builder = SearchHelper::search(QueryHelper::unionAll(...GetModels::getModels()), MediaFields::getSearchFields(), $validated);
        $max = $validated['max'] ?? 20;

        $results = DB::select(
            DB::raw("select DISTINCT {$validated['key']} as result from ({$builder->toSql()}) FETCH NEXT {$max} ROWS ONLY"),
            $builder->getBindings()
        );

        return response()->json([
            'res' => array_values(array_column($results, 'result')),
        ]);
    }
}
