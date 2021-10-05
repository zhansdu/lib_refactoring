<?php


namespace App\Exports\Report;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MostReadBooksExport implements FromCollection, WithHeadings, ShouldAutoSize
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
                'author' => $item->author,
                'title' => $item->title,
                'language' => $item->language,
                'isbn' => $item->isbn,
                'count_issue' => $item->count_issue,
            ];
        });
    }

    public function headings(): array
    {
        return [
            __('prints/most_read_books.author', [], $this->locale),
            __('prints/most_read_books.title', [], $this->locale),
            __('prints/most_read_books.language', [], $this->locale),
            __('prints/most_read_books.isbn', [], $this->locale),
            __('prints/most_read_books.count_issue', [], $this->locale),
        ];
    }
}
