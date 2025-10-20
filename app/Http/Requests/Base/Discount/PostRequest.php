<?php

namespace App\Http\Requests\Base\Discount;

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
            'order_marketplace_id' => 'required|numeric|exists:order_marketplace,id',
            'reason' => 'required|string|max:1000',
            'amount' => 'required|integer',
            'applies_to' => 'required|integer',
        ];
    }
}
