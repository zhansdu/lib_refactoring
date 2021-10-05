<?php

namespace App\Http\Controllers\Api\Report\InventoryBooks;

use App\Common\Fields\Report\InventoryBooksFields;
use App\Common\Helpers\Show\FilterFields;
use App\Common\Helpers\Show\SearchFields;
use App\Common\Helpers\Show\SortFields;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new InventoryBooksFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new InventoryBooksFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new InventoryBooksFields())
        ]);
    }
}
