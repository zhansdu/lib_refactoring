<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'search_options' => 'nullable|array',
            'search_options.*' => 'nullable|array',
            'search_options.*.key' => 'nullable|string',
            'search_options.*.value' => 'nullable',
            'search_options.*.operator' => 'nullable|in:and,or,not',

            'add_options' => 'nullable|array',
            'add_options.*' => 'nullable|array',
            'add_options.*.key' => 'nullable|string',
            'add_options.*.value' => 'nullable',
            'add_options.*.operator' => 'nullable|in:and,or,not',

            'order' => 'nullable|array',
            'order.key' => 'nullable|string',
            'order.mode' => 'nullable|string',

            'filter' => 'nullable|array',
            'filter.*' => 'nullable|array',
            'filter.*.key' => 'nullable|string',
            'filter.*.value' => 'nullable'
        ];
    }
}
