<?php

namespace App\Models\Acquisition\Supplier;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model implements DefaultQueryInterface
{
    use SupplierManage;
    use SupplierAdditional;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'lib_suppliers as s';
    protected $primaryKey = 's.supplier_id';
    protected $fillable = ['supplier_name', 'bin/inn', 'commercial_name', 'address'];

    public static function defaultQuery(): Builder
    {
        return static::query()->select('s.supplier_id as id', 's.supplier_name as name', 's.commercial_name as com_name',
            's.bin/inn as bin', 's.address',
            DB::raw("(select listagg(c.contact, ', ') from lib_contacts c where c.supplier = s.supplier_id and c.type_id = 4) as email"),
            DB::raw("(select listagg(c.contact, ', ') from lib_contacts c where c.supplier = s.supplier_id and c.type_id = 1) as phone"),
            DB::raw("(select listagg(c.contact, ', ') from lib_contacts c where c.supplier = s.supplier_id and c.type_id = 5) as fax"));
    }
}
