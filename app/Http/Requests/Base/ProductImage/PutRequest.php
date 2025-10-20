<?php

namespace App\Http\Requests\Base\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

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
            'product_id' => 'nullable|exists:products,id',
            'color_id' => 'nullable|exists:colors,id',
            'camera_perspective' => 'nullable|string',
            'src_image' => 'nullable|image',
        ];
    }
}
