<?php


namespace App\Common\Helpers\Manage;


use App\Exceptions\ReturnResponseException;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;

class Delete
{
    #[ArrayShape(['message' => "mixed"])] public static function delete(Model $model, mixed $id): array
    {
        $className = (new ReflectionClass($model))->getShortName();

        $data = $model::find($id);
        if (!$data->delete()) {
            throw new ReturnResponseException(__('general.delete_failed', ['name' => $className]), 409);
        }

        return ['message' => __('general.delete_success', ['name' => $className])];
    }
}
