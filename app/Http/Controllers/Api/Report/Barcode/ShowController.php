<?php

namespace App\Http\Controllers\Api\Report\Barcode;

use App\Common\Fields\Report\BarcodeFields;
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
            'res' => SearchFields::searchFields(new BarcodeFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new BarcodeFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new BarcodeFields())
        ]);
    }
}
