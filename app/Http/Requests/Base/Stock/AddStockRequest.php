<?php

namespace App\Http\Requests\Base\Stock;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ItemStatuses;

class AddStockRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'price_purchase' => 'required|decimal:0,2|min:1',
            'invoice_id' => 'required_without:invoice|integer|exists:invoices,id',
            'invoice' => 'required_without:invoice_id|array',
            'inventory' => 'required|array',
            'inventory.*.color_id' => 'required|integer|exists:colors,id',
            'inventory.*.size_id' => 'required|integer|exists:sizes,id',
            'inventory.*.price_sale' => 'required|integer|min:1',
            'inventory.*.amount' => 'required|integer|min:1',
            'inventory.*.store_id' => 'prohibited',
            'inventory.*.details' => 'prohibited',
            'inventory.*.status' => 'nullable|integer|in:' . implode(',', array_keys(ItemStatuses::getAllNames())),
            'inventory.*.condition' => 'prohibited',
            'inventory.*.integrity' => 'prohibited',
        ];
    }
}
