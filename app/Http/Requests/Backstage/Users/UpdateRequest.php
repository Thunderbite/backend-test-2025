<?php

namespace App\Http\Requests\Backstage\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
