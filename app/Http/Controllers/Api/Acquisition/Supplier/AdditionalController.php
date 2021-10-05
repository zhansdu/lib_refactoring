<?php

namespace App\Http\Controllers\Api\Acquisition\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Acquisition\Supplier\Supplier;
use Illuminate\Http\JsonResponse;

class AdditionalController extends Controller
{
    public function names(): JsonResponse
    {
        $data = Supplier::names()->get()->toArray();
        return response()->json(['res' => $data]);
    }

    public function types(): JsonResponse
    {
        $data = Supplier::types()->get()->toArray();
        return response()->json(['res' => $data]);
    }
}
