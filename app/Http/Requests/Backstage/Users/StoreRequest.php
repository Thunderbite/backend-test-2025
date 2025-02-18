<?php

declare(strict_types=1);

namespace App\Http\Requests\Backstage\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'level' => [
                Rule::in(['admin', 'download', 'readonly']),
                'required',
            ],
        ];
    }
}
