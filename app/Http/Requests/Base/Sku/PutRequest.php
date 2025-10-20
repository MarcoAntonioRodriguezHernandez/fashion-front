<?php

namespace App\Http\Requests\Base\Sku;

use Illuminate\Foundation\Http\FormRequest;

class PutRequest extends FormRequest
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
            'sku' => 'required|numeric|unique:skus,sku',
            'name' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ];
    }
}
