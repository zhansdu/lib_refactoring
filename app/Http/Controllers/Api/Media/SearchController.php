<?php

namespace App\Http\Controllers\Api\Media;

use App\Common\Fields\Media\MediaFields;
use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Controller\Search;
use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Query\QueryHelper;
use App\Common\Helpers\Search\FilterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(SearchRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;

        $dataByContent = $this->contentSearch($request);
        $data = Search::search($request, QueryHelper::unionAll(...GetModels::getModels()), new MediaFields());
        $data = $data->merge($dataByContent);
        $forFilter = FilterHelper::forFilter($data, MediaFields::getFilterFields());

        return response()->json([
            'res' => CustomPaginate::getPaginate($data, $request, $perPage),
            'filter' => $forFilter,
            'all' => $data->pluck('id')->toArray()
        ]);
    }

    /**
     * @param SearchRequest $request
     * @return \Tightenco\Collect\Support\Collection|\Illuminate\Support\Collection
     */
    private function contentSearch(SearchRequest $request): \Tightenco\Collect\Support\Collection|\Illuminate\Support\Collection
    {
        $data = collect([]);
        $options = $request->input('search_options');

        if ($options[0]['key'] === 'all') {
            $value = $options[0]['value'];

            $searchResults = DB::select(DB::raw(
                "select coalesce(book_id, journal_id, disc_id) as id
                        from LIB_BIBLIOGRAPHIC_INFO t
                        where CONTAINS(t.xml_data, '$value INPATH (//TreeList/Nodes/Node/NodeData[Cell=\"650.x\"])', 1) > 0
                        ORDER BY SCORE(1) DESC"
            ));

            $ids = [];

            foreach ($searchResults as $result) {
                $ids[] = $result->id;
            }

            $data = QueryHelper::unionAll(...GetModels::getModels())->select()->whereIn('id', $ids)->get();
        }

        return $data;
    }
}
