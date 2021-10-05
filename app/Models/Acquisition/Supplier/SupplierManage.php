<?php


namespace App\Models\Acquisition\Supplier;


use App\Common\Helpers\Query\ManageBuilder;
use App\Exceptions\ReturnResponseException;
use Illuminate\Support\Facades\DB;

trait SupplierManage
{
    public static function create(array $attributes): object
    {
        DB::beginTransaction();

        $data = (new static)->fill($attributes);
        $data->save();

        $contacts = [];

        if (!empty($attributes['email'])) {
            $contacts[] = ['contact' => trim($attributes['email']), 'type_id' => 4, 'supplier' => $data->getKey()];
        }

        if (!empty($attributes['phone'])) {
            $contacts[] = ['contact' => trim($attributes['phone']), 'type_id' => 1, 'supplier' => $data->getKey()];
        }

        if (!empty($attributes['fax'])) {
            $contacts[] = ['contact' => trim($attributes['fax']), 'type_id' => 5, 'supplier' => $data->getKey()];
        }

        if (!DB::table('lib_contacts')->insert($contacts)) {
            DB::rollBack();
            throw new ReturnResponseException(__('general.contacts_create_failed', ['name' => 'supplier']), 409);
        }

        DB::commit();
        return $data;
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        DB::beginTransaction();
        $id = $this->supplier_id;

        if (!ManageBuilder::updateBuilder(static::class, $this->getKeyName(), $id, $attributes, $this->fillable)) {
            return false;
        }


        if (!empty($attributes['email'])) {
            $updateEmail = DB::table('lib_contacts')
                ->where('supplier', '=', $id)->where('type_id', '=', 4)
                ->updateOrInsert(['supplier' => $id, 'type_id' => 4,],
                    ['contact' => trim($attributes['email'])]);
            if (!$updateEmail) {
                DB::rollBack();
                throw new ReturnResponseException(__('general.contacts_update_failed', ['name' => 'supplier']), 409);
            }
        }

        if (!empty($attributes['phone'])) {
            $updatePhone = DB::table('lib_contacts')
                ->where('supplier', '=', $id)->where('type_id', '=', 1)
                ->updateOrInsert(['supplier' => $id, 'type_id' => 1,],
                    ['contact' => trim($attributes['phone'])]);
            if (!$updatePhone) {
                DB::rollBack();
                throw new ReturnResponseException(__('general.contacts_update_failed', ['name' => 'supplier']), 409);
            }
        }

        if (!empty($attributes['fax'])) {
            $updateFax = DB::table('lib_contacts')
                ->updateOrInsert(['supplier' => $id, 'type_id' => 5,],
                    ['contact' => trim($attributes['fax'])]);
            if (!$updateFax) {
                DB::rollBack();
                throw new ReturnResponseException(__('general.contacts_update_failed', ['name' => 'supplier']), 409);
            }
        }
        DB::commit();
        return true;
    }

    public function delete(): ?bool
    {
        DB::beginTransaction();

        $contacts = DB::table('lib_contacts')->where('supplier', '=', $this->supplier_id);
        if (!empty($contacts->get()->toArray()) && !$contacts->delete()) {
            DB::rollBack();
            throw new ReturnResponseException(__('general.contacts_delete_failed', ['name' => 'supplier']), 409);
        }

        $result = ManageBuilder::deleteBuilder(static::class, $this->getKeyName(), $this->supplier_id);
        DB::commit();

        return $result;
    }
}
