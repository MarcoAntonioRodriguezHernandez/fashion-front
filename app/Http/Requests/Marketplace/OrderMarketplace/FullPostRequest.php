<?php

namespace App\Http\Requests\Marketplace\OrderMarketplace;

use App\Enums\{
    FoundByMethods,
    OrderSaleType,
    OrderStatuses,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FullPostRequest extends FormRequest
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
        $extraRules = array_merge(
            $this->getShippingRules(),
            $this->getItemsRules(),
            $this->getIdentityRules(),
        );

        return [
            'client_id' => 'required|integer|exists:users,id',
            'code' => 'nullable|string|max:30|unique:order_marketplace,code',
            'marketplace_id' => 'prohibited',
            'amount' => 'prohibited',
            'surcharge' => 'prohibited',
            'store_id' => 'prohibited',
            'number_products' => 'prohibited',
            'status' => ['nullable', 'integer', new Enum(OrderStatuses::class)],
            'date_cancelled' => 'prohibited',
            'discount' => 'nullable|array:value,reason',
            'found_by' => ['required', 'integer', new Enum(FoundByMethods::class)],
            'created_at' => 'required|date|before_or_equal:now',
            ...$extraRules,
        ];
    }

    public function getEventRules()
    {
        return [
            'event_id' => 'nullable|integer|exists:events,id',
            'event' => 'nullable',
            'event.event_type_name' => 'prohibits:event.event_type_id|string|max:255',
            'event.event_type_id' => 'prohibits:event.event_type_name|integer|exists:event_types,id',
            'event.specification' => 'required_with:event|string|max:255',
            'event.scheduled_date' => 'required_with:event|date|after_or_equal:created_at_date',
        ];
    }

    private function getShippingRules()
    {
        return [
            'shipping' => 'nullable|array:shipping_price_id,order_marketplace_id,user_address_id,status',
            'shipping.shipping_price_id' => 'required_with:shipping|integer|exists:shipping_prices,id',
            'shipping.order_marketplace_id' => 'prohibited',
            'shipping.user_address_id' => 'required_with:shipping|integer|exists:user_addresses,id',
            'shipping.status' => 'prohibited',
        ];
    }

    private function getItemsRules()
    {
        return [
            'items' => 'required|array|min:1',
            // 'items.*' => 'required|array:item_id,sale_type', // TODO: use 'array' validation rule
            'items.*.item_id' => 'required_with:items|integer|exists:items,id',
            'items.*.order_marketplace_id' => 'prohibited',
            'items.*.sale_type' => ['required_with:items', 'integer', new Enum(OrderSaleType::class)],
            'items.*.additional_notes' => 'nullable|string|max:255',
            'items.*.fitting_price' => 'nullable|integer|min:0',
            'items.*.item_price' => 'prohibited',
            'items.*.fitting_notes' => 'required_with:items.*.fitting_price|string|max:255',
            'items.*.status' => 'prohibited',
            'items.*.rent_detail' => 'required_if:items.*.sale_type,' . OrderSaleType::RENT->value,
            // 'items.*.rent_detail' => 'required_if:sale_type,' . OrderSaleType::RENT->value . '|array:date_start,date_end', // TODO: use 'array' validation rule
            'items.*.rent_detail.date_start' => 'required_with:items.*.rent_detail|date|after_or_equal:created_at_date',
            'items.*.rent_detail.date_end' => 'required_with:items.*.rent_detail|date|after:items.*.rent_detail.date_start',
            'items.*.rent_detail.insurance_price' => 'nullable|integer|min:0',
        ];
    }

    private function getIdentityRules()
    {
        return [
            'has_rents' => 'required|boolean',
            'identity_document_front' => 'required_if_accepted:has_rents|image|mimes:jpeg,png,jpg',
            'identity_document_back' => 'required_if_accepted:has_rents|image|mimes:jpeg,png,jpg',
            'contract_signature' => 'required_if_accepted:has_rents|string',
        ];
    }
}
