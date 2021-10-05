<?php

namespace App\Http\Controllers\Api\Acquisition\Item;

use App\Common\Fields\Acquisition\ItemFields;
use App\Common\Helpers\Show\FilterFields;
use App\Common\Helpers\Show\Index;
use App\Common\Helpers\Show\LastCreated;
use App\Common\Helpers\Show\SearchFields;
use App\Common\Helpers\Show\Show;
use App\Common\Helpers\Show\SortFields;
use App\Http\Controllers\Controller;
use App\Models\Acquisition\Item\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = Index::index($request, new Item(), ItemFields::getSortFields());
        return response()->json([
            'res' => $data
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $data = Show::show(new Item(), $id);
        return response()->json([
            'res' => $data
        ]);
    }

    public function lastCreated(Request $request): JsonResponse
    {
        $data = LastCreated::lastCreated(new Item());
        return response()->json([
            'res' => $data
        ]);
    }

    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new ItemFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new ItemFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new ItemFields())
        ]);
    }
}
