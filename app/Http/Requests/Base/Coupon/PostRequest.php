<?php

namespace App\Http\Requests\Base\Coupon;

use App\Enums\{
    CategoryStatuses,
    CouponTypes,
    OrderSaleType,
};
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
            'code' => 'required|string|unique:coupons,code',
            'uses_amount' => 'nullable|integer|min:1',
            'category_id'=>'nullable|integer|exists:categories,id',
            'sale_type' => ['required','integer', new Enum(OrderSaleType::class)],
            'min_products' => 'required|integer|min:1',
            'discount' => 'required|integer|min:1',
            'coupon_type' => ['required','integer', new Enum(CouponTypes::class)],
            'date_start' => 'nullable|date|after_or_equal:today',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'status' => 'required|boolean',
        ];
    }
}