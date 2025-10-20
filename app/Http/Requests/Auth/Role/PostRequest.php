<?php

namespace App\Http\Requests\Auth\Role;

use App\Models\Auth\Module;
use Closure;
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
            'name' => 'required|string|max:255',
            'slug' => 'prohibited',
            'description' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
            'permissions.*' => ['required', 'array', function (string $attribute, mixed $value, Closure $fail) {
                if (Module::find(explode('.', $attribute)[1]) === null) {
                    $fail('Some of the selected permission is invalid.');
                }
            },],
        ];
    }
}
