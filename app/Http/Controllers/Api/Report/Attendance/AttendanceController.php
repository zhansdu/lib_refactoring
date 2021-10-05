<?php

namespace App\Http\Controllers\Api\Report\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Media\Loan;
use App\Models\User\WebLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;

class AttendanceController extends Controller
{
    private array $weeks = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
    ];

    private array $months = [
        'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september',
        'october', 'november', 'december'
    ];

    public function getVirtualAttendance(): JsonResponse
    {
        $byWeek = WebLog::whereByWeek(WebLog::byWeek());
        $byMonth = WebLog::whereByMonth(WebLog::byMonths());

        $studentsWeek = $byWeek->addSelect(DB::raw("count(uc.stud_id) as count"))->get()->toArray();
        $studentsMonth = $byMonth->addSelect(DB::raw("count(uc.stud_id) as count"))->get()->toArray();
        $employeesWeek = $byWeek->addSelect(DB::raw("count(uc.emp_id) as count"))->get()->toArray();
        $employeesMonth = $byMonth->addSelect(DB::raw("count(uc.emp_id) as count"))->get()->toArray();

        $totalOfWeek = WebLog::whereByWeek(WebLog::total())->first()->toArray();
        $totalOfMonth = WebLog::whereByMonth(WebLog::total())->first()->toArray();

        return response()->json([
            'res' => [
                'byWeek' => [
                    'students' => $this->processData($studentsWeek, $this->weeks),
                    'employees' => $this->processData($employeesWeek, $this->weeks),
                    'total' => $totalOfWeek,
                ],
                'byMonth' => [
                    'students' => $this->processData($studentsMonth, $this->months),
                    'employees' => $this->processData($employeesMonth, $this->months),
                    'total' => $totalOfMonth,
                ],
            ]
        ]);
    }

    public function getDepartments(): JsonResponse
    {
        return response()->json([
            'res' => Loan::departments()->get()->toArray()
        ]);
    }

    #[Pure] private function processData(array $data, array $source): array
    {
        $result = array_pad([], sizeof($source), 0);
        foreach ($data as $item) {
            $result[array_search($item['name'], $source)] = (int)$item['count'];
        }

        return $result;
    }
}
