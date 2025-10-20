<?php

namespace App\Http\Requests\Base\PaymentOrderMarketplace;

use App\Enums\PaymentOrderMarketplaceReason;
use App\Enums\PaymentStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'id' => 'required|integer|exists:payment_order_marketplace,id',
            'order_marketplace_id' => 'prohibited',
            'total' => 'prohibited',
            'payment' => 'prohibited',
            'payment_type_id' => 'prohibited',
            'status' => ['required', 'integer', new Enum(PaymentStatuses::class)],
        ];
    }
}
