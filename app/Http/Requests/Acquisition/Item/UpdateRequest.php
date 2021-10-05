<?php

namespace App\Http\Requests\Acquisition\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return ['inv_id' => 'required|integer',
            'cost' => 'required|integer',
            'location' => 'required|string|max:10',
            'currency' => 'required|string|max:3',
            'prog_code' => 'nullable|string'];
    }
}
