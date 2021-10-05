<?php


namespace App\Common\Helpers\Manage;


use App\Exceptions\ReturnResponseException;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;

class Create
{
    #[ArrayShape(['message' => "mixed", 'id' => "mixed"])] public static function create(Model $model, array $createInputs, $isFill = true): array
    {
        if ($isFill) {
            $data = $model->fill($createInputs);
            $data->save();
        } else {
            $data = $model::create($createInputs);
        }

        $className = (new ReflectionClass($model))->getShortName();

        if (empty($data) && empty($data->getKey())) {
            throw new ReturnResponseException(__('general.create_failed', ['name' => $className]), 409);
        }

        if ($isFill) {
            $keyName = $data->getKeyName();

            $id = $model::select($keyName)->orderBy($keyName, 'desc')->first()->toArray()[explode('.', $keyName)[1]];
        } else {
            $id = $data->getKey();
        }

        return ['message' => __('general.create_success', ['name' => $className]), 'id' => $id];
    }
}
