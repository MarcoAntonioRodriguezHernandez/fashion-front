<?php

namespace App\Http\Requests\Examples;

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
            'name' => 'required|string|max:255',
            'image' => 'required|image',
            'description' => 'required|string',
            'creation' => 'required|date',
            'value' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'required|boolean',
            'example_type_id' => 'required|exists:example_types,id',
        ];
    }
}
