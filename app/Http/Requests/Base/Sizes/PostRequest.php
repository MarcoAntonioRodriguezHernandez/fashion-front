<?php

namespace App\Http\Requests\Base\Sizes;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'name' => 'required|string|unique:sizes,name',
            'number' => 'nullable|integer|min:0',
            'hex_color' => 'required|string|regex:/^#[0-9a-f]{6}$/',
            'characteristics' => 'nullable|array',
            'characteristics.*' => 'required_with:characteristics|integer|exists:characteristics,id',
        ];
    }
}
