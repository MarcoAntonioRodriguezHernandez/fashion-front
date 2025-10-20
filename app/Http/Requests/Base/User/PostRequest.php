<?php

namespace App\Http\Requests\Base\User;

use App\Enums\Genders;
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
            $this->getEmployeeDetailRules(),
            $this->getClientDetailRules(),
        );

        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|regex:/^\d{10}$/|unique:users,phone',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ...$extraRules,
        ];
    }

    private function getEmployeeDetailRules()
    {
        return [
            'employee_detail' => 'nullable|array|array:store_id',
            'employee_detail.store_id' => 'required_with:employee_detail|integer|exists:stores,id',
            'employee_detail.notifications_allowed' => 'prohibited',
        ];
    }

    private function getClientDetailRules()
    {
        return [
            'client_detail' => 'nullable|array|array:date_of_birth,gender',
            'client_detail.date_of_birth' => 'nullable|date|before:today',
            'client_detail.gender' => ['required_with:client_detail', 'integer', new Enum(Genders::class)],
            'client_detail.credit' => 'prohibited',
        ];
    }
}
