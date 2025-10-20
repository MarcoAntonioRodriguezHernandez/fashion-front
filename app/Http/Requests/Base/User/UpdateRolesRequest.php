<?php

namespace App\Http\Requests\Base\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roles' => 'nullable|array|min:1',
            'roles.*' => 'nullable|integer|exists:roles,id',
        ];
    }
}
