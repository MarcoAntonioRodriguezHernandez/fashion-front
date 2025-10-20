<?php

namespace App\Http\Requests\Base\Supply;

use Illuminate\Contracts\Validation\Validator;
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
            'sender_id' => 'prohibited',
            'code' => 'required|string|max:255',
            'shipping_date' => 'prohibited',
            'status' => 'prohibited',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.destination_id' => 'required|integer|exists:stores,id',
            'items.*.recipient_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
