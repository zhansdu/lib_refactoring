<?php

namespace App\Http\Controllers\Api\Report\MostReadBooks;

use App\Exports\Report\MostReadBooksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\MostReadBooks\ExportRequest;
use App\Models\Media\Loan;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __invoke(ExportRequest $request): BinaryFileResponse
    {
        $validated = $request->validated();

        $data = Loan::mostReadBooks()->whereIn('i.book_id', $validated['media'])
            ->orWhereIn('i.disc_id', $validated['media'])
            ->orWhereIn('i.j_issue_id', $validated['media'])->get();

        return Excel::download(new MostReadBooksExport($data, $validated['locale'] ?? app()->getLocale()), 'most_read_books.xlsx');
    }
}
