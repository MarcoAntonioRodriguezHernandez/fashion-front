<?php

namespace App\Http\Requests\Marketplace\Order;

use App\Enums\OrderSaleType;
use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
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
            'store_id' => 'nullable|integer|exists:stores,id',
            'start_date' => 'nullable|date',
            'finish_date' => 'nullable|date',
            'sale_type' => 'nullable|in:0,' . OrderSaleType::SALE->value . ',' . OrderSaleType::RENT->value,
        ];
    }
}
