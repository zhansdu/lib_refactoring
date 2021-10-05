<?php


namespace App\Common\Helpers\Controller;


use App\Common\Helpers\Search\FilterHelper;
use App\Common\Helpers\Search\OrderHelper;
use App\Common\Helpers\Search\SearchHelper;
use App\Common\Interfaces\Fields\FieldInterface;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class Search
{
    public static function search(FormRequest $request, EBuilder|Builder $builder, FieldInterface $field): ECollection|Collection
    {
        $validated = $request->validated();

        // Advanced search
        $searchFields = $field::getSearchFields();

        $options = $validated['search_options'] ?? [];
        if (!empty($options)) {
            $builder = $builder->where(function ($query) use ($searchFields, $options) {
                foreach ($options as $option) {
                    switch ($option['key']) {
                        case 'all':
                            foreach ($searchFields as $searchField) {
                                if ($searchField['key'] !== 'all') {
                                    if (!empty($option['value'])) {
                                        $customOption = [
                                            'key' => $searchField['key'],
                                            'operator' => 'or',
                                            'value' => $option['value'],
                                        ];
                                        $query = SearchHelper::search($query, $searchFields, $customOption);
                                    }
                                }
                            }
                            break;
                        default:
                            if (!empty($option['value'])) {
                                $query = SearchHelper::search($query, $searchFields, $option);
                            }
                    }
                }
            });
        }

        // Additional search
        $searchFields = $field::getAddSearchFields();
        $options = $validated['add_options'] ?? [];

        foreach ($options as $option) {
            switch ($option['key']) {
                case 'all':
                    foreach ($searchFields as $searchField) {
                        if ($searchField['key'] !== 'all') {
                            if (!empty($option['value'])) {
                                $customOption = [
                                    'key' => $searchField['key'],
                                    'operator' => 'or',
                                    'value' => $option['value']
                                ];
                                $builder = SearchHelper::search($builder, $searchFields, $customOption);
                            }
                        }
                    }
                    break;
                default:
                    if (!empty($option['value'])) {
                        $builder = SearchHelper::search($builder, $searchFields, $option);
                    }
            }
        }

        $options = $validated['order'] ?? [];
        $builder = OrderHelper::order($builder, $options);

        $data = $builder->get();

        // Filter
        $options = $validated['filter'] ?? [];

        foreach ($options as $option) {
            $data = FilterHelper::filter($data, $field::getFilterFields(), $option);
        }

        return $data;
    }
}
