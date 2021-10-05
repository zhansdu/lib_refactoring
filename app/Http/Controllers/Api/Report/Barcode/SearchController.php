<?php

namespace App\Http\Controllers\Api\Report\Barcode;

use App\Common\Fields\Report\BarcodeFields;
use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Controller\Search;
use App\Common\Helpers\Query\QueryHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Acquisition\Item\Item;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;
        $validated = $request->validated();

        $addOptions = $validated['add_options'];

        $index = array_search('barcode', array_column($addOptions, 'key'));

        if ($index !== false) {
            $barcodeValue = $addOptions[$index]['value'];

            if (isset($barcodeValue['from']) && isset($barcodeValue['to']) && $barcodeValue['to'] === '-') {
                $data = Item::barcodeQuery()->where('i.barcode', $barcodeValue['from'])->first();
                $data = collect([$data]);
            }
        }

        if (!isset($data)) {
            $data = Search::search($request, QueryHelper::nestedQueryBuilder(Item::barcodeQuery()), new BarcodeFields());
        }

        return response()->json([
            'res' => CustomPaginate::getPaginate($data, $request, $perPage),
            'all' => $data->pluck('id')->toArray()
        ]);
    }
}
