<?php

namespace App\Http\Requests\Base\Event;

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
            'event_type' => 'required|integer|exists:event_types,id',
            'specification' => 'required|string|max:255',
            'scheduled_date' => 'required|date|after_or_equal:today',
        ];
    }
}
