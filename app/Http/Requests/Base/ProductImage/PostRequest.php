<?php

namespace App\Http\Requests\Base\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'camera_perspective' => 'required|string',
            'src_image' => 'required|image',

        ];
    }
}
