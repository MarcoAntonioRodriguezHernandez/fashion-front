<?php

namespace App\Http\Requests\Examples;

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
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'creation' => 'nullable|date',
            'value' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'status' => 'nullable|boolean',
            'example_type_id' => 'nullable|exists:example_types,id',
        ];
    }
}
