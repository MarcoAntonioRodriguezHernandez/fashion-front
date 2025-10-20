<?php

namespace App\Http\Requests\Base\Supply;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'origin_id' => 'nullable|integer|exists:stores,id',
            'destination_id' => 'nullable|integer|exists:stores,id',
            'active_only' => 'nullable|boolean',
        ];
    }
}
