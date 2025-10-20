<?php

namespace App\Http\Requests\Base\Products;

use App\Enums\{
    PaymentStatuses,
    ProductSaleTypes,
};
use App\Models\Base\{
    Characteristic,
    Tag,
};
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FullPostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $extraRules = array_merge(
            $this->getInventoryRules(),
            $this->getImagesRules(),
            $this->getTagsRules(),
            $this->getCharacteristicsRules(),
        );

        return [
            'name' => 'required|string|max:100|unique:products,name',
            'title' => 'required|string|max:150',
            'slug' => 'prohibited',
            'origin_code' => 'required|string|unique:products,origin_code',
            'internal_code' => 'prohibited',
            'full_price' => 'required|integer|min:0',
            'description' => 'required|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'designer_id' => 'required|integer|exists:designers,id',
            'invoice_id' => 'sometimes|integer|exists:invoices,id',
            'desired' => 'sometimes|boolean',
            'sale_type' => ['required', 'integer', new Enum(ProductSaleTypes::class)],
            'sku' => 'prohibited',
            'provider_id' => 'required_without:provider|integer|exists:providers,id',
            'provider' => 'required_without:provider_id|array',
            'invoice_id' => 'required_without:invoice|integer|exists:invoices,id',
            'invoice' => 'required_without:invoice_id|array',
            ...$extraRules,
        ];
    }

    private function getInventoryRules()
    {
        return [
            'inventory' => 'required|array|min:1',
            'inventory.*' => 'required_with:inventory|array:color_id,size_id,price_sale,amount,status',
            'inventory.*.color_id' => 'required_with:inventory|integer|exists:colors,id',
            'inventory.*.size_id' => 'required_with:inventory|integer|exists:sizes,id',
            'inventory.*.price_sale' => 'required_with:inventory|integer|min:1',
            'inventory.*.amount' => 'required_with:inventory|integer|min:1',
        ];
    }

    private function getImagesRules()
    {
        return [
            'images' => 'required|array',
            'images.*' => 'required|array',
            'images.*.color_id' => 'required|integer|exists:colors,id',
            'images.*.camera_perspective' => 'required|string|in:front,back,left,right',
        ];
    }

    private function getTagsRules()
    {
        return [
            'tags' => 'nullable|array',
            'tags.*' => ['required_with:tags', function (string $attr, mixed $val, Closure $fail) {
                if (is_numeric($val) && Tag::find($val) == null) { // If it's a numeric value but doesn't exists in the tags table
                    $fail('El campo de etiqueta seleccionado no existe o es inválido.');
                }
            },],
        ];
    }

    private function getCharacteristicsRules()
    {
        return [
            'characteristics' => 'required|array',
            'characteristics.*' => 'required|array',
            'characteristics.*.*' => ['required', function (string $attr, mixed $val, Closure $fail) {
                if (is_numeric($val) && Characteristic::find($val) == null) { // If it's a numeric value but doesn't exists in the tags table
                    $fail('El campo de characteristica seleccionado no existe o es inválido.');
                }
            },],
        ];
    }
}
