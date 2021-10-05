<?php


namespace App\Exports\Report;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookHistoryExport implements FromCollection, WithHeadings, ShouldAutoSize
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
                'barcode' => $item->barcode,
                'inventory_no' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'author' => $item->author,
                'borrow_date' => $item->borrow_date,
                'due_date' => $item->due_date,
                'delivery_date' => $item->delivery_date,
                'status' => $item->status,
                'username' => $item->username
            ];
        });
    }

    public function headings(): array
    {
        return [
            __('prints/book_history.barcode', [], $this->locale),
            __('prints/book_history.inventory_no', [], $this->locale),
            __('prints/book_history.type', [], $this->locale),
            __('prints/book_history.title', [], $this->locale),
            __('prints/book_history.author', [], $this->locale),
            __('prints/book_history.borrow_date', [], $this->locale),
            __('prints/book_history.due_date', [], $this->locale),
            __('prints/book_history.delivery_date', [], $this->locale),
            __('prints/book_history.status', [], $this->locale),
            __('prints/book_history.username', [], $this->locale)
        ];
    }
}
