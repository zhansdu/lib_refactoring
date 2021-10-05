<?php

namespace App\Http\Controllers\Api\Report\InventoryBooks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\InventoryBooks\ExportRequest;
use App\Models\Acquisition\Item\Item;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function __invoke(ExportRequest $request): Response
    {
        $validated = $request->validated();

        $data = Item::inventoryBooks()->whereIn('bii.inventory_no', $validated['inventories'])->get();
        $count = $data->count();
        $cost = $data->sum('cost');

        $webLogId = DB::table("web_log")->select('log_id')
            ->where("user_id", Auth::user()->user_cid)->where("login_status", 1)->first()->log_id;
        DB::table("inventory_book")->whereIn("inventory_no", $validated['inventories'])->update([
            'print_date' => Carbon::now()->toDateString(),
            'web_log' => $webLogId
        ]);

        return PDF::loadView('prints.inventories', ['items' => $data, 'count' => $count, 'cost' => $cost, 'locale' => $validated['locale'] ?? app()->getLocale()])
            ->save(public_path('prints') . '/inventory_books.pdf')->stream();
    }
}
