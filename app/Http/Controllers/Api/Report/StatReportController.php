<?php

namespace App\Http\Controllers\Api\Report;

use App\Exports\Report\StatReportExport;
use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class StatReportController extends Controller
{
    public function getReport(Request $request): JsonResponse
    {
        $reportBuilder = $this->getBuilder();

        if ($request->exists('year')) {
            $reportBuilder = $reportBuilder
                ->where(DB::raw("to_char(t.borrow_date, 'YYYY')"), $request->get('year'));
        }

        return response()->json([
            'res' => $reportBuilder->get()->toArray()
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $reportBuilder = $this->getBuilder();

        return Excel::download(
            new StatReportExport($reportBuilder->get(), $request->get('locale', app()->getLocale())),
            'stat_report_' . CarbonImmutable::now()->toDateString() . '.xlsx'
        );
    }

    private function getBuilder(): Builder
    {
        return DB::table('LIB_LOANS as t')
            ->select([
                DB::raw("to_char(t.borrow_date, 'YYYY') as year"),
                DB::raw("to_char(t.borrow_date, 'MONTH') as month"),
                DB::raw("sum(decode(t.inv_id, NULL, 0, 1)) as total_borrow"),
                DB::raw("sum(decode(t.locked, 0, 1, 0)) as on_hands"),
            ])
            ->groupBy([
                DB::raw("to_char(t.borrow_date, 'YYYY')"),
                DB::raw("to_char(t.borrow_date, 'MM')"),
                DB::raw("to_char(t.borrow_date, 'MONTH')"),
            ])
            ->orderBy('year')
            ->orderBy(DB::raw("to_char(t.borrow_date, 'MM')"));
    }
}
