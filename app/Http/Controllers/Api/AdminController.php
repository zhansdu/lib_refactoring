<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

final class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeAdminConfigurationFile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required',
        ], $request->all());

        $content = json_encode($validated['content']);

        File::put(public_path('admin_configuration.json'), $content);

        return response()->json([
            'res' => 'success',
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getAdminConfigurationFile(): JsonResponse
    {
        $file = File::get(public_path('admin_configuration.json'));

        return response()->json([
            'res' => [
                'content' => json_decode($file),
            ],
        ]);
    }
}
