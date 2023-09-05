<?php

namespace App\Http\Requests\Backstage\TimeSlots;

use Illuminate\Foundation\Http\FormRequest;

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
     */
    public function rules(): array
    {
        return [
            'starts_at' => 'required',
            'ends_at' => 'required',
        ];
    }
}
