<?php


namespace App\Common\Helpers\Manage;


use App\Exceptions\ReturnResponseException;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;

class Update
{
    #[ArrayShape(['message' => "mixed"])] public static function update(Model $model, mixed $id, array $updateInputs): array
    {
        $className = (new ReflectionClass($model))->getShortName();
        $data = $model::find($id);
        if (!$data->update($updateInputs)) {
            throw new ReturnResponseException(__('general.update_failed', ['name' => $className]), 409);
        }

        return ['message' => __('general.update_success', ['name' => $className])];
    }
}
