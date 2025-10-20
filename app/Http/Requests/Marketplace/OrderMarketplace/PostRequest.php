<?php

namespace App\Http\Requests\Marketplace\OrderMarketplace;

use App\Enums\OrderStatuses;
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
            //
            'user_id' => 'required|integer',
            'code' => 'required|string',
            'marketplace_id' => 'required|integer',
            'amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'surcharge' => 'prohibited',
            'store_id' => 'required|integer',
            'number_products' => 'required|integer',
            'status' => ['required', new Enum(OrderStatuses::class)],
            'date_cancelled' => 'nullable|date',
        ];
    }
}
