<?php

namespace App\Http\Controllers\Api\Media;

use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Query\QueryHelper;
use App\Exports\Media\MediaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\MediaExportRequest;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function export(MediaExportRequest $request): BinaryFileResponse
    {
        $validated = $request->validated();
        $data = QueryHelper::unionAll(...GetModels::getModels())->whereIn('id', $validated['media'])->get();

        return Excel::download(new MediaExport($data, $validated['locale'] ?? app()->getLocale()), 'media.xlsx');
    }
}
