<?php

namespace App\Http\Controllers\Api\Service;

use App\Common\Fields\Service\UserSearchFields;
use App\Common\Helpers\Show\FilterFields;
use App\Common\Helpers\Show\SearchFields;
use App\Common\Helpers\Show\SortFields;
use App\Exceptions\ReturnResponseException;
use App\Http\Controllers\Controller;
use App\Models\Media\Loan;
use App\Models\User\Employee;
use App\Models\User\Image;
use App\Models\User\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{
    public function show(string $type, int $id): JsonResponse
    {
        $model = (match ($type) {
            'student' => Student::fullInfo($id),
            'employee' => Employee::fullInfo($id),
            default => throw new ReturnResponseException('Incorrect user type', 400),
        })->first();

        if (empty($model)) {
            throw new ReturnResponseException('User not found', 404);
        }

        $image = Image::find($id);
        $media = isset($model->user_cid) && !empty($model->user_cid) ? Loan::userHistory($model->user_cid)->get()->toArray() : [];

        $image = $image ? 'data:image/' . $type . ';base64,' . base64_encode($image->image) : null;

        $total = $this->countTotal($media);
        $borrowed = $this->getBorrowedBooks($media);

        $duration = DB::table('lib_cfg as lc')->select('lc.data')
            ->leftJoin('user_groups as ug', 'ug.group_id', '=', 'lc.group_id')
            ->where('lc.cfg_key', '=', 'BORROW_PERIOD')
            ->where('ug.user_cid', '=', $model->user_cid)
            ->orderBy('data', 'desc')
            ->first();

        return response()->json([
            'res' => [
                'info' => $model,
                'photo' => $image,
                'history' => $media,
                'total' => $total,
                'return' => $borrowed,
                'duration' => !empty($duration) ? $duration->data : 21
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function userInfo(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->show($user->type, $user->id);
    }

    public function types(): JsonResponse
    {
        return response()->json([
            'res' => [
                'types' => [
                    ['key' => 'student', 'title' => 'Student'],
                    ['key' => 'employee', 'title' => 'Staff']
                ]
            ],
        ]);
    }

    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new UserSearchFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new UserSearchFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new UserSearchFields())
        ]);
    }

    private function getBorrowedBooks(array $media): array
    {
        $borrowed = [];
        foreach ($media as $item) {
            if ($item['status'] === 'issued' || $item['status'] === 'overdue') {
                $borrowed[] = $item;
            }
        }

        return $borrowed;
    }

    private function countTotal(array $media): array
    {
        $borrowed = [];
        $returned = [];
        $dept = [];

        foreach ($media as $item) {
            switch ($item['status']) {
                case 'issued':
                    $borrowed[] = $item;
                    break;
                case 'overdue':
                    $dept[] = $item;
                    break;
                case 'returned':
                    $returned[] = $item;
                    break;
            }
        }

        $borrowed = count($borrowed);
        $returned = count($returned);
        $dept = count($dept);

        return ['borrowed' => $borrowed, 'returned' => $returned, 'dept' => $dept];
    }
}
