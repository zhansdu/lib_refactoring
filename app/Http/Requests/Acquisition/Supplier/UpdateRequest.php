<?php

namespace App\Http\Requests\Acquisition\Supplier;

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
        return [
            'sup_id' => 'required|integer',
            'sup_name' => 'required|string',
            'bin' => 'nullable|integer',
            'com_name' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'fax' => 'nullable|string'
        ];
    }
}
