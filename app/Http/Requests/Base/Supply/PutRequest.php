<?php

namespace App\Http\Requests\Base\Supply;

use App\Enums\SupplyStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'sender' => 'prohibited',
            'code' => 'prohibited|string|max:8',
            'shipping_date' => 'prohibited|date',
            'status' => ['required', 'integer', new Enum(SupplyStatuses::class)],
        ];
    }
}
