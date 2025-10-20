<?php

namespace App\Http\Requests\Base\Store;

use App\Enums\StoreStatuses;
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
            'name' => 'required|string|max:255|unique:stores,name',
            'slug' => 'prohibited',
            'code' => 'required|string|max:100|unique:stores,code',
            'marketplace_id' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'cp' => 'required|numeric|digits:5|unique:stores,cp',
            'municipality' => 'required|string|max:100',
            'store_type' => 'required|string|max:50',
            'status' => ['required', 'integer', new Enum(StoreStatuses::class)],
        ];
    }
}
