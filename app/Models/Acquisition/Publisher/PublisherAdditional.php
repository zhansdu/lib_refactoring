<?php


namespace App\Models\Acquisition\Publisher;


use Illuminate\Database\Eloquent\Builder;

trait PublisherAdditional
{
    public static function names(): Builder
    {
        return static::query()
            ->select('publisher_id as id', 'name')->orderByDesc('publisher_id');
    }
}
