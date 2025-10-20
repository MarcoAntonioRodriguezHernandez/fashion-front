<?php

namespace App\Http\Requests\Base\Store;

use App\Enums\StoreStatuses;
use Illuminate\Validation\Rules\Enum;
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
            'slug' => 'prohibited',
            'code' => 'nullable|string|max:100',
            'marketplace_id' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric|between:-90,90',
            'long' => 'nullable|numeric|between:-180,180',
            'cp' => 'required|numeric|digits:5',
            'municipality' => 'nullable|string|max:100',
            'store_type' => 'nullable|string|max:50',
            'status' => ['required', 'integer', new Enum(StoreStatuses::class)],
        ];
    }
}
