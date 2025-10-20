<?php

namespace App\Http\Requests\Base\TemporaryInvitation;

use App\Enums\InvitationTypes;
use App\Models\Base\TemporaryInvitation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invitation_type' => [
                'required',
                'integer',
                new Enum(InvitationTypes::class),
            ],
            'expiration' => [
                'required',
                Rule::in([
                    ...TemporaryInvitation::EXPIRATION_TIMES,
                    'custom'
                ]),
            ],
            'store_id' => 'required|integer|exists:stores,id',
            'token' => 'prohibited',
            'uses_left' => 'prohibited',
            'custom_expiration' => 'required_if:expiration,custom|date|after_or_equal:today',
        ];
    }
}
