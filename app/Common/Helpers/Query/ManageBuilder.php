<?php


namespace App\Common\Helpers\Query;


class ManageBuilder
{
    public static function updateBuilder($class, string $keyName, int $id, array $attributes, array $fillable)
    {
        return $class::where($keyName, $id)->update(array_intersect_key($attributes, array_flip($fillable)));
    }

    public static function deleteBuilder($class, string $keyName, int $id)
    {
        return $class::where($keyName, $id)->delete();
    }
}
