<?php

namespace App\Http\Requests\Marketplace\OrderMarketplace;

use Illuminate\Foundation\Http\FormRequest;

class CancellationRequest extends FormRequest
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
            'ordenId' => 'required|string',
            'status' => 'required|integer',
            'parcial' => 'required|boolean',
            'productos' => 'required|array',
            'productos.*' => 'required|array:variantId,sku',
            'productos.*.variantId' => 'required|integer',
            'productos.*.sku' => 'required|string',
            'dateCanceled' => 'required|date|before_or_equal:today',
            'storeId' => 'required|integer',
            'storeName' => 'required|string',
        ];
    }
}
