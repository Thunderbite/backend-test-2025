<?php

namespace App\Http\Requests\Backstage\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route()->user->id),
            ],
            'level' => [
                Rule::in(['admin', 'download', 'readonly']),
                'required',
            ],
            'current_password' => [
                'sometimes',
                'nullable',
                'password',
                'required_with:password',
            ],
            'password' => [
                'required_with:current_password',
                'nullable',
                'confirmed',
                'min:8',
            ],
        ];
    }
}
