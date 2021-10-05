<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class Student extends Authenticatable implements UserCidAttribute
{
    use HasApiTokens, HasFactory;

    public $incrementing = false;
    protected $table = 'dbmaster.students';
    protected $primaryKey = 'stud_id';
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'surname',
        'class',
        'status',
        'passw',
        'web_lan',
        'last_psw_set_date',
        'emp_id',
        'type',
        'name_native',
        'surname_native',
        'old_sdu_id',
        'patronymic',
        'prog_code_reg',
        'prog_year_reg',
        'last_login_info',
        'attempt_count',
        'attempt_date',
        'edu_level',
        'entrance_year',
        'study_count'
    ];

    protected $hidden = [
        'passw',
        'web_lan',
        'last_psw_set_date',
        'emp_id',
        'name_native',
        'surname_native',
        'old_sdu_id',
        'patronymic',
        'prog_code_reg',
        'prog_year_reg',
        'last_login_info',
        'attempt_count',
        'attempt_date',
        'entrance_year',
        'study_count'
    ];

    protected $appends = ['user_cid', 'username', 'id', 'type'];

    public function getUserCidAttribute()
    {
        return DB::table('lib_user_cards as uc')->select('uc.user_cid')->where('uc.stud_id', $this->stud_id)->first()->user_cid;
    }

    public function getUsernameAttribute()
    {
        return $this->stud_id;
    }

    public function getIdAttribute()
    {
        return $this->stud_id;
    }

    public static function defaultQuery(): Builder
    {
        return DB::table('dbmaster.students as t')
            ->select('t.stud_id as id', 't.stud_id as username', DB::raw("t.name||' '||t.surname as full_name"),
                DB::raw("(select d.title_en from dbmaster.departments d where d.dep_code = dp.dep_code_f and d.son = 1) as faculty"),
                DB::raw("(select el.title_en from dbmaster.edu_levels el
                                            where el.level_code = t.edu_level) as degree"),
                DB::raw("'student' as type"))
            ->leftJoin('dbmaster.stud_prog as sp', static function ($join) {
                $join->on('sp.stud_id', '=', 't.stud_id');
                $join->on('sp.prog_type', '=', DB::raw("'M'"));
            })->leftJoin('dbmaster.dep_programs as dp', static function ($join) {
                $join->on('dp.prog_code', '=', 'sp.prog_code');
                $join->on('dp.son', '=', 1);
            });
    }

    public static function fullInfo(int $id): Builder
    {
        return DB::table('dbmaster.students as t')
            ->select(DB::raw('(select uc.user_cid from lib_user_cards uc where uc.stud_id = t.stud_id) as user_cid'),
                't.stud_id as id', DB::raw("t.name||' '||t.surname as full_name"), 't.stud_id as username',
                DB::raw("(select el.title_en from dbmaster.edu_levels el
                                            where el.level_code = t.edu_level) as degree"),
                DB::raw("(select ss.title_en from dbmaster.stud_status ss
                                            where ss.status_id = t.status) as status"),
                't.class', DB::raw("dp.prog_code||' - '||dp.title_en as program"),
                DB::raw("(select d.title_en from dbmaster.departments d
                                            where d.dep_code = dp.dep_code_f and d.son = 1) as faculty"),
                DB::raw("(select wm_concat(c.contact) from dbmaster.contacts c
                                            where c.stud_id = t.stud_id and c.type_id = 5
                                            and (c.owner_id is NULL or c.owner_id = 0)) as email"),
                DB::raw("(select wm_concat(c.contact) from dbmaster.contacts c
                                            where c.stud_id = t.stud_id and c.type_id = 1
                                             and (c.owner_id is NULL or c.owner_id = 0)) as mobile"),
                DB::raw("(select dbmaster.getaddress(ua.address_id)||nvl2(ua.address_line, ', '||ua.address_line, '')
                                            from dbmaster.user_address ua where ua.stud_id = t.stud_id
                                            and ua.owner_type = 0 and ua.address_type = 3) as address"))
            ->leftJoin('dbmaster.stud_prog as sp', static function ($join) {
                $join->on('sp.stud_id', '=', 't.stud_id');
                $join->on('sp.prog_type', '=', DB::raw("'M'"));
            })->leftJoin('dbmaster.dep_programs as dp', static function ($join) {
                $join->on('dp.prog_code', '=', 'sp.prog_code');
                $join->on('dp.son', '=', 1);
            })->where('t.stud_id', '=', $id);
    }

    /**
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return 'student';
    }
}
