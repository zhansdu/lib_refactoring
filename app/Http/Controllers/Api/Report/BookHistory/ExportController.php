<?php

namespace App\Http\Controllers\Api\Report\BookHistory;

use App\Exports\Report\BookHistoryExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\InventoryBooks\ExportRequest;
use App\Models\Acquisition\Item\Item;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __invoke(ExportRequest $request): BinaryFileResponse
    {
        $validated = $request->validated();

        $data = Item::bookHistory()->whereIn('i.inv_id', $validated['inventories'])->get();

        return Excel::download(new BookHistoryExport($data, $validated['locale'] ?? app()->getLocale()), 'book_history.xlsx');
    }
}
