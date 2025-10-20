<?php
namespace App\Http\Requests\Base\Colors;

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
            'parent_color_id' => 'required|integer',
            'name' => 'required|string|unique:colors,name',
            'hexadecimal' => 'required|string',
            'texture_src' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
