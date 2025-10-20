<?php

namespace App\Http\Requests\Base\Category;

use App\Enums\CategoryStatuses;
use Illuminate\Validation\Rules\Enum;
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
            'name' => 'required|string|unique:categories,name',
            'parent_category_id' => 'nullable|integer|exists:categories,id',
            'characteristics' => 'nullable|array',
            'characteristics.*' => 'required_with:characteristics|integer|exists:characteristics,id,parent_characteristic_id,NULL',
            'status' => ['required', 'integer', new Enum(CategoryStatuses::class)],
        ];
    }
}
