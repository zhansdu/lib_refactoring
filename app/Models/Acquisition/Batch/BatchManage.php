<?php


namespace App\Models\Acquisition\Batch;


use App\Common\Helpers\Query\ManageBuilder;
use Illuminate\Support\Facades\DB;

trait BatchManage
{
    public static function create(array $attributes): object
    {
        DB::beginTransaction();

        $data = (new static)->fill($attributes);
        $data->save();

        DB::commit();
        return $data;
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        return ManageBuilder::updateBuilder(self::class, $this->getKeyName(), $this->hesab_id, $attributes, $this->fillable);
    }

    public function delete(): ?bool
    {
        return ManageBuilder::deleteBuilder(self::class, $this->getKeyName(), $this->hesab_id);
    }
}
