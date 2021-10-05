<?php

namespace App\Http\Requests\Acquisition\Item;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'title' => 'required|string|max:500',
            'author' => 'required|string|max:500',
            'isbn' => 'required|string|max:30',
            'issn' => 'nullable|string',
            'volume' => 'nullable|string',
            'batch_id' => 'required|integer',
            'item_type' => 'required|string|max:2',
            'publisher_id' => 'required|integer',
            'pub_year' => 'required|digits:4',
            'pub_city' => 'required|string|max:250',
            'cost' => 'required|integer',
            'count' => 'required|integer',
            'location' => 'required|string|max:4',
            'currency' => 'required|string|max:3',
            'prog_code' => 'nullable|string',
        ];
    }
}
