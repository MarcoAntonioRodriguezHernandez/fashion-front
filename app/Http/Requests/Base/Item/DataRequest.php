<?php

namespace App\Http\Requests\Base\Item;

use App\Enums\{
    ItemConditions,
    ItemStatuses,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class DataRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'product_title' => 'required|string|max:255',
            'full_price' => 'required|integer|min:0',
            'description' => 'required|string|max:1000',
            'pricing_scheme_id' => 'required|integer|exists:pricing_schemes,id',
            'condition' => ['required', 'integer', new Enum(ItemConditions::class)],
            'status' => ['required', 'integer', new Enum(ItemStatuses::class)],
            'price_sale' => 'required|decimal:0,2|min:0',
            'serial_number' => 'required|string|max:255|unique:items,serial_number,' . $this->item_id,
            'barcode' => 'nullable|string|max:255|unique:items,barcode,' . $this->item_id,
            'details' => 'nullable|string|max:65535',
        ];
    }
}
