<?php

namespace App\Http\Requests\Base\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\CategoryStatuses;

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
            'name' => 'nullable|string',
            'parent_category_id' => 'nullable|integer|exists:categories,id',
            'characteristics' => 'nullable|array',
            'characteristics.*' => 'required_with:characteristics|integer|exists:characteristics,id,parent_characteristic_id,NULL',
            'status' => ['required', 'integer', new Enum(CategoryStatuses::class)],
        ];
    }
}
