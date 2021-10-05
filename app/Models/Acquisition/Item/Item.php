<?php

namespace App\Models\Acquisition\Item;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model implements DefaultQueryInterface
{
    use ItemManage;
    use ItemAdditional;
    use ItemReports;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'lib_inventory as i';
    protected $primaryKey = 'i.inv_id';
    protected $fillable = [
        'inv_id',
        'book_id',
        'j_issue_id',
        'disc_id',
        'receive_date',
        'oda_id',
        'status',
        'old_inv_id',
        'hesab_id',
        'emeliyyat_no',
        'price',
        'currency',
        'barcode',
        'sigle_type',
        'user_cid',
        'user_name',
        'edited_by'
    ];

    public static function defaultQuery(): Builder
    {
        $lang = app()->getLocale();
        return static::query()->select(
            'i.barcode',
            'i.inv_id as id',
            'i.old_inv_id',
            'i.hesab_id as batch_id',
            'b.book_id',
            'ji.j_issue_id',
            'd.disc_id',
            DB::raw("coalesce(b.prog_code, j.prog_code, d.prog_code) as prog_code"),
            DB::raw('coalesce(b.title, j.title, d.name) as title'),
            DB::raw("(select trim(a.name||' '||a.surname) from lib_book_authors a
                            where (a.book_id = i.book_id or a.j_issue_id = i.j_issue_id or a.disc_id = i.disc_id)
                            and a.is_main = 1) as author"),
            DB::raw('coalesce(b.isbn, j.isbn, d.isbn) as isbn'),
            DB::raw('coalesce(b.pub_year,j.pub_year, d.pub_year) as pub_year'),
            DB::raw('coalesce(b.pub_city, j.pub_city, d.pub_city) as pub_city'),
            DB::raw('coalesce(b.type, j.type, d.type) as item_key'),
            DB::raw("(select mt.title_$lang from lib_material_types mt
                            where mt.key = coalesce(b.type, j.type, d.type)) as item_type"),
            DB::raw('coalesce(b.publisher_id, j.publisher_id, d.publisher_id) as publisher_id'),
            DB::raw('(select p.name from lib_publishers p
                            where p.publisher_id = coalesce(b.publisher_id, j.publisher_id, d.publisher_id)) as publisher'),
            DB::raw('(select s.supplier_name from lib_suppliers s
                            where s.supplier_id = h.supplier_id) as supplier'),
            DB::raw("(select st.title_$lang from lib_supply_types st where st.key = h.supply_type) as supply_type"),
            DB::raw("(select e.name||' '||e.sname from dbmaster.employee e
                            where e.emp_id = (select u.emp_id from lib_user_cards u where u.user_cid = i.user_cid)) as created_by"),
            DB::raw("(select e.name||' '||e.sname from dbmaster.employee e
                            where e.emp_id = (select u.emp_id from lib_user_cards u where u.user_cid = i.edited_by)) as edited_by"),
            'i.price as cost',
            'i.currency',
            DB::raw("to_char(i.receive_date, 'YYYY-MM-DD') as create_date"),
            DB::raw("(select si.key||' - '||si.title_$lang from sigle_types si where si.key = i.sigle_type) as location_title"),
            'i.sigle_type as location',
            'i.user_cid',
            DB::raw("decode(i.tag_printed, 1, 'printed', 'not printed') as print_status"),
            DB::raw("decode(i.tag_initialized, 1, 'initialized', 'not initialized') as init_status")
        )
            ->leftJoin('lib_books as b', 'b.book_id', '=', 'i.book_id')
            ->leftJoin('lib_journal_issues as ji', 'ji.j_issue_id', '=', 'i.j_issue_id')
            ->leftJoin('lib_journals as j', 'j.journal_id', '=', 'ji.journal_id')
            ->leftJoin('lib_discs as d', 'd.disc_id', '=', 'i.disc_id')
            ->leftJoinSub(
                DB::table('lib_hesab_mats as t')
                    ->select([
                        't.hesab_id',
                        't.book_id',
                        't.j_issue_id',
                        't.disc_id',
                    ])
                    ->groupBy(['t.hesab_id', 't.book_id', 't.j_issue_id', 't.disc_id']),
                'hm',
                function ($join) {
                    $join->on(function ($nestedJoin) {
                        $nestedJoin->on('hm.book_id', '=', 'i.book_id');
                        $nestedJoin->orOn('hm.j_issue_id', '=', 'i.j_issue_id');
                        $nestedJoin->orOn('hm.disc_id', '=', 'i.disc_id');
                    });
                    $join->on('hm.hesab_id', '=', 'i.hesab_id');
                }
            )
            ->leftJoin('lib_hesablar as h', 'h.hesab_id', '=', 'hm.hesab_id');
    }
}
