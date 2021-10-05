<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class Employee extends Authenticatable implements UserCidAttribute
{
    use HasApiTokens, HasFactory;

    public $incrementing = false;
    protected $table = 'dbmaster.employee';
    protected $primaryKey = 'emp_id';
    protected $fillable = [
        'name',
        'degree_id',
        'ip',
        'status',
        'passw',
        'passw2',
        'state',
        'web_lan',
        'pswchdate',
        'reg_date',
        'name_native',
        'surname_native',
        'patronymic',
        'old_emp_id',
        'wage_rate',
        'academic_rank',
        'last_login_info',
        'attempt_count',
        'attempt_date',
    ];

    protected $hidden = [
        'emp_id',
        'sname',
        'hname',
        'degree_id',
        'ip',
        'passw',
        'passw2',
        'state',
        'web_lan',
        'pswchdate',
        'reg_date',
        'name_native',
        'surname_native',
        'patronymic',
        'old_emp_id',
        'wage_rate',
        'academic_rank',
        'last_login_info',
        'attempt_count',
        'attempt_date',
    ];

    protected $appends = ['is_admin', 'user_cid', 'surname', 'id', 'username', 'type'];

    public function getUserCidAttribute()
    {
        return DB::table('lib_user_cards as uc')->select('uc.user_cid')->where('uc.emp_id', $this->emp_id)->first()->user_cid;
    }

    public function getIsAdminAttribute(): bool
    {
        $ids = [10931, 10896, 11025, 11026, 11050, 11067, 11053, 11094, 11111];

        if (in_array($this->emp_id, $ids)) {
            return true;
        }

        $permission = static::query()->select(DB::raw("dbmaster.GetPermitByUser(emp_id, ai.acc_id) as user_permit"),
            DB::raw("dbmaster.getpermitByUserJob(emp_id, ai.acc_id) as position_permit"),
            'ai.acc_level')
            ->leftJoin('dbmaster.acc_info as ai', 'ai.acc_id', '=', 7)
            ->where('emp_id', '=', $this->emp_id)->first();
        if (!empty($permission)) {
            $permitted = $permission->acc_level == "2" || $permission->user_permit == "1"
                || $permission->position_permit == "1";
        }

        return $permitted ?? false;
    }

    public function getIdAttribute()
    {
        return $this->emp_id;
    }

    public function getSurnameAttribute()
    {
        return $this->sname;
    }

    public function getUsernameAttribute()
    {
        return $this->hname;
    }

    public static function defaultQuery(): Builder
    {
        return DB::table('dbmaster.employee as e')->select('e.emp_id as id',
            DB::raw("e.name||' '||e.sname as full_name"),
            'e.hname as username', DB::raw("(select dr.degree_title_en||'/'||g.gorev_ad_en from dbmaster.degree dr,
                                            dbmaster.gorev g where dr.degree_id = e.degree_id and g.gorev_id = dg.gorev_id) as degree_position"),
            DB::raw("(select d.title_en from dbmaster.departments d where d.dep_code = dg.dep_code and d.son = 1) as department"),
            DB::raw("'employee' as type"))
            ->leftJoin('dbmaster.emp_gorev as eg', static function ($join) {
                $join->on('eg.emp_id', '=', 'e.emp_id');
                $join->on('eg.esas_gorev', '=', 1);
                $join->on('eg.status', '=', 1);
            })
            ->leftJoin('dbmaster.dep_gorev as dg', 'dg.dep_gorev_id', '=', 'eg.dep_gorev_id');
    }

    public static function fullInfo(int $id): Builder
    {
        return DB::table('dbmaster.employee as e')
            ->select('e.emp_id as id', 'e.hname as username', DB::raw("e.name||' '||e.sname as full_name"),
                        DB::raw('(select uc.user_cid from lib_user_cards uc where uc.emp_id = e.emp_id) as user_cid'),
                        DB::raw("(select dr.degree_title_en from dbmaster.degree dr
                                            where dr.degree_id = e.degree_id) as degree"),
                        DB::raw("(select s.title_en from dbmaster.state s where s.state_id = e.state) as status"),
                        DB::raw("(select g.gorev_ad_en from dbmaster.gorev g where g.gorev_id = dg.gorev_id) as position"),
                        DB::raw("(select d.title_en from dbmaster.departments d where d.dep_code = dg.dep_code and d.son = 1) as department"),
                        DB::raw("(select wm_concat(c.contact) from dbmaster.contacts c where c.emp_id = e.emp_id
                                            and c.type_id = 5 and (c.owner_id is NULL or c.owner_id = 0)) as email"),
                        DB::raw("(select wm_concat(c.contact) from dbmaster.contacts c where c.emp_id = e.emp_id
                                            and c.type_id = 1 and (c.owner_id is NULL or c.owner_id = 0)) as mobile"),
                        DB::raw("(select dbmaster.getaddress(ua.address_id)||nvl2(ua.address_line, ', '||ua.address_line, '')
                            from dbmaster.user_address ua where ua.emp_id = e.emp_id and ua.owner_type = 0 and ua.address_type = 3) as address"))
                    ->leftJoin('dbmaster.emp_gorev as eg', static function ($join) {
                        $join->on('eg.emp_id', '=', 'e.emp_id');
                        $join->on('eg.esas_gorev', '=', 1);
                        $join->on('eg.status', '=', 1);
                    })->leftJoin('dbmaster.dep_gorev as dg', 'dg.dep_gorev_id', '=', 'eg.dep_gorev_id')
                    ->where('e.emp_id', '=', $id);
    }

    /**
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return 'employee';
    }
}
