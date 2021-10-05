<?php

namespace App\Exports\Report;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

final class StatReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private Collection $data;
    private string $locale;

    public function __construct(Collection $data, string $locale)
    {
        $this->data = $data;
        $this->locale = $locale;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            __('prints/stat_report.year', [], $this->locale),
            __('prints/stat_report.month', [], $this->locale),
            __('prints/stat_report.total_borrow', [], $this->locale),
            __('prints/stat_report.on_hands', [], $this->locale),
        ];
    }
}
