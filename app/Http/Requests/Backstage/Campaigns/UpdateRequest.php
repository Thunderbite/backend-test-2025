<?php

declare(strict_types=1);

namespace App\Http\Requests\Backstage\Campaigns;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateRequest extends FormRequest
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
            'timezone' => 'required',
            'starts_at' => 'required',
            'ends_at' => 'required',
        ];
    }
}
