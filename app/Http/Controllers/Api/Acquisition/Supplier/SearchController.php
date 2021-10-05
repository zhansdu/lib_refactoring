<?php

namespace App\Http\Controllers\Api\Acquisition\Supplier;

use App\Common\Fields\Acquisition\SupplierFields;
use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Controller\Search;
use App\Common\Helpers\Query\QueryHelper;
use App\Common\Helpers\Search\AutocompleteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Acquisition\Supplier\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(SearchRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;
        $data = Search::search($request, QueryHelper::nestedQuery(new Supplier()), new SupplierFields());
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

        return response()->json([
            'res' => AutocompleteHelper::autocomplete(QueryHelper::nestedQuery(new Supplier()), SupplierFields::getAddSearchFields(), $validated),
        ]);
    }
}
