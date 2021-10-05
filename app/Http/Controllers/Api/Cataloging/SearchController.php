<?php

namespace App\Http\Controllers\Api\Cataloging;

use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Query\QueryHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Media\MaterialTypeFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $type = $validated['add_options'][0]['value'];
        $query = mb_strtolower($validated['add_options'][1]['value']);
        $perPage = $request->get('per_page', 10);

        if (empty($query)) {
            return response()->json([
                'res' => [],
                'all' => []
            ]);
        }

        if ($type !== 'all') {
            $materials = QueryHelper::nestedQuery(MaterialTypeFactory::getMaterialClass($type));
        } else {
            $materials = QueryHelper::unionAll(...GetModels::getModels());
        }

        $materials = $materials
            ->where(DB::raw("lower(isbn)"), 'like', '%'. $query . '%')
            ->orWhere(DB::raw("lower(title)"), 'like', '%'. $query . '%')
            ->orWhere(DB::raw("lower(author)"), 'like', '%'. $query . '%')
            ->get();

        return response()->json([
            'res' => CustomPaginate::getPaginate($materials, $request, $perPage),
            'all' => $materials->pluck('id')->toArray()
        ]);
    }
}
