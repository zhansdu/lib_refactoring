<?php

namespace App\Http\Controllers\Api\Acquisition\Item;

use App\Http\Controllers\Controller;
use App\Models\Acquisition\Item\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdditionalController extends Controller
{
    public function createData(): JsonResponse
    {
        $data = Item::createdData();
        return response()->json(['res' => $data]);
    }

    public function byBatchId(int $batchId): JsonResponse
    {
        $items = DB::select('select hm.hesab_id,
        coalesce((select b.title from lib_books b where b.book_id = hm.book_id),
        (select j.title from lib_journal_issues ji left outer join lib_journals j on j.journal_id = ji.journal_id where ji.j_issue_id = hm.j_issue_id),
        (select d.name from lib_discs d where d.disc_id = hm.disc_id)) as title,
        hm.count,
        inv_info.price,
        (nvl(inv_info.price,0) * hm.count) as total_sum,
        inv_info.sigle_type,
        inv_info.currency
        from lib_hesab_mats hm
        outer apply(select i.currency, i.price, i.sigle_type from lib_inventory i where i.book_id = hm.book_id or i.j_issue_id = hm.j_issue_id or i.disc_id = hm.disc_id fetch first row only) inv_info
        where hm.hesab_id = ?', [$batchId]);

        return response()->json([
            'res' => $items,
        ]);
    }

    public function specialities(): JsonResponse
    {
        return response()->json([
            'res' => Item::specialities()->get()->toArray(),
        ]);
    }
}
