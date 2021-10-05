<?php

namespace App\Models\Media;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use App\Models\Acquisition\Item\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Journal extends Model implements DefaultQueryInterface
{
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'lib_journals as j';
    protected $primaryKey = 'j.journal_id';
    protected $fillable = [
        'journal_id',
        'isbn',
        'title',
        'publisher_id',
        'publish_interval_id',
        'borc_verme',
        'diagonal',
        'pub_year',
        'pub_city',
        'editor',
        'page_num',
        'seriya',
        'old_id',
        'parallel_title',
        'title_related_info',
        'pub_info',
        'issue_number',
        'issue_date',
        'temp_publisher_title',
        'language',
        'type',
        'issn',
        'volume',
        'prog_code',
    ];

    public static function defaultQuery(): Builder
    {
        return static::query()->select(
            'j.journal_id as id', 'j.title as title', 'j.state',
            'j.pub_year as year', 'j.language as language', 'j.callnumber as call_number', 'j.pub_city as city',
            DB::raw("(select lm.title_en from lib_material_types lm where lm.key = j.type) as type"),
            'j.type as type_key', 'j.isbn', 'j.issn', 'j.volume', 'j.prog_code',
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.j_issue_id = ji.j_issue_id group by a.j_issue_id) as author"),
            DB::raw("(select lp.name from lib_publishers lp where lp.publisher_id = j.publisher_id) as publisher"),
            DB::raw("(select r.status from lib_reserve_list r where ji.j_issue_id = r.j_issue_id) as status"),
            'av.available',
            'av.total'
        )
            ->leftJoin('lib_journal_issues as ji', 'j.journal_id', '=', 'ji.journal_id')
            ->leftJoin('lib_publishers as p', 'p.publisher_id', '=', 'j.publisher_id')
            ->leftJoinSub(
                Item::query()->select(
                    'i.j_issue_id',
                    DB::raw("sum(nvl((select 0 from lib_loans l where l.inv_id = i.inv_id and l.locked = 0), 1)) as available"),
                    DB::raw("count(*) as total")
                )
                    ->groupBy('i.j_issue_id')
                    ->getQuery(),
                'av',
                'av.j_issue_id',
                '=',
                'ji.j_issue_id'
            );
    }

    public static function withAdditionalAttributes(Builder $builder): Builder
    {
        return $builder->addSelect(
            'j.title_related_info',
            'j.page_num as page_number',
            'j.parallel_title',
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.j_issue_id = ji.j_issue_id and a.is_main = 1 group by a.j_issue_id) as main_author"),
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.j_issue_id = ji.j_issue_id and a.is_main = 0 group by a.j_issue_id) as other_author")
        );
    }
}
