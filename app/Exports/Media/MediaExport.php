<?php


namespace App\Exports\Media;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MediaExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    const FULL_MEDIA_URI = '/full';
    private Collection $data;
    private string $locale;

    public function __construct(Collection $data, string $locale)
    {
        $this->data = $data;
        $this->locale = $locale;
    }

    public function collection(): Collection
    {
        $url = url('/') . self::FULL_MEDIA_URI;
        return $this->data->map(function ($item) use ($url) {
            return ['author' => $item->author, 'type' => $item->type, 'title' => $item->title,
                'publisher' => $item->publisher, 'isbn' => $item->isbn, 'call_number' => $item->call_number,
                'url' => $url . "?type={$item->type_key}&id={$item->id}"];
        });
    }

    public function headings(): array
    {
        return [
            __('prints/media.author', [], $this->locale),
            __('prints/media.type', [], $this->locale),
            __('prints/media.title', [], $this->locale),
            __('prints/media.publisher', [], $this->locale),
            __('prints/media.isbn', [], $this->locale),
            __('prints/media.call_number', [], $this->locale),
            __('prints/media.url', [], $this->locale),
        ];
    }
}
