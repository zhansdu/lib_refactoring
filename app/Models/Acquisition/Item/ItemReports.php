<?php


namespace App\Models\Acquisition\Item;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait ItemReports
{
    public static function inventoryBooks(): Builder
    {
        return static::query()->select(DB::raw("TO_CHAR(i.receive_date, 'YYYY-MM-DD') as create_date"),
            'h.hesab_id as batch_id', 'bii.inventory_no', 'i.inv_id as id',
            DB::raw("(select decode(ba.name, null, '', ba.name || ' ')||decode(ba.surname, null, '', ba.surname||', ')||b.title
                                    from lib_books b left join lib_book_authors ba on b.book_id = ba.book_id and ba.is_main = 1
                                    where b.book_id = i.book_id) as author_title"),
            DB::raw("(select b.pub_city||', '||b.pub_year from lib_books b where b.book_id = i.book_id) as year_city"),
            DB::raw("(select listagg(xt.extract('//Cell[7]/text()').getStringVal(), ' ')
                                        within group (order by null)
                                from lib_bibliographic_info bi, XMLTABLE('//Nodes/Node' PASSING bi.XML_DATA) xt
                                where bi.book_id = i.book_id and xt.extract('//Cell[1]/text()').getStringVal() in ('010.a')) as call_number"),
            'i.price as cost', 'i.currency', 'i.barcode', 'h.doc_no')
            ->leftJoin('lib_hesab_mats as hm', 'i.book_id', '=', 'hm.book_id')
            ->leftJoin('lib_hesablar as h', 'hm.hesab_id', '=', 'h.hesab_id')
            ->join('inventory_book as bii', 'i.inv_id', '=', 'bii.inv_id')
            ->whereNotNull('i.book_id')
            ->orderBy('bii.inventory_no');
    }

    public static function bookHistory(): Builder
    {
        return static::query()->select('i.barcode', 'i.inv_id as id',
            DB::raw("(case when i.book_id is not null
                                    then (select mt.title_" . app()->getLocale() . " from lib_material_types mt,
                                            lib_books b where b.type = mt.key and b.book_id = i.book_id)
                                        when i.disc_id is not null
                                    then (select mt.title_" . app()->getLocale() . " from lib_material_types mt,
                                            lib_discs d where d.type = mt.key and d.disc_id = i.disc_id)
                                        when i.j_issue_id is not null
                                    then (select mt.title_" . app()->getLocale() . " from lib_material_types mt,
                                            lib_journals j, lib_journal_issues ji where j.type = mt.key
                                            and j.journal_id = ji.journal_id and ji.j_issue_id = i.j_issue_id) end) as type"),
            DB::raw("(case when i.book_id is not null
                                    then (select b.title from lib_books b where i.book_id = b.book_id)
                                        when i.disc_id is not null
                                    then (select d.name from lib_discs d where i.disc_id = d.disc_id)
                                        when i.j_issue_id is not null
                                    then (select j.title from lib_journals j, lib_journal_issues ji
                                            where j.journal_id = ji.journal_id and ji.j_issue_id = i.j_issue_id) end) as title"),
            DB::raw("(case when i.book_id is not null
                            then (select listagg(ba.name||ba.surname, ', ') within group(order by ba.name)
                                from lib_book_authors ba where ba.book_id = i.book_id group by i.book_id)
                            when i.j_issue_id is not null
                            then (select listagg(ba.name||ba.surname, ', ') within group(order by ba.name)
                                from lib_book_authors ba where ba.j_issue_id = i.j_issue_id)
                            when i.disc_id is not null
                            then (select listagg(ba.name||ba.surname, ', ') within group(order by ba.name)
                                from lib_book_authors ba where ba.disc_id = i.disc_id) end) as author"),
            'l.borrow_date', 'l.due_date', 'l.delivery_date',
            DB::raw("(case when l.delivery_date is not null and i.status = 1 then 'returned'
                            else (case
                                when current_date <= l.due_date then 'issued'
                                when current_date > l.due_date then 'overdue'
                                end)
                                 end) as status"),
            DB::raw("(select (case when u.stud_id is not null
                                        then (select t.name||' '||t.surname from dbmaster.students t where u.stud_id = t.stud_id)
                                        when u.emp_id is not null
                                        then (select e.name||' '||e.sname from dbmaster.employee e where e.emp_id = u.emp_id) end)
                                        from lib_user_cards u where u.user_cid = l.user_cid) as username"))
            ->leftJoin('lib_loans as l', 'i.inv_id', '=', 'l.inv_id');
    }

    public static function barcodeQuery(): Builder
    {
        return static::query()->select('i.barcode', 'i.inv_id as id',
            DB::raw("(case when i.book_id is not null
                                then (select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.book_id = i.book_id group by a.book_id)
                                when i.j_issue_id is not null
                                then (select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.j_issue_id = i.j_issue_id group by a.j_issue_id)
                                when i.disc_id is not null
                                then (select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.disc_id = i.disc_id group by a.disc_id)
                                         end) as author"),
            DB::raw("(case when i.book_id is not null
                                then (select b.title from lib_books b where b.book_id = i.book_id)
                                when i.j_issue_id is not null
                                then (select j.title from lib_journals j
                                        left join lib_journal_issues ji on j.journal_id = ji.journal_id
                                        where ji.j_issue_id = i.j_issue_id)
                                when i.disc_id is not null
                                then (select d.name from lib_discs d where d.disc_id = i.disc_id) end) as title"),
            DB::raw("(case when i.tag_printed = 1 then 'printed'
                                       else 'not printed' end) as print_status"),
            DB::raw("(case when i.tag_initialized = 1 then 'initialized'
                                       else 'not initialized' end) as init_status"));
    }
}
