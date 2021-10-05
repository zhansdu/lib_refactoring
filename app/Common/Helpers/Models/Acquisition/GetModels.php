<?php


namespace App\Common\Helpers\Models\Acquisition;


use App\Common\Interfaces\Models\GetModelsInterface;
use App\Models\Acquisition\Batch\Batch;
use App\Models\Acquisition\Item\Item;
use App\Models\Acquisition\Publisher\Publisher;
use App\Models\Acquisition\Supplier\Supplier;
use JetBrains\PhpStorm\Pure;

class GetModels implements GetModelsInterface
{
    #[Pure] public static function getModels(): array
    {
        return [new Batch(), new Item(), new Publisher(), new Supplier()];
    }
}
