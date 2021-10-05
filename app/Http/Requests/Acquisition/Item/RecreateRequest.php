<?php

namespace App\Http\Requests\Acquisition\Item;

use Illuminate\Foundation\Http\FormRequest;

final class RecreateRequest extends FormRequest
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
            'book_id' => 'nullable|integer',
            'j_issue_id' => 'nullable|integer',
            'disc_id' => 'nullable|integer',
            'batch_id' => 'required|integer',
            'count' => 'required|integer',
            'cost' => 'required|integer',
            'currency' => 'required|string',
            'location' => 'required|string',
        ];
    }
}
