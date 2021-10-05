<?php


namespace App\Models\Acquisition\Publisher;


use App\Common\Helpers\Query\ManageBuilder;
use App\Exceptions\ReturnResponseException;
use Illuminate\Support\Facades\DB;

trait PublisherManage
{
    public static function create(array $attributes): object
    {
        DB::beginTransaction();

        $data = (new static)->fill($attributes);
        $data->save();

        $contacts = [];

        if (!empty($attributes['email'])) {
            $contacts[] = ['contact' => trim($attributes['email']), 'type_id' => 4, 'publisher' => $data->getKey()];
        }

        if (!empty($attributes['phone'])) {
            $contacts[] = ['contact' => trim($attributes['phone']), 'type_id' => 1, 'publisher' => $data->getKey()];
        }

        if (!empty($attributes['fax'])) {
            $contacts[] = ['contact' => trim($attributes['fax']), 'type_id' => 5, 'publisher' => $data->getKey()];
        }

        if (!DB::table('lib_contacts')->insert($contacts)) {
            DB::rollBack();
            throw new ReturnResponseException(__('general.contacts_create_failed', ['name' => 'publisher']), 409);
        }

        DB::commit();
        return $data;
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        DB::beginTransaction();
        $id = $this->publisher_id;

        if (!ManageBuilder::updateBuilder(static::class, $this->getKeyName(), $id, $attributes, $this->fillable)) {
            DB::rollBack();
            return false;
        }

        if (!empty($attributes['email'])) {
            $updateEmail = DB::table('lib_contacts')
                ->where('publisher', '=', $id)->where('type_id', '=', 4)
                ->updateOrInsert(['publisher' => $id, 'type_id' => 4,],
                    ['contact' => trim($attributes['email'])]);
            if (!$updateEmail) {
                DB::rollBack();
                throw new ReturnResponseException(__('general.contacts_update_failed', ['name' => 'publisher']), 409);
            }
        }

        if (!empty($attributes['phone'])) {
            $updatePhone = DB::table('lib_contacts')
                ->where('publisher', '=', $id)->where('type_id', '=', 1)
                ->updateOrInsert(['publisher' => $id, 'type_id' => 1,],
                    ['contact' => trim($attributes['phone'])]);
            if (!$updatePhone) {
                DB::rollBack();
                throw new ReturnResponseException(__('general.contacts_update_failed', ['name' => 'publisher']), 409);
            }
        }

        if (!empty($attributes['fax'])) {
            $updateFax = DB::table('lib_contacts')
                ->updateOrInsert(['publisher' => $id, 'type_id' => 5,],
                    ['contact' => trim($attributes['fax'])]);
            if (!$updateFax) {
                DB::rollBack();
                throw new ReturnResponseException(__('general.contacts_update_failed', ['name' => 'publisher']), 409);
            }
        }

        DB::commit();
        return true;
    }

    public function delete(): ?bool
    {
        DB::beginTransaction();

        $contacts = DB::table('lib_contacts')->where('publisher', '=', $this->publisher_id);
        if (!empty($contacts->get()->toArray()) && !$contacts->delete()) {
            DB::rollBack();
            throw new ReturnResponseException(__('general.contacts_delete_failed', ['name' => 'publisher']), 409);
        }

        $result = ManageBuilder::deleteBuilder(static::class, $this->getKeyName(), $this->publisher_id);
        DB::commit();

        return $result;
    }
}
