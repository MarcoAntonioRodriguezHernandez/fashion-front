<?php

namespace App\Http\Requests\Base\SupplyTransfer;

use App\Enums\{
    ItemIntegrities,
    SupplyStatuses,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'supply_id' => 'prohibited',
            'recipient_id' => 'prohibited',
            'reception_date' => 'prohibited',
            'origin_id' => 'prohibited',
            'destination_id' => 'prohibited',
            'items' => 'required|array',
            'items.*' => 'required|array|required_array_keys:status',
            'items.*.status' => ['required', 'integer', new Enum(SupplyStatuses::class)],
            'items.*.integrity' => ['nullable', 'integer', new Enum(ItemIntegrities::class)],
            'items.*.details' => 'nullable|string|max:255',
        ];
    }
}
