<?php

namespace App\Http\Requests\Base\Item\Mass;

use App\Enums\ItemStatuses;
use App\Enums\ItemConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ConditionStatusRequest extends FormRequest

{
    public function rules(): array
    {
        return [
            'items'      => 'required_without:select_all|array|min:1',
            'items.*'    => 'integer|exists:items,id',
            'select_all' => 'sometimes|boolean',
            'filters'    => 'required_if:select_all,1|array',
            'condition'  => ['required', 'integer', new Enum(ItemConditions::class)],
            'status'     => ['required', 'integer', new Enum(ItemStatuses::class)],
        ];
    }
}
