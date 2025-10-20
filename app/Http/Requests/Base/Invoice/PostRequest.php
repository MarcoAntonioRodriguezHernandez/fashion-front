<?php

namespace App\Http\Requests\Base\Invoice;

use App\Enums\PaymentStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FormRequest
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
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'buyer_id' => 'required|integer|exists:users,id',
            'payment_status' => ['required', new Enum(PaymentStatuses::class)],
            'issuance_date' => 'required|date|before_or_equal:today',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'exchange_rate' => 'required|numeric|min:0',
            'invoice_file' => 'required|file',
        ];
    }
}
