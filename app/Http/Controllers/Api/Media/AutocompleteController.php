<?php

namespace App\Http\Controllers\Api\Media;

use App\Common\Fields\Media\MediaFields;
use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Query\QueryHelper;
use App\Common\Helpers\Search\AutocompleteHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function autocomplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
            'max' => 'nullable|integer',
        ]);

        return response()->json([
            'res' => AutocompleteHelper::autocomplete(QueryHelper::unionAll(...GetModels::getModels()), MediaFields::getSearchFields(), $validated)
        ]);
    }
}
