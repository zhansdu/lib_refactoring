<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
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
            'inv_id' => 'required|int',
            'loan_id' => 'required|int',
            'user_cid' => 'required|string',
            'duration' => 'nullable|int',
            'due_date' => 'nullable|date',
        ];
    }
}
