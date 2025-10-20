<?php

namespace App\Http\Requests\Base\Variant;

use Illuminate\Foundation\Http\FormRequest;

class PutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'size_id' => 'required|integer|exists:sizes,id',
            'color_id' => 'required|integer|exists:colors,id',
            'code' => 'required|string|max:255',
            'status' => 'required|in:1,0',
        ];
    }
}
