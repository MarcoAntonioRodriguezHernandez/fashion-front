<?php

namespace App\Http\Requests\Base\SuppliedItem;

use App\Enums\{
    ItemIntegrities,
    SupplyStatuses,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FormRequest
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

            'supply_transfer_id' => 'required|exists:supply_transfers,id',
            'item_id' => 'required|exists:items,id',
            'delivered' => 'required|boolean',
            'status' => ['required', 'integer', new Enum(SupplyStatuses::class)],
            'integrity' => ['nullable', 'integer', new Enum(ItemIntegrities::class)],
            'details' => 'nullable|string|max:255',
        ];
    }
}
