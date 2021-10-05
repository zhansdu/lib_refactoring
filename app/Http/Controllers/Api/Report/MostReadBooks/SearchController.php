<?php

namespace App\Http\Controllers\Api\Report\MostReadBooks;

use App\Common\Fields\Report\MostReadBooksFields;
use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Controller\Search;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Media\Loan;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;
        $data = Search::search($request, Loan::mostReadBooks(), new MostReadBooksFields());

        return response()->json([
            'res' => CustomPaginate::getPaginate($data, $request, $perPage),
            'all' => $data->pluck('id')->toArray()
        ]);
    }
}
