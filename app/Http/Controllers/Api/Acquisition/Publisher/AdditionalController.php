<?php

namespace App\Http\Controllers\Api\Acquisition\Publisher;

use App\Http\Controllers\Controller;
use App\Models\Acquisition\Publisher\Publisher;
use Illuminate\Http\JsonResponse;

class AdditionalController extends Controller
{
    public function names(): JsonResponse
    {
        $data = Publisher::names()->get()->toArray();
        return response()->json(['res' => $data]);
    }
}
