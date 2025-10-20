<?php

namespace App\Http\Requests\Base\Products;

use App\Enums\ProductSaleTypes;
use App\Models\Base\{
    Characteristic,
    Tag,
};
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FormRequest
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
            $this->getImagesRules(),
            $this->getTagsRules(),
            $this->getCharacteristicsRules(),
        );

        return [
            'name' => 'required|string|max:100',
            'title' => 'required|string|max:150',
            'slug' => 'prohibited',
            'origin_code' => 'required|string|unique:products,origin_code',
            'internal_code' => 'string|unique:products,internal_code',
            'full_price' => 'required|integer|min:0',
            'description' => 'required|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'designer_id' => 'required|integer|exists:designers,id',
            'pricing_scheme_id' => 'required|integer|exists:pricing_schemes,id',
            'desired' => 'sometimes|boolean',
            'sale_type' => ['required', 'integer', new Enum(ProductSaleTypes::class)],
            'sku' => 'string|max:100',
            'providers' => 'nullable|array',
            'providers.*' => 'required_with:providers|integer|exists:providers,id',
            ...$extraRules,
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
            'characteristics' => 'nullable|array',
            'characteristics.*' => 'required_with:characteristics|array',
            'characteristics.*.*' => ['required_with:characteristics', function (string $attr, mixed $val, Closure $fail) {
                if (is_numeric($val) && Characteristic::find($val) == null) { // If it's a numeric value but doesn't exists in the tags table
                    $fail('El campo de characteristica seleccionado no existe o es inválido.');
                }
            },],
        ];
    }
}
