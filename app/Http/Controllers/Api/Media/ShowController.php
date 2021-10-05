<?php

namespace App\Http\Controllers\Api\Media;

use App\Common\Fields\Media\MediaFields;
use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Show\FilterFields;
use App\Common\Helpers\Show\SearchFields;
use App\Common\Helpers\Show\SortFields;
use App\Common\Interfaces\Query\DefaultQueryInterface;
use App\Exceptions\ReturnResponseException;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $data = self::findByType($id, ...GetModels::getModels());
        $fullInfo = self::getFullInfo(explode('.', $data->getKeyName())[1], $id);

        return response()->json([
            'res' => $data->toArray(),
            'xmlInfo' => $fullInfo
        ]);
    }

    private static function findByType(int $id, DefaultQueryInterface ...$queries): Model|Builder
    {
        $data = null;
        foreach ($queries as $query) {
            $data = $query::defaultQuery()->find($id);

            if (!empty($data)) break;
        }

        if (empty($data)) throw new ReturnResponseException(__('general.data_not_found'), 404);
        return $data;
    }

    private static function getFullInfo(string $keyName, $id): array
    {
        return DB::table('view_marc_fileds as bi')->select()->where('bi.' . $keyName, $id)->get()->toArray();
    }

    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new MediaFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new MediaFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new MediaFields())
        ]);
    }
}
