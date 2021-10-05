<?php

namespace App\Http\Controllers\Api\Report\InventoryBooks;

use App\Exports\Report\InventoryBooksExport;
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

        $data = Item::inventoryBooks()->whereIn('bii.inventory_no', $validated['inventories'])->get();

        return Excel::download(new InventoryBooksExport($data, $validated['locale'] ?? app()->getLocale()), 'inventory_books.xlsx');
    }
}
