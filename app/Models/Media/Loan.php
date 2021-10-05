<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Loan extends Model
{
    protected $table = 'lib_loans as l';
    public $incrementing = false;
    protected $primaryKey = 'l.loan_id';
    protected $fillable = [
        'loan_id',
        'inv_id',
        'user_cid',
        'borrow_date',
        'due_date',
        'delivery_date',
        'locked',
        'old_id',
        'ceza',
        'ceza_odenib',
        'uzatma_sayi'
    ];


    public static function mostReadBooks(): \Illuminate\Database\Eloquent\Builder
    {
        return static::query()
            ->select(DB::raw("(case when i.book_id is not null then i.book_id
                                    when i.disc_id is not null then i.disc_id
                                    when i.j_issue_id is not null then i.j_issue_id end) as id"),
                DB::raw("(case when i.book_id is not null
                                then (select listagg(a.name||a.surname, ', ') within group(order by a.name)
                                        from lib_book_authors a where a.book_id = i.book_id)
                                when i.j_issue_id is not null
                                then (select listagg(a.name||a.surname, ', ') within group(order by a.name)
                                        from lib_book_authors a where a.j_issue_id = i.j_issue_id)
                                when i.disc_id is not null
                                then (select listagg(a.name||a.surname, ', ') within group(order by a.name)
                                        from lib_book_authors a where a.disc_id = i.disc_id)
                                         end) as author"),
                DB::raw("(case when i.book_id is not null
                                then (select b.title from lib_books b where b.book_id = i.book_id)
                                when i.j_issue_id is not null
                                then (select j.title from lib_journals j
                                        left join lib_journal_issues ji on j.journal_id = ji.journal_id
                                        where ji.j_issue_id = i.j_issue_id)
                                when i.disc_id is not null
                                then (select d.name from lib_discs d where d.disc_id = i.disc_id) end) as title"),
                DB::raw("(case when i.book_id is not null
                                then (select b.language from lib_books b where b.book_id = i.book_id)
                                when i.disc_id is not null
                                then (select d.language from lib_discs d where d.disc_id = i.disc_id)
                                when i.j_issue_id is not null
                                then (select j.language from lib_journals j
                                        left join lib_journal_issues ji on j.journal_id = ji.journal_id
                                        where ji.j_issue_id = i.j_issue_id) end) as language"),
                DB::raw("(case when i.book_id is not null
                                then (select b.isbn from lib_books b where b.book_id = i.book_id)
                                when i.j_issue_id is not null
                                then (select j.isbn from lib_journals j
                                        left join lib_journal_issues ji on j.journal_id = ji.journal_id
                                        where ji.j_issue_id = i.j_issue_id)
                                when i.disc_id is not null
                                then (select d.isbn from lib_discs d where d.disc_id = i.disc_id) end) as isbn"),
                DB::raw("count(l.loan_id) as count_issue"))
            ->leftJoin('lib_inventory as i', 'l.inv_id', '=', 'i.inv_id')
            ->groupBy('i.book_id', 'i.disc_id', 'i.j_issue_id')
            ->orderBy('count_issue', 'desc');
    }

    public static function departments(): Builder
    {
        return DB::table("dbmaster.departments")->select('dep_id as id', 'title_' . app()->getLocale() . ' as title')->orderBy('dep_id');
    }

    public static function userHistory(string $userCID): \Illuminate\Database\Eloquent\Builder
    {
        return static::query()->select('l.loan_id', 'l.inv_id', 'i.barcode',
            DB::raw("(case when i.book_id is not null
                            then (select b.title from lib_books b where b.book_id = i.book_id)
                            when i.j_issue_id is not null then (select j.title||' '||ji.issue_name
                            from lib_journals j, lib_journal_issues ji where j.journal_id = ji.journal_id
                            and ji.j_issue_id = i.j_issue_id) when i.disc_id is not null
                            then (select d.name from lib_discs d where d.disc_id = i.disc_id) end) as title"),
            DB::raw("(case when i.book_id is not null
                            then (select listagg(ba.name||ba.surname, ', ') within group(order by ba.name)
                                from lib_book_authors ba where ba.book_id = i.book_id group by i.book_id)
                            when i.j_issue_id is not null
                            then (select listagg(ba.name||ba.surname, ', ') within group(order by ba.name)
                                from lib_book_authors ba where ba.j_issue_id = i.j_issue_id)
                            when i.disc_id is not null
                            then (select listagg(ba.name||ba.surname, ', ') within group(order by ba.name)
                                from lib_book_authors ba where ba.disc_id = i.disc_id) end) as authors"),
            DB::raw("(case when i.book_id is not null
                            then (select b.pub_year from lib_books b where b.book_id = i.book_id)
                            when i.j_issue_id is not null then (select j.pub_year
                            from lib_journals j, lib_journal_issues ji where j.journal_id = ji.journal_id
                            and ji.j_issue_id = i.j_issue_id) when i.disc_id is not null
                            then (select d.pub_year from lib_discs d where d.disc_id = i.disc_id) end) as year"),
            DB::raw("coalesce(i.book_id, i.j_issue_id, i.disc_id) as media_id"),
            'l.borrow_date as issue_date', 'l.due_date', 'l.delivery_date',
            DB::raw("(case when delivery_date is not null and i.status = 1 then 'returned'
                            else (case
                                when current_date <= due_date then 'issued'
                                when current_date > due_date then 'overdue'
                                end)
                                 end) as status"))
            ->leftJoin('lib_inventory as i', 'l.inv_id', '=', 'i.inv_id')
            ->where('l.user_cid', '=', $userCID)
            ->orderBy(DB::raw("decode(status, 'overdue', 1, 'issued', 2, 'returned', 3)"))
            ->orderBy('due_date');
    }
}
