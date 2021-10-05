<?php

namespace App\Http\Controllers\Api\Acquisition\Supplier;

use App\Common\Helpers\Manage\Create;
use App\Common\Helpers\Manage\Delete;
use App\Common\Helpers\Manage\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acquisition\Supplier\CreateRequest;
use App\Http\Requests\Acquisition\Supplier\UpdateRequest;
use App\Models\Acquisition\Supplier\Supplier;
use Illuminate\Http\JsonResponse;

class ManageController extends Controller
{
    public function create(CreateRequest $request): JsonResponse
    {
        $response = Create::create(new Supplier(), self::createInputs($request->validated()));
        return response()->json([
            'res' => $response
        ], 201);
    }

    static function createInputs(array $validated): array
    {
        return [
            'supplier_name' => $validated['sup_name'],
            'bin/inn' => $validated['bin'] ?? '',
            'commercial_name' => $validated['com_name'] ?? '',
            'address' => $validated['address'] ?? '',
            'email' => $validated['email'] ?? '',
            'fax' => $validated['fax'] ?? '',
            'phone' => $validated['phone'] ?? '',
        ];
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $response = Update::update(new Supplier(), $validated['sup_id'], self::updateInputs($validated));
        return response()->json([
            'res' => $response
        ]);
    }

    static function updateInputs(array $validated): array
    {
        return self::createInputs($validated);
    }

    public function delete(int $id): JsonResponse
    {
        $response = Delete::delete(new Supplier(), $id);
        return response()->json([
            'res' => $response
        ]);
    }
}
