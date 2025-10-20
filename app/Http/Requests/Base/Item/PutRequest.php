<?php

namespace App\Http\Requests\Base\Item;

use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
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
            'product_id' => 'required|integer|exists:products,id',
            'color_id' => 'required|integer|exists:colors,id',
            'size_id' => 'required|integer|exists:sizes,id',
            'variant_id' => 'prohibited',
            'store_id' => 'prohibited',
            'invoice_id' => 'required|integer|exists:invoices,id',
            'barcode' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'price_sale' => 'required|decimal:0,2|min:0',
            'price_purchase' => 'required|decimal:0,2|min:0',
            'status' => ['required', 'integer', new Enum(ItemStatuses::class)],
            'condition' => ['required', 'integer', new Enum(ItemConditions::class)],
            'integrity' => ['required', 'integer', new Enum(ItemIntegrities::class)],
            'details' => 'required|string|max:65535',
        ];
    }
}
