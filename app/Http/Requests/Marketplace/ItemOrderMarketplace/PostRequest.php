<?php

namespace App\Http\Requests\Marketplace\ItemOrderMarketplace;

use App\Enums\OrderSaleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => 'required|integer|exists:items,id',
            'order_marketplace_id' => 'required|integer|exists:order_marketplace,id',
            'additional_notes' => 'required|string|max:255',
            'fitting_price' => 'nullable|integer|min:1',
            'fitting_notes' => 'present_with:fitting_price|string|max:255',
            'sale_type' => ['required', 'integer', new Enum(OrderSaleType::class)],
            'status' => 'required|integer',
        ];
    }
}
