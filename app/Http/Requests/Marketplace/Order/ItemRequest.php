<?php

namespace App\Http\Requests\Marketplace\Order;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|min:0|max:255',
            'barcode' => 'nullable|string|min:0|max:255',
            'category' => 'nullable|integer|exists:categories,id',
            'designer' => 'nullable|prohibits:values|integer|exists:designers,id',
            'colors' => 'nullable|array|min:0',
            'colors.*' => 'required_with:colors|exists:colors,id',
            'characteristics' => 'nullable|prohibits:values|array|min:0',
            'characteristics.*' => 'required_with:characteristics|exists:characteristics,id',
        ];
    }
}
