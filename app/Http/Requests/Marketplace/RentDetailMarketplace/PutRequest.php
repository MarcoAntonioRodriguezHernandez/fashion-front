<?php

namespace App\Http\Requests\Marketplace\RentDetailMarketplace;

use Illuminate\Foundation\Http\FormRequest;

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
            'item_order_marketplace_id' => 'required|integer|exists:item_order_marketplace,id',
            'date_start' => 'required|date|after_or_equal:today',
            'date_end' => 'required|date|after_or_equal:date_start',
            'insurance_price' => 'nullable|integer|min:1',
            'description' => 'required|string|max:255',
            'status' => 'required|integer',
        ];
    }
}
