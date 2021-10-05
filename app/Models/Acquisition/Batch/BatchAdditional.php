<?php


namespace App\Models\Acquisition\Batch;


use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

trait BatchAdditional
{
    public static function numbers(): EBuilder
    {
        return static::query()->select('b.hesab_id as id', 'b.title')->orderByDesc('id');
    }

    public static function status(int $id): EBuilder
    {
        return static::query()->select('b.hesab_id as id', 'b.items_no', 'b.titles_no',
            DB::raw("(select count(*) from lib_hesab_mats bm where b.hesab_id = bm.hesab_id group by bm.hesab_id) as titles_ma"),
            DB::raw("(select count(*) from lib_inventory i
                            inner join lib_hesab_mats bm on (i.book_id = bm.book_id or i.disc_id = bm.disc_id or i.j_issue_id = bm.j_issue_id)
                            where bm.hesab_id = b.hesab_id) as items_ma"))
            ->where('b.hesab_id', $id);
    }

    public static function statuses(): Builder
    {
        return DB::table('lib_batch_status')
            ->select('id as status', 'title_' . app()->getLocale() . ' as status_title');
    }
}
