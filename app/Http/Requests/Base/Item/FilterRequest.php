<?php

namespace App\Http\Requests\Base\Item;

use App\Enums\ItemConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'values' => 'nullable|prohibits:name,barcode,store,designer,sizes,colors,characteristics,status|array|min:1',
            'values.*' => 'required_with:values|integer|exists:items,id',
            'name' => 'nullable|prohibits:values|string|min:0|max:255',
            'barcode' => 'nullable|prohibits:values|string|min:0|max:255',
            'store' => 'nullable|prohibits:values|integer|exists:stores,id',
            'category' => 'nullable|prohibits:values|integer|exists:categories,id',
            'designer' => 'nullable|prohibits:values|integer|exists:designers,id',
            'sizes' => 'nullable|prohibits:values|array|min:0',
            'sizes.*' => 'required_with:sizes|exists:sizes,id',
            'colors' => 'nullable|prohibits:values|array|min:0',
            'colors.*' => 'required_with:colors|exists:colors,id',
            'characteristics' => 'nullable|prohibits:values|array|min:0',
            'characteristics.*' => 'required_with:characteristics|exists:characteristics,id',
            'condition' => ['nullable', 'prohibits:values', 'integer', new Enum(ItemConditions::class)],
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->merge([
            'name' => trim($this->get('name')),
            'barcode' => trim($this->get('barcode')),
        ]);
    }
}
