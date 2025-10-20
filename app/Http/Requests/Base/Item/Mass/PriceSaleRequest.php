<?php

namespace App\Http\Requests\Base\Item\Mass;

use Illuminate\Foundation\Http\FormRequest;

class PriceSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items'       => 'required_without:select_all|array|min:1',
            'items.*'     => 'integer|exists:items,id',
            'select_all'  => 'sometimes|boolean',
            'filters'     => 'required_if:select_all,1|array',
            'price_sale'  => 'required|numeric|min:1',
        ];
    }
}
