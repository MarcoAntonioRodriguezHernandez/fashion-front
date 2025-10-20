<?php

namespace App\Http\Requests\Marketplace\ItemOrderMarketplace;

use App\Enums\OrderSaleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PutRequest extends FormRequest
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
            'order_marketplace_id' => 'prohibited',
            'additional_notes' => 'nullable|string|max:255',
            'fitting_price' => 'nullable|integer|min:0',
            'item_price' => 'prohibited',
            'fitting_notes' => 'required_with:fitting_price|string|max:255',
            'sale_type' => ['required', 'integer', new Enum(OrderSaleType::class)],
            'status' => 'required|boolean',
            'rent_detail' => 'required_if:sale_type,' . OrderSaleType::RENT->value,
            // 'rent_detail' => 'required_if:sale_type,' . OrderSaleType::RENT->value . '|array:date_start,date_end', // TODO: use 'array' validation rule
            'rent_detail.date_start' => 'required_with:rent_detail|date|after_or_equal:today',
            'rent_detail.date_end' => 'required_with:rent_detail|date|after:rent_detail.date_start',
            'rent_detail.status' => 'required_with:rent_detail|boolean',
            'rent_detail.insurance_price' => 'nullable|integer|min:0',
        ];
    }
}
