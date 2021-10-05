<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WebLog extends Model
{
    protected $table = 'web_log as wl';
    protected $primaryKey = 'wl.log_id';
    public $incrementing = false;

    protected $fillable = [
        'log_id', 'user_id', 'login_status', 'log_date', 'user_ip', 'device_info'
    ];

    public static function byWeek(): Builder
    {
        return static::query()->select(DB::raw("trim(to_char(wl.log_date, 'day')) as name"))
            ->leftJoin('lib_user_cards as uc', 'wl.user_id', '=', 'uc.user_cid')
            ->groupBy(DB::raw("to_char(wl.log_date, 'day')"));
    }

    public static function byMonths(): Builder
    {
        return static::query()->select(DB::raw("trim(to_char(wl.log_date, 'month')) as name"))
            ->leftJoin('lib_user_cards as uc', 'wl.user_id', '=', 'uc.user_cid')
            ->groupBy(DB::raw("to_char(wl.log_date, 'month')"));
    }

    public static function whereByWeek(Builder $builder): Builder
    {
        return $builder->whereBetween('wl.log_date',
            [DB::raw("to_date((next_day(sysdate, 'MON'))-7)"),
                DB::raw("to_date((next_day(sysdate, 'MON')-1))")]);
    }

    public static function whereByMonth(Builder $builder): Builder
    {
        return $builder->where(DB::raw("to_char(wl.log_date, 'YYYY')"), DB::raw("to_char(sysdate, 'YYYY')"));
    }

    public static function total(): Builder
    {
        return static::query()->select(
            DB::raw("count(uc.emp_id) as employees"), DB::raw("count(uc.stud_id) as students")
        )->leftJoin('lib_user_cards as uc', 'wl.user_id', '=', 'uc.user_cid');
    }
}
