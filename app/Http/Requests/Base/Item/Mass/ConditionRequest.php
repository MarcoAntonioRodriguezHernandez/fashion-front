<?php

namespace App\Http\Requests\Base\Item\Mass;

use App\Enums\ItemConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ConditionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*' => 'required|integer|exists:items,id',
            'condition' => ['required', 'integer', new Enum(ItemConditions::class)],
        ];
    }
}
