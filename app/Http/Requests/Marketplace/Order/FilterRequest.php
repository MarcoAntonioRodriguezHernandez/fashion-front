<?php

namespace App\Http\Requests\Marketplace\Order;

use App\Enums\OrderSaleType;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'client_name' => 'nullable|string|min:1|max:255',
            'client_email' => 'nullable|string|min:1|max:255',
            'order_code' => 'nullable|string|min:1|max:255',
            'order_store_id' => 'nullable|integer|exists:stores,id',
            'product_name' => 'nullable|string|min:1|max:255',
            'order_start_date' => 'nullable|date',
            'order_finish_date' => 'nullable|date',
            'sale_type' => 'nullable|in:0,' . OrderSaleType::SALE->value . ',' . OrderSaleType::RENT->value,
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->merge([
            'order_code' => trim($this->get('order_code')),
        ]);
    }
}
