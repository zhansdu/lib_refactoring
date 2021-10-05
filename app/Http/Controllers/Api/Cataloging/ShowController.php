<?php

namespace App\Http\Controllers\Api\Cataloging;

use App\Common\Fields\Cataloging\MediaFields;
use App\Common\Helpers\Show\SearchFields;
use App\Http\Controllers\Api\Cataloging\Handler\MarcFieldsHandler;
use App\Http\Controllers\Api\Cataloging\Handler\MarcFieldsXmlHandler;
use App\Http\Controllers\Controller;
use App\Models\Media\MaterialTypeFactory;
use App\Models\User\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShowController extends Controller
{
    public function getMaterialById(string $type, int $materialId): JsonResponse
    {
        $materialInstance = MaterialTypeFactory::getMaterialClass($type);
        $keyName = explode('.', $materialInstance->getKeyName());

        $marcData = DB::table('view_marc_data')->select()->where($keyName[1] ?? $keyName[0], $materialId)->orderBy('id')->get()->toArray();

        if ($type === 'BK') {
            $actualData = DB::table('lib_books as b')
                ->select(
                    'b.isbn', 'b.title', 'p.name as publisher', 'b.pub_year', 'b.pub_city',
                    'b.parallel_title', 'b.title_related_info', 'b.language',
                    DB::raw("(select listagg(a.name||''||a.surname, ',')
                                within group(order by a.name||''||a.surname)
                                from lib_book_authors a where a.book_id = b.book_id and a.is_main = 1) as main_author"),
                    'b.page_num'
                )
                ->leftJoin('lib_publishers as p', 'p.publisher_id', '=', 'b.publisher_id')
                ->where('b.book_id', $materialId)
                ->first();
            $info = DB::table('lib_bibliographic_info')
                ->select('info_id')
                ->where('book_id', $materialId)
                ->first();
        }

        $result = MarcFieldsHandler::generateArray($marcData, $actualData ?? null);

        if (isset($info)) {
            $log = DB::table('lib_logs')
                ->where('row_id', $info->info_id)
                ->whereNotNull('emp_id')
                ->where('emp_id', '!=', 0)
                ->select([
                    'emp_id',
                    'action_date',
                ])
                ->orderBy('action_date')
                ->first();

            if (!empty($log)) {
                $employee = Employee::find($log->emp_id);
                $createdInfo = [
                    'created_by' => $employee->name . ' ' . $employee->sname,
                    'created_at' => $log->action_date,
                ];
            }
        }

        return response()->json([
            'res' => $result,
            'created_info' => $createdInfo ?? [],
            'state' => $materialInstance
                    ->query()
                    ->select('state')
                    ->where($keyName[1] ?? $keyName[0], $materialId)
                    ->first()?->state ?? 'not_started',
        ]);
    }

    public function getTypes(): JsonResponse
    {
        return response()->json([
            'res' => DB::table('lib_material_types')
                ->select('key as type', 'title_' . app()->getLocale() . ' as type_title')
                ->get()->toArray(),
        ]);
    }

    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new MediaFields())
        ]);
    }

    public function exportXml(string $type, int $materialId): BinaryFileResponse
    {
        $keyName = explode('.', MaterialTypeFactory::getMaterialClass($type)->getKeyName());

        $marcData = DB::table('view_marc_data')->select()->where($keyName[1] ?? $keyName[0], $materialId)->orderBy('id')->get()->toArray();
        $template = (new MarcFieldsHandler([], $marcData))->getTemplate();
        $xml = (new MarcFieldsXmlHandler($template))->getXml();

        File::put(storage_path('') . "/material_{$materialId}.xml", $xml);

        return response()->download(storage_path('') . "/material_{$materialId}.xml");
    }

    /**
     * @param string $type
     * @param int $materialId
     * @return JsonResponse
     */
    public function completeCataloging(string $type, int $materialId): JsonResponse
    {
        $materialQuery = MaterialTypeFactory::getMaterialQuery($type, $materialId);

        DB::transaction(function () use ($materialQuery) {
            $materialQuery
                ->update([
                    'state' => 'completed',
                ]);
        });

        return response()->json([
            'res' => 'success'
        ]);
    }

    /**
     * @param string $type
     * @param int $materialId
     * @return JsonResponse
     */
    public function madeByHistory(string $type, int $materialId): JsonResponse
    {
        $materialInstance = MaterialTypeFactory::getMaterialClass($type);
        $keyName = explode('.', $materialInstance->getKeyName());

        $info = DB::table('lib_bibliographic_info')
            ->select('info_id')
            ->where($keyName[1] ?? $keyName[0], $materialId)
            ->first();

        $history = [];

        if (!empty($info)) {
            $logs = DB::table('lib_logs')
                ->where('row_id', $info->info_id)
                ->whereNotNull('emp_id')
                ->where('emp_id', '!=', 0)
                ->select([
                    'emp_id',
                    'action_date',
                ])
                ->orderBy('action_date')
                ->get();

            if (!empty($logs)) {
                foreach ($logs as $log) {
                    $employee = Employee::find($log->emp_id);

                    $history[] = [
                        'made_by' => $employee->name . ' ' . $employee->sname,
                        'made_at' => $log->action_date,
                    ];
                }
            }
        }

        return response()->json([
            'res' => $history,
        ]);
    }
}
