<?php

namespace App\Http\Requests\Base\Invoice;

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
            'invoice_number' => 'required|string|max:255',
            'buyer' => 'required|string|max:255',
            'payment_status' => ['required', 'integer', new Enum(PaymentStatuses::class)],
            'issuance_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'payment_type_id' => 'required|string|max:255',
            'exchange_rate' => 'required|numeric',
            'invoice_file' => 'required|integer',
            'file' => 'sometimes|file',
        ];
    }
}
