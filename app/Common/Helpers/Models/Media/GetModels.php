<?php


namespace App\Common\Helpers\Models\Media;


use App\Common\Interfaces\Models\GetModelsInterface;
use App\Models\Media\Book;
use App\Models\Media\Disc;
use App\Models\Media\Journal;

class GetModels implements GetModelsInterface
{
    public static function getModels(): array
    {
        return [new Book(), new Disc(), new Journal()];
    }
}
