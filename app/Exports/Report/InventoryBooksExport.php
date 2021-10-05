<?php


namespace App\Exports\Report;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryBooksExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        return $this->data->map(function ($item) {
            return [
                'create_date|batch_id' => "{$item->create_date}, {$item->batch_id}",
                'inventory_no' => $item->inventory_no,
                'check' => "-",
                'author_title' => $item->author_title,
                'year_city' => $item->year_city,
                'cost' => "{$item->cost} {$item->currency}",
                'barcode' => $item->barcode,
                'call_number' => $item->call_number,
                'doc_no' => $item->doc_no
            ];
        });
    }

    public function headings(): array
    {
        return [
            __('prints/inventories.create_date', [], $this->locale) . ', ' . __('prints/inventories.batch_id', [], $this->locale),
            __('prints/inventories.inventory_no', [], $this->locale),
            __('prints/inventories.check', [], $this->locale),
            __('prints/inventories.author_title', [], $this->locale),
            __('prints/inventories.year_city', [], $this->locale),
            __('prints/inventories.cost', [], $this->locale),
            __('prints/inventories.barcode', [], $this->locale),
            __('prints/inventories.call_number', [], $this->locale),
            __('prints/inventories.doc_no', [], $this->locale)
        ];
    }
}
