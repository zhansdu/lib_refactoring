<?php

namespace App\Exports\Report;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

final class KsuReportExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            __('prints/ksu_report.create_date', [], $this->locale),
            __('prints/ksu_report.batch_id', [], $this->locale),
            __('prints/ksu_report.supplier', [], $this->locale),
            __('prints/ksu_report.doc_year', [], $this->locale),
            __('prints/ksu_report.in_balance_items', [], $this->locale),
            __('prints/ksu_report.in_balance_titles', [], $this->locale),
            __('prints/ksu_report.in_balance_price', [], $this->locale),
            __('prints/ksu_report.not_in_balance_items', [], $this->locale),
            __('prints/ksu_report.not_in_balance_titles', [], $this->locale),
            __('prints/ksu_report.not_in_balance_price', [], $this->locale),
            __('prints/ksu_report.total_items', [], $this->locale),
            __('prints/ksu_report.total_titles', [], $this->locale),
            __('prints/ksu_report.ru_lang_materials', [], $this->locale),
            __('prints/ksu_report.kz_lang_materials', [], $this->locale),
            __('prints/ksu_report.other_lang_materials', [], $this->locale),
            __('prints/ksu_report.null_lang', [], $this->locale),
            __('prints/ksu_report.disc_titles', [], $this->locale),
            __('prints/ksu_report.disc_items', [], $this->locale),
            __('prints/ksu_report.disc_totalcost', [], $this->locale),
            __('prints/ksu_report.disc_language_kz', [], $this->locale),
            __('prints/ksu_report.disc_language_ru', [], $this->locale),
            __('prints/ksu_report.disc_language_other', [], $this->locale),
        ];
    }
}
