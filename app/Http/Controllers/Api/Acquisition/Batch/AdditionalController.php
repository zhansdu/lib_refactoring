<?php

namespace App\Http\Controllers\Api\Acquisition\Batch;

use App\Http\Controllers\Controller;
use App\Models\Acquisition\Batch\Batch;
use Illuminate\Http\JsonResponse;

class AdditionalController extends Controller
{
    public function status($id): JsonResponse
    {
        $status = Batch::status($id)->first();
        return response()->json(['res' => $status]);
    }

    public function statuses(): JsonResponse
    {
        $statuses = Batch::statuses()->get()->toArray();
        return response()->json(['res' => $statuses]);
    }

    public function numbers(): JsonResponse
    {
        $batches = Batch::numbers()->get()->toArray();
        return response()->json(['res' => $batches]);
    }
}
