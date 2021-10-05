<?php

namespace App\Http\Controllers\Api\Acquisition\Publisher;

use App\Common\Fields\Acquisition\PublisherFields;
use App\Common\Helpers\Show\FilterFields;
use App\Common\Helpers\Show\Index;
use App\Common\Helpers\Show\LastCreated;
use App\Common\Helpers\Show\SearchFields;
use App\Common\Helpers\Show\Show;
use App\Common\Helpers\Show\SortFields;
use App\Http\Controllers\Controller;
use App\Models\Acquisition\Publisher\Publisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = Index::index($request, new Publisher(), PublisherFields::getSortFields());
        return response()->json([
            'res' => $data
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $data = Show::show(new Publisher(), $id);
        return response()->json([
            'res' => $data
        ]);
    }

    public function lastCreated(Request $request): JsonResponse
    {
        $data = LastCreated::lastCreated(new Publisher());
        return response()->json([
            'res' => $data
        ]);
    }

    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new PublisherFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new PublisherFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new PublisherFields())
        ]);
    }
}
