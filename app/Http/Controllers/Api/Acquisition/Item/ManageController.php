<?php

namespace App\Http\Controllers\Api\Acquisition\Item;

use App\Common\Helpers\Controller\ManageItemsProcedure;
use App\Exceptions\ReturnResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Acquisition\Item\CreateRequest;
use App\Http\Requests\Acquisition\Item\RecreateRequest;
use App\Http\Requests\Acquisition\Item\UpdateRequest;
use App\Models\Media\Book;
use App\Models\Media\Disc;
use App\Models\Media\Journal;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ManageController extends Controller
{
    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function create(CreateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $check = $validated['isbn'] . ($validated['issn'] ?? '') . ($validated['volume'] ?? '');

        $count = Book::query()->where(DB::raw("isbn||issn||volume"), $check)->count();
        $count += Disc::query()->where(DB::raw("isbn||issn||volume"), $check)->count();
        $count += Journal::query()->where(DB::raw("isbn||issn||volume"), $check)->count();

        if ($count > 0) {
            throw new ReturnResponseException('ISBN, ISSN and volume are already taken', 422);
        }

        if ((int) ManageItemsProcedure::create(self::createInputs($validated, $request->user()))['pRes'] !== 1) {
            throw new ReturnResponseException('Process error', 100);
        }

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => true,
            ],
        ], 201);
    }

    public static function createInputs(array $validated, $user): array
    {
        return [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'] . ($validated['issn'] ?? '') . ($validated['volume'] ?? ''),
            'item_type' => $validated['item_type'],
            'batch_id' => $validated['batch_id'],
            'publisher_id' => $validated['publisher_id'],
            'count' => $validated['count'],
            'cost' => $validated['cost'],
            'currency' => $validated['currency'],
            'location' => $validated['location'],
            'pub_year' => $validated['pub_year'],
            'pub_city' => $validated['pub_city'],
            'prog_code' => $validated['prog_code'] ?? '',
            'user_cid' => $user->user_cid
        ];
    }

    /**
     * @param UpdateRequest $request
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        if ((int) ManageItemsProcedure::edit(self::updateInputs($request->validated(), $request->user()))['pRes'] !== 1) {
            throw new ReturnResponseException('Process error', 100);
        }

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => true,
            ],
        ]);
    }

    public static function updateInputs(array $validated, $user): array
    {
        return [
            'inv_id' => $validated['inv_id'],
            'cost' => $validated['cost'],
            'currency' => $validated['currency'],
            'location' => $validated['location'],
            'user_cid' => $user->user_cid
        ];
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function delete(int $id): JsonResponse
    {
        if ((int) ManageItemsProcedure::delete(['inv_id' => $id])['pInvId'] !== 1) {
            throw new ReturnResponseException('Process error', 100);
        }

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => true,
            ],
        ]);
    }

    public function recreate(RecreateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['user_cid'] = $request->user()->user_cid;
        $validated['create_date'] = CarbonImmutable::now()->toDateString();

        if ((int) ManageItemsProcedure::recreate($validated)['pRes'] !== 1) {
            throw new ReturnResponseException('Process error', 100);
        }

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => true,
            ],
        ], 201);
    }
}
